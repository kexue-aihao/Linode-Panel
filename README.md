# Linode-Panel

Linode-Panel 是一个用于快速启动和管理 Linode 实例的自部署面板。

当前版本采用 **PHP + MySQL/MariaDB** 架构，主要面向 **aaPanel** 部署。它是普通 PHP 网站，不需要 Go、Node、Docker、systemd，也不需要单独监听后端端口。

## 功能

- VM 管理：Linode 实例列表、创建、开机、重启、关机、删除。
- Linode 号池：支持多个 Linode Personal Access Token，支持默认账号切换和账号检测。
- 代理配置：支持外部 HTTP / SOCKS5 代理，支持代理 API 批量导入。
- DNS 管理：内部集成彩虹 DNS 面板接入，支持配置、域名读取、解析记录保存和 DNS 绑定。
- 自动补机：支持保存补机策略、手动执行一次，并组合 Linode 号池、代理、DNS、通知和日志。
- 通知设置：支持 Telegram Bot Token、个人 Chat ID 和群组 Chat ID。
- 执行日志：记录账号、代理、DNS、通知、安全和实例操作，支持导出。
- 账号安全：管理员账号、密码和会话安全管理。
- 管理员后台：查看资源统计、PHP 扩展和运行环境。
- MySQL/MariaDB 持久化配置，支持 phpMyAdmin 管理。
- 中文交互式安装脚本 `install.sh`。
- 中文交互式升级脚本 `update.sh`。

## 架构说明

```text
浏览器
  ↓
aaPanel 网站 / Nginx / OpenResty
  ↓
PHP 8.1+
  ↓
MySQL/MariaDB
  ↓
Linode API
```

项目入口：

```text
index.php              面板页面
api.php                后端 API
app/                   PHP 后端代码
assets/                前端 JS/CSS
config.php             本地数据库配置，安装脚本生成
config.example.php     配置模板
install.sh             安装脚本
update.sh              升级脚本
```

## 环境依赖

### 服务器

推荐系统：

- Debian 11+
- Ubuntu 20.04+
- CentOS 8+
- Rocky Linux 8+
- AlmaLinux 8+

推荐面板：

- aaPanel

### aaPanel 软件

在 aaPanel 中安装：

- Nginx 或 OpenResty
- PHP `8.1` 或更高版本
- MySQL 8+ 或 MariaDB 10.6+
- phpMyAdmin

### PHP 扩展

PHP 必须启用：

- `pdo_mysql`
- `curl`
- `json`
- `session`

在 aaPanel 中检查路径：

```text
软件商店 -> PHP -> 设置 -> 安装扩展
```

### SSH 命令依赖

安装脚本需要服务器具备：

- `git`
- `curl`
- `tar`
- `ca-certificates`

`install.sh` 会尝试自动安装这些基础命令。

### 网络要求

服务器需要能访问：

```text
https://github.com/kexue-aihao/Linode-Panel.git
https://api.linode.com
```

如果服务器无法访问 Linode API，面板可以打开，但读取实例、创建实例等功能会失败。

## aaPanel 部署流程

### 1. 创建网站

在 aaPanel 中创建网站，例如：

```text
linode.example.com
```

建议：

- PHP 版本选择 `8.1+`
- 开启 HTTPS
- 默认首页包含 `index.php`

### 2. 创建数据库

在 aaPanel 的“数据库”页面创建数据库：

```text
数据库名：linode_panel
用户名：linode_panel
密码：自行生成强密码
```

数据库字符集建议：

```text
utf8mb4
```

数据库用户至少需要这些权限：

```sql
CREATE, ALTER, SELECT, INSERT, UPDATE, DELETE, INDEX
```

### 3. 拉取项目

SSH 登录服务器后执行：

```sh
cd /root
git clone https://github.com/kexue-aihao/Linode-Panel.git Linode-Panel
cd Linode-Panel
chmod +x install.sh update.sh
```

### 4. 运行安装脚本

```sh
./install.sh
```

脚本会提示填写：

```text
源码仓库地址
网站根目录
MySQL/MariaDB 主机
MySQL/MariaDB 端口
数据库名
数据库用户
数据库密码
```

典型填写：

```text
源码仓库地址：https://github.com/kexue-aihao/Linode-Panel.git
网站根目录：/www/wwwroot/linode-panel
MySQL/MariaDB 主机：127.0.0.1
MySQL/MariaDB 端口：3306
数据库名：linode_panel
数据库用户：linode_panel
数据库密码：aaPanel 中创建数据库时设置的密码
```

安装脚本会：

- 同步源码到网站目录。
- 生成 `config.php`。
- 验证数据库连接。
- 提示需要检查的 PHP 扩展。

### 5. 设置网站根目录

回到 aaPanel，把网站根目录设置为：

```text
/www/wwwroot/linode-panel
```

如果你的安装脚本里填写了其他目录，以实际填写的目录为准。

### 6. 添加 Nginx 安全规则

在 aaPanel 网站配置中加入：

```nginx
location = /config.php { deny all; }
location ~* \.(md|sh|example)$ { deny all; }
location ~ /\. { deny all; }
```

项目也包含 `.htaccess`，但如果 aaPanel 使用 Nginx/OpenResty，应优先使用上面的 Nginx 规则。

### 7. 访问面板

访问你的域名：

```text
https://linode.example.com
```

首次打开会进入初始化页面。

初始化时填写：

- 面板管理员用户名
- 面板管理员密码

初始化完成后会自动进入面板。请先打开「Linode 号池」，添加至少一个 Linode Token，并设为默认账号，然后再加载实例、创建实例或管理防火墙。

## 功能配置

### Linode 号池

进入「Linode 号池」：

1. 填写账号名称。
2. 填写 Linode Personal Access Token。
3. 可选绑定一个代理配置。
4. 勾选“设为默认账号”。
5. 保存时会调用 Linode `/v4/profile` 验证 Token。

默认账号用于 VM 管理、创建实例、读取目录和防火墙等 Linode API 操作。

### 代理配置

进入「代理配置」：

- 支持 `HTTP` 代理。
- 支持 `SOCKS5` 代理。
- 支持手动填写主机、端口、用户名和密码。
- 支持代理 API 批量导入，一行一个代理或 JSON 返回均可解析。

SOCKS5 API 示例：

```text
https://www.miyaip.com/api/ProxyLogic/Generate?Num=10&SessionTime=30&Server=us&Format=0&Crc=1F3971BE1C2F5F5F000A2FBF6A8E6C72&Pool=1&KeyName=xofolatlwzmiyaip&GenType=socks5
```

HTTP API 示例：

```text
https://www.miyaip.com/api/ProxyLogic/Generate?Num=10&SessionTime=30&Server=us&Format=0&Crc=1F3971BE1C2F5F5F000A2FBF6A8E6C72&Pool=1&KeyName=xofolatlwzmiyaip&GenType=http
```

如果 API 地址中包含 `GenType=socks5` 或 `GenType=http`，面板会优先按对应协议解析 `host:port:user:pass` 格式。

### DNS 管理

进入「DNS 管理」添加彩虹 DNS 面板配置：

- 面板地址：彩虹 DNS 面板访问地址，例如 `https://dns.example.com`。
- 账号密码模式：填写彩虹 DNS 用户名和密码。
- API 模式：填写 UID 和 API Key。

保存后可以测试连接、读取域名列表，并通过域名 ID 添加或更新解析记录。DNS 绑定用于后续 VM 创建、换 IP、自动补机成功后的自动解析同步。

### 通知、日志、安全和后台

- 「自动补机」可保存策略并手动执行一次。若需要定时执行，可在 aaPanel 计划任务中定时请求对应策略的 `replenish/policies/{id}/run` API。
- 「通知设置」支持 Telegram Bot Token、个人 Chat ID 和群组 Chat ID。
- 「执行日志」记录关键操作并支持导出。
- 「账号安全」用于修改管理员账号和密码。
- 「管理员后台」展示 Linode 号池、代理、DNS、日志和 PHP 扩展状态。

## Linode Token 权限建议

创建 Token 时按需授权。基础实例管理建议包含：

```text
linodes:read_write
images:read_only
stackscripts:read_only
firewall:read_write
events:read_only
```

如果后续需要 DNS、对象存储、LKE，再额外增加对应权限。

## 健康检查

浏览器或 curl 访问：

```text
https://你的域名/api.php?action=health
```

正常返回类似：

```json
{"ok":true,"configured":false,"time":"2026-06-13T00:00:00+00:00"}
```

如果返回 `缺少 config.php`，说明没有运行安装脚本，或网站根目录不是安装目录。

如果返回数据库错误，检查：

- `config.php` 中数据库主机、端口、库名、用户、密码是否正确。
- aaPanel 数据库是否已创建。
- PHP 是否启用 `pdo_mysql`。

如果 Linode 功能请求失败，检查：

- PHP 是否启用 `curl`。
- 服务器是否能访问 `https://api.linode.com`。
- Token 是否正确、权限是否足够。

## 升级

进入网站目录：

```sh
cd /www/wwwroot/linode-panel
./update.sh
```

升级脚本会：

- 备份 `config.php` 到 `/root/linode-panel-backups`
- 拉取或同步最新代码
- 保留现有 `config.php`

PHP 版不需要重启 systemd。如果 aaPanel 开启了 Opcache，升级后建议在 aaPanel 中重载 PHP。

## 文件权限

建议：

```sh
chown -R www:www /www/wwwroot/linode-panel
chmod 640 /www/wwwroot/linode-panel/config.php
```

如果 aaPanel 的 PHP 运行用户不是 `www`，请按实际用户调整。

## 数据库表

面板会自动创建：

```text
panel_settings
```

该表保存：

- 管理员用户名
- 管理员密码哈希
- Linode Token
- 代理 URL
- 会话密钥

可以通过 phpMyAdmin 查看，但不要公开或复制其中的敏感字段。

## 常见问题

### 访问页面空白

检查 PHP 错误日志，并确认 PHP 版本为 `8.1+`。

### API 返回 500

通常是数据库配置或 PHP 扩展问题。先访问：

```text
https://你的域名/api.php?action=health
```

### 提示缺少 pdo_mysql

在 aaPanel 中进入 PHP 设置，安装或启用 `pdo_mysql`。

### Linode API 请求失败

检查 PHP `curl` 扩展和服务器到 `api.linode.com` 的网络。

### config.php 被浏览器下载或显示

立刻在 aaPanel 网站配置中加入 Nginx 规则：

```nginx
location = /config.php { deny all; }
```

并确认 PHP 正常解析 `.php` 文件。

## 安全建议

- 必须启用 HTTPS。
- `config.php` 权限建议为 `640`。
- Linode Token 使用最小权限。
- phpMyAdmin 使用强密码，并限制访问。
- 定期备份 MySQL/MariaDB 数据库。
- 不要把 `config.php` 提交到 Git。

## Linode API 学习资料

- [Linode API v4 学习笔记](docs/linode-api-study.md)
- [Linode API v4 全量端点索引](docs/linode-api-endpoints.md)
