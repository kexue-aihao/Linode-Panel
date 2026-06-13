# Linode-Panel

一个用于快速启动和管理 Linode 实例的自部署面板。

## 功能

- 使用 Linode Personal Access Token 管理实例。
- 支持初始化管理员账号、登录会话、Token 服务端保存。
- 支持实例列表、创建、开机、重启、关机、删除。
- 支持区域、套餐、镜像、防火墙目录读取。
- 支持创建默认 SSH/HTTP/HTTPS 防火墙。
- 支持查看 Linode 最近事件。
- 使用 MySQL/MariaDB 高性能数据库，支持 phpMyAdmin 管理。
- 支持 aaPanel、AcePanel、1Panel 通过反向代理部署。
- 提供中文交互式一键部署脚本 `install.sh` 和一键升级脚本 `update.sh`。

## 推荐架构

本项目采用 Go 单二进制 + MySQL/MariaDB 架构：

- 后端：Go 标准库 HTTP 服务。
- 前端：内嵌 HTML/CSS/JavaScript，无需 Node 构建。
- 数据库：MySQL 8 或 MariaDB 10.6+，表结构自动初始化。
- 管理工具：可用 phpMyAdmin 管理数据库和 `panel_settings` 表。
- 运行：systemd 常驻服务，默认监听 `127.0.0.1:8088`。
- 接入：aaPanel、AcePanel、1Panel 或 Nginx 反向代理到本地端口。

这个架构适合面板部署：性能足够、数据库可视化管理方便、升级简单，迁移时备份数据库即可。

## 部署环境依赖

### 主机安装依赖

适用于直接运行 `install.sh` 的方式：

- 操作系统：推荐 Debian 11+、Ubuntu 20.04+、CentOS 8+、Rocky Linux 8+、AlmaLinux 8+。
- 系统权限：需要 `root` 或可使用 `sudo` 的用户。
- 服务管理：需要 `systemd`，用于注册和守护 `linode-panel` 服务。
- 基础命令：`git`、`curl`、`tar`、`ca-certificates`、`openssl`。
- 编译环境：Go `1.24.0` 或更高版本。脚本未检测到 Go 时会自动下载 Go `1.24.0`。
- 数据库：MySQL 8+ 或 MariaDB 10.6+。
- 数据库管理：phpMyAdmin，可由 aaPanel、AcePanel、1Panel 或系统包管理器安装。
- 反向代理：Nginx、OpenResty、Apache 或面板自带反向代理功能。
- 网络访问：服务器需要能访问 Git 仓库、Go 下载站点、Go 模块代理和 Linode API。

`install.sh` 会尝试自动安装基础命令，并可在本机存在 `mysql` 命令时交互式创建数据库和用户。MySQL/MariaDB 服务本身、phpMyAdmin、域名解析和 HTTPS 证书建议在服务器面板中提前准备。

如果自动创建数据库时出现 `ERROR 1045 (28000): Access denied`，表示填写的 MySQL/MariaDB 管理员账号或密码不正确，或该账号没有建库/授权权限。此时可以复制脚本输出的 SQL 到 phpMyAdmin 执行，手动建好数据库和用户后继续安装。

### 数据库权限要求

面板连接数据库的用户需要拥有目标数据库的完整权限：

```sql
CREATE, ALTER, SELECT, INSERT, UPDATE, DELETE, INDEX
```

首次启动时，面板会自动创建 `panel_settings` 表。使用 phpMyAdmin 手动创建数据库时，建议字符集选择 `utf8mb4`，排序规则选择 `utf8mb4_unicode_ci`。

### Docker Compose 依赖

适用于 1Panel 编排、AcePanel Docker 编排或手动 `docker compose up -d`：

- Docker Engine 24+。
- Docker Compose v2。
- 服务器需要能拉取 `mariadb:11`、`phpmyadmin:5`、`golang:1.24-alpine`、`alpine:3.20` 镜像。
- 至少保留一个持久化目录用于 `./mysql-data`。

Compose 模式会同时启动 `linode-panel`、`mariadb` 和 `phpmyadmin`，不需要在宿主机额外安装 Go 或 MySQL。

## 一键部署

在服务器上运行：

```sh
curl -fsSL https://raw.githubusercontent.com/your-org/linode-panel/main/install.sh | sudo sh
```

如果是上传源码到服务器后本地安装：

```sh
sudo sh install.sh
```

`install.sh` 是中文交互式脚本，会依次提示：

- 源码仓库地址
- 安装目录
- 面板监听地址和端口
- MySQL/MariaDB 主机、端口、数据库名、用户名、密码
- 是否自动创建数据库和用户

也可以用环境变量预设：

```sh
REPO_URL=https://github.com/your-org/linode-panel.git \
INSTALL_DIR=/opt/linode-panel \
LINODE_PANEL_HOST=127.0.0.1 \
LINODE_PANEL_PORT=8088 \
LINODE_PANEL_DB_HOST=127.0.0.1 \
LINODE_PANEL_DB_PORT=3306 \
LINODE_PANEL_DB_NAME=linode_panel \
LINODE_PANEL_DB_USER=linode_panel \
LINODE_PANEL_DB_PASSWORD='change-this-password' \
sudo -E sh install.sh
```

生产环境建议不要直接开放端口，使用面板反向代理并启用 HTTPS。

## 一键升级

```sh
sudo sh /opt/linode-panel/update.sh
```

`update.sh` 也是中文交互式脚本，会提示是否修改数据库连接配置，然后拉取新代码、重新编译并重启 `linode-panel` 服务。

## 数据库和 phpMyAdmin

面板会自动创建并维护表：

```text
panel_settings
```

该表保存管理员账号哈希、Linode Token、代理 URL、会话密钥等配置。可以通过 phpMyAdmin 进入 `linode_panel` 数据库查看该表。

注意：

- `linode_token` 是敏感字段，不建议在 phpMyAdmin 中随意复制或暴露。
- 修改数据库配置后需要重启服务。
- 生产环境建议定期备份 MySQL/MariaDB 数据库。

## aaPanel 部署

1. 在 aaPanel 中安装 MySQL/MariaDB 和 phpMyAdmin。
2. 运行 `install.sh`，按中文提示填写数据库信息，监听地址建议保持 `127.0.0.1`。
3. 在 aaPanel 网站中新建站点，例如 `linode.example.com`。
4. 打开站点设置，配置反向代理：
   - 目标 URL：`http://127.0.0.1:8088`
   - 发送域名：`$host`
5. 在 SSL 菜单申请证书并强制 HTTPS。

## AcePanel 部署

1. 在 AcePanel 中准备 MySQL/MariaDB 数据库，并启用 phpMyAdmin 或数据库管理工具。
2. 运行 `install.sh`，按中文提示填写数据库连接。
3. 在 AcePanel 中创建网站或反向代理应用。
4. 上游地址填写：

```text
http://127.0.0.1:8088
```

5. 绑定域名并开启 HTTPS。

如果 AcePanel 支持 Docker Compose，也可以直接使用仓库中的 `docker-compose.yml`。

## 1Panel 部署

### 方式一：主机安装

1. 在 1Panel 中创建 MySQL/MariaDB 数据库。
2. 运行 `install.sh`，按中文提示填写数据库连接。
3. 在 1Panel 的网站管理中创建反向代理。
4. 代理地址填写 `http://127.0.0.1:8088`。
5. 申请 HTTPS 证书。

### 方式二：Docker Compose

仓库中的 `docker-compose.yml` 包含：

- `linode-panel`
- `mariadb`
- `phpmyadmin`

启动：

```sh
docker compose up -d
```

默认端口：

- 面板：`8088`
- phpMyAdmin：`8089`

然后在 1Panel 网站里反代到 `http://127.0.0.1:8088`。

## systemd 管理

```sh
systemctl status linode-panel
journalctl -u linode-panel -f
systemctl restart linode-panel
```

配置文件：

```text
/etc/linode-panel/linode-panel.env
```

配置项：

```text
LINODE_PANEL_HOST=127.0.0.1
LINODE_PANEL_PORT=8088
LINODE_PANEL_DB_HOST=127.0.0.1
LINODE_PANEL_DB_PORT=3306
LINODE_PANEL_DB_NAME=linode_panel
LINODE_PANEL_DB_USER=linode_panel
LINODE_PANEL_DB_PASSWORD=change-this-password
```

## 安全建议

- Linode Token 只保存在服务端数据库中，不会返回前端。
- Token 建议使用最小权限，例如实例管理只给必要 scope。
- phpMyAdmin 必须开启强密码和 HTTPS。
- 生产环境必须放在 HTTPS 后面。
- 创建、删除、关机等操作会影响费用或数据，面板中已做确认提示。
- 定期备份 MySQL/MariaDB 数据库。

## Linode API 学习资料

- [Linode API v4 学习笔记](docs/linode-api-study.md)
- [Linode API v4 全量端点索引](docs/linode-api-endpoints.md)
