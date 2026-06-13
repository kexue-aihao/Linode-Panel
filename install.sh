#!/usr/bin/env sh
set -eu

APP_NAME="linode-panel"
DEFAULT_REPO_URL="https://github.com/your-org/linode-panel.git"
DEFAULT_INSTALL_DIR="/opt/linode-panel"
DEFAULT_ENV_DIR="/etc/linode-panel"
DEFAULT_HOST="127.0.0.1"
DEFAULT_PORT="8088"
DEFAULT_DB_HOST="127.0.0.1"
DEFAULT_DB_PORT="3306"
DEFAULT_DB_NAME="linode_panel"
DEFAULT_DB_USER="linode_panel"

need_root() {
  if [ "$(id -u)" -ne 0 ]; then
    echo "请使用 root 运行：sudo sh install.sh"
    exit 1
  fi
}

have() {
  command -v "$1" >/dev/null 2>&1
}

ask() {
  prompt="$1"
  default="$2"
  printf "%s [%s]: " "$prompt" "$default" >&2
  read -r value || true
  if [ -z "$value" ]; then
    value="$default"
  fi
  printf "%s" "$value"
}

ask_secret() {
  prompt="$1"
  default="$2"
  printf "%s" "$prompt" >&2
  if [ -n "$default" ]; then
    printf " [保持原值]" >&2
  fi
  printf ": " >&2
  stty -echo 2>/dev/null || true
  read -r value || true
  stty echo 2>/dev/null || true
  printf "\n" >&2
  if [ -z "$value" ]; then
    value="$default"
  fi
  printf "%s" "$value"
}

validate_identifier() {
  name="$1"
  value="$2"
  case "$value" in
    *[!A-Za-z0-9_]*|"")
      echo "$name 只能包含英文字母、数字和下划线：$value"
      exit 1
      ;;
  esac
}

quote_env() {
  printf "%s" "$1" | sed "s/'/'\\\\''/g; 1s/^/'/; \$s/\$/'/"
}

quote_sql() {
  printf "%s" "$1" | sed "s/'/''/g; 1s/^/'/; \$s/\$/'/"
}

ask_yes_no() {
  prompt="$1"
  default="$2"
  printf "%s [%s]: " "$prompt" "$default"
  read -r value || true
  if [ -z "$value" ]; then
    value="$default"
  fi
  case "$value" in
    y|Y|yes|YES|Yes|是) return 0 ;;
    *) return 1 ;;
  esac
}

random_password() {
  if have openssl; then
    openssl rand -base64 24 | tr -d '/+=' | cut -c 1-24
  else
    date +%s | sha256sum | cut -c 1-24
  fi
}

install_packages() {
  echo "步骤 1/7：检查并安装基础依赖。"
  if have apt-get; then
    apt-get update
    apt-get install -y git curl ca-certificates tar openssl
  elif have dnf; then
    dnf install -y git curl ca-certificates tar openssl
  elif have yum; then
    yum install -y git curl ca-certificates tar openssl
  elif have apk; then
    apk add --no-cache git curl ca-certificates tar openssl
  else
    echo "未识别包管理器，请手动确认 git、curl、tar、openssl 已安装。"
  fi
}

ensure_go() {
  echo "步骤 2/7：检查 Go 编译环境。"
  if have go; then
    return
  fi
  ARCH="$(uname -m)"
  case "$ARCH" in
    x86_64|amd64) GOARCH="amd64" ;;
    aarch64|arm64) GOARCH="arm64" ;;
    *) echo "不支持的架构：$ARCH"; exit 1 ;;
  esac
  GOVERSION="${GOVERSION:-1.24.0}"
  TMP="/tmp/go${GOVERSION}.linux-${GOARCH}.tar.gz"
  echo "未检测到 Go，正在下载 Go $GOVERSION。"
  curl -fsSL "https://go.dev/dl/go${GOVERSION}.linux-${GOARCH}.tar.gz" -o "$TMP"
  rm -rf /usr/local/go
  tar -C /usr/local -xzf "$TMP"
  export PATH="/usr/local/go/bin:$PATH"
}

collect_config() {
  echo "步骤 3/7：请输入面板和数据库配置。"
  REPO_URL="${REPO_URL:-$(ask "源码仓库地址" "$DEFAULT_REPO_URL")}"
  INSTALL_DIR="${INSTALL_DIR:-$(ask "安装目录" "$DEFAULT_INSTALL_DIR")}"
  ENV_DIR="${ENV_DIR:-$(ask "配置目录" "$DEFAULT_ENV_DIR")}"
  HOST="${LINODE_PANEL_HOST:-$(ask "面板监听地址，生产建议保持 127.0.0.1" "$DEFAULT_HOST")}"
  PORT="${LINODE_PANEL_PORT:-$(ask "面板监听端口" "$DEFAULT_PORT")}"

  DB_HOST="${LINODE_PANEL_DB_HOST:-$(ask "MySQL/MariaDB 主机" "$DEFAULT_DB_HOST")}"
  DB_PORT="${LINODE_PANEL_DB_PORT:-$(ask "MySQL/MariaDB 端口" "$DEFAULT_DB_PORT")}"
  DB_NAME="${LINODE_PANEL_DB_NAME:-$(ask "数据库名，可用 phpMyAdmin 管理" "$DEFAULT_DB_NAME")}"
  DB_USER="${LINODE_PANEL_DB_USER:-$(ask "数据库用户" "$DEFAULT_DB_USER")}"
  validate_identifier "数据库名" "$DB_NAME"
  validate_identifier "数据库用户" "$DB_USER"
  if [ -n "${LINODE_PANEL_DB_PASSWORD:-}" ]; then
    DB_PASSWORD="$LINODE_PANEL_DB_PASSWORD"
  else
    DB_PASSWORD="$(ask_secret "数据库密码，留空则自动生成" "")"
    if [ -z "$DB_PASSWORD" ]; then
      DB_PASSWORD="$(random_password)"
      echo "已自动生成数据库密码。"
    fi
  fi
}

maybe_create_database() {
  echo "步骤 4/7：准备 MySQL/MariaDB 数据库。"
  if ! have mysql; then
    echo "未检测到 mysql 命令，跳过自动建库。请确认数据库和用户已在 phpMyAdmin 或面板中创建。"
    return
  fi

  if ask_yes_no "是否尝试自动创建数据库和用户？需要 MySQL root 权限" "n"; then
    MYSQL_ROOT_USER="$(ask "MySQL 管理员账号" "root")"
    MYSQL_ROOT_PASSWORD="$(ask_secret "MySQL 管理员密码，留空表示无密码或使用 socket 登录" "")"
    if [ -n "$MYSQL_ROOT_PASSWORD" ]; then
      MYSQL_PWD="$MYSQL_ROOT_PASSWORD"
      export MYSQL_PWD
    else
      unset MYSQL_PWD 2>/dev/null || true
    fi
    SQL_DB_PASSWORD="$(quote_sql "$DB_PASSWORD")"
    mysql --protocol=TCP -h "$DB_HOST" -P "$DB_PORT" -u "$MYSQL_ROOT_USER" <<EOF_SQL
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY $SQL_DB_PASSWORD;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY $SQL_DB_PASSWORD;
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF_SQL
    echo "数据库和用户已创建或已存在。"
  else
    echo "已选择手动建库。请确认数据库 $DB_NAME 和用户 $DB_USER 已存在并有完整权限。"
  fi
}

sync_source() {
  echo "步骤 5/7：同步源码。"
  mkdir -p "$INSTALL_DIR"
  if [ -d "$INSTALL_DIR/.git" ]; then
    git -C "$INSTALL_DIR" fetch --all --tags
    git -C "$INSTALL_DIR" pull --ff-only
  elif [ -f "./go.mod" ] && [ -d "./cmd" ]; then
    SRC="$(pwd)"
    if [ "$SRC" != "$INSTALL_DIR" ]; then
      tar --exclude .git --exclude data --exclude mysql-data --exclude .gocache --exclude .gomodcache --exclude bin -cf - . | tar -C "$INSTALL_DIR" -xf -
    fi
  else
    git clone "$REPO_URL" "$INSTALL_DIR"
  fi
}

build_binary() {
  echo "步骤 6/7：编译面板程序。"
  cd "$INSTALL_DIR"
  export PATH="/usr/local/go/bin:$PATH"
  CGO_ENABLED=0 GOOS=linux go build -trimpath -ldflags="-s -w" -o "/usr/local/bin/$APP_NAME" ./cmd/linode-panel
}

install_service() {
  echo "步骤 7/7：写入配置并启动 systemd 服务。"
  id -u "$APP_NAME" >/dev/null 2>&1 || useradd --system --home-dir /nonexistent --shell /usr/sbin/nologin "$APP_NAME"
  mkdir -p "$ENV_DIR"
  chmod 750 "$ENV_DIR"

  cat > "$ENV_DIR/linode-panel.env" <<EOF_ENV
LINODE_PANEL_HOST=$(quote_env "$HOST")
LINODE_PANEL_PORT=$(quote_env "$PORT")
LINODE_PANEL_DB_HOST=$(quote_env "$DB_HOST")
LINODE_PANEL_DB_PORT=$(quote_env "$DB_PORT")
LINODE_PANEL_DB_NAME=$(quote_env "$DB_NAME")
LINODE_PANEL_DB_USER=$(quote_env "$DB_USER")
LINODE_PANEL_DB_PASSWORD=$(quote_env "$DB_PASSWORD")
EOF_ENV
  chmod 600 "$ENV_DIR/linode-panel.env"

  cp "$INSTALL_DIR/deploy/linode-panel.service" /etc/systemd/system/linode-panel.service
  systemctl daemon-reload
  systemctl enable linode-panel
  systemctl restart linode-panel
}

print_panel_notes() {
  IP="$(hostname -I 2>/dev/null | awk '{print $1}' || true)"
  [ -n "$IP" ] || IP="服务器IP"
  cat <<EOF_NOTE

Linode Panel 已安装完成。

面板本地地址：http://127.0.0.1:$PORT
如临时开放端口，可访问：http://$IP:$PORT

数据库信息：
  类型：MySQL/MariaDB
  地址：$DB_HOST:$DB_PORT
  数据库：$DB_NAME
  用户：$DB_USER
  管理：可使用 phpMyAdmin 打开该数据库，查看 panel_settings 表。

生产建议：
  在 aaPanel、AcePanel 或 1Panel 中创建反向代理到 http://127.0.0.1:$PORT，并启用 HTTPS。

常用命令：
  systemctl status linode-panel
  journalctl -u linode-panel -f
  sh $INSTALL_DIR/update.sh

EOF_NOTE
}

need_root
install_packages
ensure_go
collect_config
maybe_create_database
sync_source
build_binary
install_service
print_panel_notes
