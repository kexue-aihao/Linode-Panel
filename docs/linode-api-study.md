# Linode API v4 学习笔记

本文档用于给 `Linode-Panel` 项目建立一份面向开发的 Linode 官方 API 学习地图。完整端点索引见 [linode-api-endpoints.md](linode-api-endpoints.md)。

## 官方资料

- API 参考：https://techdocs.akamai.com/linode-api/reference/api
- OpenAPI 仓库：https://github.com/linode/linode-api-openapi
- API Base URL：`https://api.linode.com`
- 当前学习基准：OpenAPI 标题 `Akamai: Linode API`，版本 `4.229.1`
- 规模：`334` 个路径，`505` 个 HTTP 操作

Linode API 现在属于 Akamai Cloud 体系，官方文档域名是 `techdocs.akamai.com`，代码和 OpenAPI 规范仍保留 Linode 命名。

## 请求基础

所有稳定接口都使用 `/v4`：

```http
GET https://api.linode.com/v4/linode/instances
Authorization: Bearer <TOKEN>
Accept: application/json
Content-Type: application/json
```

部分 Beta 接口可使用 `/v4beta`。OpenAPI 里的路径写作 `/{apiVersion}/...`，实际面板代码通常应固定为 `/v4`，只有明确需要 Beta 功能时才允许切换。

## 认证方式

官方规范定义了两种认证：

- Personal Access Token：HTTP Bearer Token，最适合个人面板、后台服务、CLI 脚本。
- OAuth2 Authorization Code：适合多用户 SaaS 或第三方应用，让用户授权自己的 Linode 账户。

Authorization 头格式：

```http
Authorization: Bearer <TOKEN>
```

OAuth scope 按资源拆分，常见权限如下：

| Scope | 用途 |
|---|---|
| `linodes:read_only` / `linodes:read_write` | 云主机读取/管理 |
| `ips:read_only` / `ips:read_write` | IP 地址读取/管理 |
| `firewall:read_only` / `firewall:read_write` | 防火墙读取/管理 |
| `domains:read_only` / `domains:read_write` | DNS 域名读取/管理 |
| `volumes:read_only` / `volumes:read_write` | 块存储读取/管理 |
| `nodebalancers:read_only` / `nodebalancers:read_write` | 负载均衡读取/管理 |
| `images:read_only` / `images:read_write` | 镜像读取/管理 |
| `stackscripts:read_only` / `stackscripts:read_write` | StackScript 读取/管理 |
| `lke:read_only` / `lke:read_write` | Kubernetes 集群读取/管理 |
| `object_storage:read_only` / `object_storage:read_write` | 对象存储读取/管理 |
| `account:read_only` / `account:read_write` | 账户、账单、用户等 |

面板建议：

- MVP 用 Personal Access Token，先实现单用户管理。
- Token 只存服务端，不返回前端。
- 按最小权限创建 Token，例如只启动实例时需要 `linodes:read_write`、`images:read_only`、`stackscripts:read_only`、`firewall:read_only`。
- 写操作前做二次确认，尤其是删除、重建、关机、迁移、Resize、Rebuild。

## 分页

集合类 GET 接口通常支持：

- `page`：页码，默认 `1`，最小 `1`
- `page_size`：每页数量，默认 `100`，最小 `25`，最大 `500`

典型响应：

```json
{
  "data": [],
  "page": 1,
  "pages": 1,
  "results": 0
}
```

面板代码应封装一个 `listAll()` 辅助函数，循环读取直到 `page >= pages`，避免列表数据只显示第一页。

## 过滤和排序

列表接口通常支持 `X-Filter` 请求头，值是 JSON 对象。常见用途：

```http
X-Filter: {"region":"us-east"}
X-Filter: {"+order_by":"created","+order":"desc"}
X-Filter: {"+or":[{"label":"web-1"},{"label":"web-2"}]}
```

官方过滤语义包括：

- 简单字段匹配：`{"region":"us-east"}`
- 比较：`+gt`、`+gte`、`+lt`、`+lte`
- 非等：`+neq`
- 包含：`+contains`
- 组合：`+and`、`+or`
- 排序：`+order_by`、`+order`

实现建议：不要让前端直接拼 `X-Filter` 字符串；由后端接收结构化筛选参数，再序列化成 JSON header。

## 错误模型

错误对象核心字段：

```json
{
  "field": "fieldname",
  "reason": "fieldname must be a valid value"
}
```

常见响应会包含：

```json
{
  "errors": [
    {
      "field": "label",
      "reason": "Label must be unique."
    }
  ]
}
```

面板建议：

- 保留 `field` 和 `reason`，不要只显示 HTTP 状态码。
- 401/403 提示 Token 或 scope 问题。
- 404 区分资源不存在与用户无权访问。
- 429 做退避重试或提示稍后再试。
- 对 POST 动作类接口，优先用 Events 查询异步结果。

## 资源总览

OpenAPI 中按 tag 分组，主要资源如下。

### 面板 MVP 最相关

| 资源 | 重点接口 | 用途 |
|---|---|---|
| Regions | `GET /v4/regions` | 选择机房 |
| Linode types | `GET /v4/linode/types` | 选择套餐 |
| Images | `GET /v4/images` | 选择系统镜像 |
| StackScripts | `GET /v4/linode/stackscripts` | 选择启动脚本 |
| Linode instances | `/v4/linode/instances` | 创建、列表、开关机、重建、删除 |
| Linode IP addresses | `/v4/linode/instances/{linodeId}/ips` | 查看/管理实例 IP |
| Linode disks | `/v4/linode/instances/{linodeId}/disks` | 磁盘管理 |
| Firewalls | `/v4/networking/firewalls` | 防火墙规则与绑定 |
| Events | `/v4/account/events` | 操作状态和异步进度 |
| Account | `/v4/account` | 账户基础信息 |

### 常见扩展

| 资源 | 重点接口 | 用途 |
|---|---|---|
| Domains / Domain records | `/v4/domains` | DNS 区域和记录 |
| Volumes | `/v4/volumes` | 块存储 |
| NodeBalancers | `/v4/nodebalancers` | 负载均衡 |
| VPCs / VPC subnets | `/v4/vpcs` | 私有网络 |
| LKE clusters | `/v4/lke/clusters` | Kubernetes |
| Object Storage Buckets | `/v4/object-storage/buckets` | 对象存储 |
| Databases MySQL / PostgreSQL | `/v4/databases/...` | 托管数据库 |
| Support tickets | `/v4/support/tickets` | 工单 |
| Users / Grants / Tokens | `/v4/account/users`、`/v4/profile/tokens` | 用户、授权、Token 管理 |

## 快速启动实例流程

`Linode-Panel` 的目标是快速启动 Linode 实例，可以按下面流程实现：

1. 读取静态选项：
   - `GET /v4/regions`
   - `GET /v4/linode/types`
   - `GET /v4/images`
   - 可选：`GET /v4/linode/stackscripts`
   - 可选：`GET /v4/networking/firewalls`

2. 用户填写创建参数：
   - `region`
   - `type`
   - `image`
   - `root_pass` 或 `authorized_keys`
   - `label`
   - `tags`
   - `backups_enabled`
   - `private_ip`
   - `firewall_id`
   - 可选：`stackscript_id`、`stackscript_data`

3. 创建实例：

```http
POST /v4/linode/instances
```

4. 轮询结果：
   - `GET /v4/linode/instances/{linodeId}`
   - `GET /v4/account/events`

5. 展示连接信息：
   - `GET /v4/linode/instances/{linodeId}/ips`

6. 提供后续操作：
   - 开机：`POST /v4/linode/instances/{linodeId}/boot`
   - 重启：`POST /v4/linode/instances/{linodeId}/reboot`
   - 关机：`POST /v4/linode/instances/{linodeId}/shutdown`
   - 重装：`POST /v4/linode/instances/{linodeId}/rebuild`
   - 重设密码：`POST /v4/linode/instances/{linodeId}/password`
   - 删除：`DELETE /v4/linode/instances/{linodeId}`

## 创建实例请求字段

创建 Linode 的核心请求通常包括：

```json
{
  "region": "us-east",
  "type": "g6-standard-1",
  "image": "linode/ubuntu24.04",
  "label": "web-1",
  "root_pass": "use-a-strong-secret",
  "authorized_keys": [
    "ssh-ed25519 AAAA..."
  ],
  "tags": [
    "panel"
  ],
  "backups_enabled": false,
  "private_ip": false,
  "firewall_id": 123
}
```

注意：

- `root_pass` 属于敏感字段，日志中必须脱敏。
- 推荐优先使用 `authorized_keys`，少用密码登录。
- `label` 需要在账户下保持合理唯一，便于用户识别。
- `firewall_id` 应默认绑定一个最小开放端口的防火墙。

## 实例生命周期

实例相关的主要操作：

| 操作 | Endpoint | 风险 |
|---|---|---|
| 列表 | `GET /v4/linode/instances` | 低 |
| 创建 | `POST /v4/linode/instances` | 中，产生费用 |
| 详情 | `GET /v4/linode/instances/{linodeId}` | 低 |
| 更新标签等 | `PUT /v4/linode/instances/{linodeId}` | 中 |
| 开机 | `POST /v4/linode/instances/{linodeId}/boot` | 中 |
| 重启 | `POST /v4/linode/instances/{linodeId}/reboot` | 中 |
| 关机 | `POST /v4/linode/instances/{linodeId}/shutdown` | 中 |
| 重建 | `POST /v4/linode/instances/{linodeId}/rebuild` | 高，可能清空系统盘 |
| Rescue | `POST /v4/linode/instances/{linodeId}/rescue` | 中 |
| Resize | `POST /v4/linode/instances/{linodeId}/resize` | 高，可能停机并改变费用 |
| Migrate | `POST /v4/linode/instances/{linodeId}/migrate` | 高，可能停机 |
| Delete | `DELETE /v4/linode/instances/{linodeId}` | 高，删除资源 |

面板应把高风险操作做成显式确认，并显示目标实例 label、region、IP。

## 网络和安全

相关资源：

- 实例 IP：`/v4/linode/instances/{linodeId}/ips`
- 全局 IP：`/v4/networking/ips`
- IPv6 ranges：`/v4/networking/ipv6/ranges`
- Firewalls：`/v4/networking/firewalls`
- Firewall devices：`/v4/networking/firewalls/{firewallId}/devices`
- VPC：`/v4/vpcs`
- VPC subnet：`/v4/vpcs/{vpcId}/subnets`
- VLANs：`/v4/networking/vlans`

快速启动面板的安全默认值：

- 默认绑定防火墙。
- SSH 只允许用户指定 IP 段时开放；否则提示风险。
- Web 端口按模板开放，例如 80/443。
- 不在前端展示完整 Token。

## DNS

域名资源：

- `GET /v4/domains`
- `POST /v4/domains`
- `GET /v4/domains/{domainId}`
- `PUT /v4/domains/{domainId}`
- `DELETE /v4/domains/{domainId}`
- `GET /v4/domains/{domainId}/records`
- `POST /v4/domains/{domainId}/records`
- `PUT /v4/domains/{domainId}/records/{recordId}`
- `DELETE /v4/domains/{domainId}/records/{recordId}`

面板可提供“创建实例后自动添加 A/AAAA 记录”的工作流。

## 存储

块存储：

- `GET /v4/volumes`
- `POST /v4/volumes`
- `POST /v4/volumes/{volumeId}/attach`
- `POST /v4/volumes/{volumeId}/detach`
- `POST /v4/volumes/{volumeId}/resize`
- `DELETE /v4/volumes/{volumeId}`

实例磁盘：

- `GET /v4/linode/instances/{linodeId}/disks`
- `POST /v4/linode/instances/{linodeId}/disks`
- `PUT /v4/linode/instances/{linodeId}/disks/{diskId}`
- `POST /v4/linode/instances/{linodeId}/disks/{diskId}/resize`
- `DELETE /v4/linode/instances/{linodeId}/disks/{diskId}`

## LKE、对象存储和数据库

这些适合后续扩展，不建议放进第一个 MVP：

- LKE：`/v4/lke/clusters`、node pools、nodes、kubeconfig、service tokens
- Object Storage：clusters、buckets、access keys、TLS certificates、endpoints
- MySQL：`/v4/databases/mysql/...`
- PostgreSQL：`/v4/databases/postgresql/...`

它们的资源关系更复杂，最好在基础实例管理稳定后再加入。

## 面板 API Client 设计建议

建议封装一个 Linode client：

- `request(method, path, body, options)`
- 自动加 `Authorization`
- 自动 JSON 编解码
- 自动处理分页
- 自动转换 Linode 错误模型
- 自动记录 request id、method、path、status、耗时
- 日志脱敏 Token、密码、SSH 私钥、StackScript 密钥变量

推荐模块分层：

| 模块 | 职责 |
|---|---|
| `linode/client` | HTTP 基础、认证、分页、错误 |
| `linode/catalog` | regions、types、images、stackscripts |
| `linode/instances` | 创建、生命周期、IP、事件 |
| `linode/firewalls` | 防火墙列表、规则、绑定 |
| `linode/domains` | DNS 自动绑定 |
| `linode/account` | 账户、配额、事件 |

## 优先学习路线

1. 认证、错误、分页、过滤。
2. `GET /v4/regions`、`GET /v4/linode/types`、`GET /v4/images`。
3. `POST /v4/linode/instances` 创建实例。
4. 实例生命周期操作。
5. Events 轮询和状态展示。
6. Firewalls 默认安全策略。
7. DNS 自动解析。
8. Volumes、NodeBalancers、VPC。
9. LKE、Object Storage、Databases。

## 风险清单

- 创建、Resize、备份、数据库、负载均衡、对象存储都会产生费用。
- Rebuild、Delete、Disk 操作可能导致数据丢失。
- Token scope 过大时，面板漏洞会扩大损害范围。
- 公开 root 密码、日志记录敏感字段、前端持久化 Token 都是高危。
- 只读取第一页会导致资源展示不完整。
- 长耗时操作不能只依赖 POST 响应，应结合 Events 或资源状态轮询。

