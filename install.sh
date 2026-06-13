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

print_manual_db_sql() {
  sql_db_password="$(quote_sql "$DB_PASSWORD")"
  cat <<EOF_SQL
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY $sql_db_password;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY $sql_db_password;
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF_SQL
}

continue_after_manual_database() {
  echo
  echo "你可以在 phpMyAdmin 或数据库面板的 SQL 页面执行下面语句来手动建库："
  echo "-------------------- SQL 开始 --------------------"
  print_manual_db_sql
  echo "-------------------- SQL 结束 --------------------"
  echo
  echo "数据库连接信息会写入 $ENV_DIR/linode-panel.env："
  echo "  数据库：$DB_NAME"
  echo "  用户：$DB_USER"
  echo "  密码：$DB_PASSWORD"
  echo
  if ask_yes_no "如果你已经手动创建好数据库和用户，是否继续安装？" "n"; then
    echo "将跳过自动建库并继续安装。"
    return 0
  fi
  echo "已停止安装。请完成手动建库后重新运行脚本，并在自动创建数据库这一步选择 n。"
  exit 1
}

test_database_connection() {
  echo "正在验证面板数据库账号是否可连接。"
  if ! have mysql; then
    echo "未检测到 mysql 命令，无法在安装阶段验证数据库连接，将在服务启动时验证。"
    return 0
  fi

  MYSQL_PWD="$DB_PASSWORD"
  export MYSQL_PWD
  if mysql --protocol=TCP -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" -e "SELECT 1;" >/dev/null 2>&1; then
    unset MYSQL_PWD 2>/dev/null || true
    echo "数据库连接验证通过。"
    return 0
  fi
  unset MYSQL_PWD 2>/dev/null || true

  echo "数据库连接验证失败，安装已停止。"
  echo
  echo "请确认 MySQL/MariaDB 已启动，并且下面数据库、用户和密码已经真实创建："
  echo "  主机：$DB_HOST"
  echo "  端口：$DB_PORT"
  echo "  数据库：$DB_NAME"
  echo "  用户：$DB_USER"
  echo "  密码：$DB_PASSWORD"
  echo
  echo "可在 phpMyAdmin 或 aaPanel 数据库管理的 SQL 页面执行："
  echo "-------------------- SQL 开始 --------------------"
  print_manual_db_sql
  echo "-------------------- SQL 结束 --------------------"
  echo
  echo "如果 aaPanel 使用了非 3306 端口，请重新运行脚本并填写正确端口。"
  exit 1
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
    echo "提示：这里需要 MySQL/MariaDB 管理员账号，通常是 root，不是面板要使用的普通数据库用户。"
    MYSQL_ROOT_USER="$(ask "MySQL 管理员账号" "root")"
    MYSQL_ROOT_PASSWORD="$(ask_secret "MySQL 管理员密码，留空表示本机 socket 登录" "")"
    if [ -n "$MYSQL_ROOT_PASSWORD" ]; then
      MYSQL_PWD="$MYSQL_ROOT_PASSWORD"
      export MYSQL_PWD
    else
      unset MYSQL_PWD 2>/dev/null || true
    fi
    SQL_FILE="/tmp/linode-panel-init-db.$$.sql"
    print_manual_db_sql > "$SQL_FILE"
    if [ -z "$MYSQL_ROOT_PASSWORD" ] && { [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; }; then
      echo "检测到本机数据库且管理员密码留空，将尝试使用 MySQL/MariaDB socket 登录。"
      if ! mysql -u "$MYSQL_ROOT_USER" < "$SQL_FILE"; then
        rm -f "$SQL_FILE"
        echo "自动建库失败：无法使用 $MYSQL_ROOT_USER 通过 socket 登录 MySQL/MariaDB。"
        echo "处理方法：使用真实的 MySQL 管理员账号和密码，或先在 phpMyAdmin/服务器面板中手动创建数据库和用户，再重新运行脚本并选择不自动创建。"
        continue_after_manual_database
      fi
    else
      if ! mysql --protocol=TCP -h "$DB_HOST" -P "$DB_PORT" -u "$MYSQL_ROOT_USER" < "$SQL_FILE"; then
        rm -f "$SQL_FILE"
        echo "自动建库失败：MySQL/MariaDB 管理员账号或密码不正确，或该账号没有创建数据库/用户的权限。"
        echo "当前尝试登录账号：$MYSQL_ROOT_USER@$DB_HOST"
        echo "处理方法：使用 root/数据库管理员账号，或先在 phpMyAdmin/服务器面板中手动创建数据库和用户，再重新运行脚本并选择不自动创建。"
        continue_after_manual_database
      fi
    fi
    rm -f "$SQL_FILE"
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

check_service_health() {
  echo "正在检查面板服务健康状态。"
  sleep 2
  if curl -fsS "http://127.0.0.1:$PORT/api/health" >/dev/null 2>&1; then
    echo "面板服务已正常启动。"
    return 0
  fi

  echo "警告：面板服务未通过健康检查，反向代理访问可能显示 Bad Gateway。"
  echo
  echo "请在服务器上执行以下命令查看原因："
  echo "  systemctl status linode-panel --no-pager"
  echo "  journalctl -u linode-panel -n 80 --no-pager"
  echo "  curl -v http://127.0.0.1:$PORT/api/health"
  echo
  echo "常见原因："
  echo "  1. MySQL/MariaDB 数据库名、用户名或密码填写错误。"
  echo "  2. 数据库用户没有 panel_settings 表的创建或读写权限。"
  echo "  3. 反向代理目标不是 http://127.0.0.1:$PORT。"
  echo "  4. 端口 $PORT 被其他程序占用。"
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
test_database_connection
sync_source
build_binary
install_service
check_service_health
print_panel_notes
