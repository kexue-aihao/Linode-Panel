#!/usr/bin/env sh
set -eu

DEFAULT_REPO_URL="https://github.com/kexue-aihao/Linode-Panel.git"
DEFAULT_WEB_DIR="/www/wwwroot/linode-panel"
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

quote_php() {
  printf "%s" "$1" | sed "s/\\\\/\\\\\\\\/g; s/'/\\\\'/g"
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

install_packages() {
  echo "步骤 1/5：检查基础命令。"
  if have apt-get; then
    apt-get update
    apt-get install -y git curl ca-certificates tar
  elif have dnf; then
    dnf install -y git curl ca-certificates tar
  elif have yum; then
    yum install -y git curl ca-certificates tar
  elif have apk; then
    apk add --no-cache git curl ca-certificates tar
  else
    echo "未识别包管理器，请手动确认 git、curl、tar 已安装。"
  fi
}

collect_config() {
  echo "步骤 2/5：请输入 aaPanel 网站目录和数据库配置。"
  REPO_URL="${REPO_URL:-$(ask "源码仓库地址" "$DEFAULT_REPO_URL")}"
  WEB_DIR="${WEB_DIR:-$(ask "网站根目录，建议填 aaPanel 创建的网站目录" "$DEFAULT_WEB_DIR")}"
  DB_HOST="${LINODE_PANEL_DB_HOST:-$(ask "MySQL/MariaDB 主机" "$DEFAULT_DB_HOST")}"
  DB_PORT="${LINODE_PANEL_DB_PORT:-$(ask "MySQL/MariaDB 端口" "$DEFAULT_DB_PORT")}"
  DB_NAME="${LINODE_PANEL_DB_NAME:-$(ask "数据库名，需先在 aaPanel 创建" "$DEFAULT_DB_NAME")}"
  DB_USER="${LINODE_PANEL_DB_USER:-$(ask "数据库用户" "$DEFAULT_DB_USER")}"
  validate_identifier "数据库名" "$DB_NAME"
  validate_identifier "数据库用户" "$DB_USER"
  DB_PASSWORD="${LINODE_PANEL_DB_PASSWORD:-$(ask_secret "数据库密码" "")}"
  if [ -z "$DB_PASSWORD" ]; then
    echo "数据库密码不能为空。请先在 aaPanel 数据库页面创建数据库和用户，然后重新运行脚本。"
    exit 1
  fi
}

sync_source() {
  echo "步骤 3/5：同步 PHP 面板源码。"
  mkdir -p "$WEB_DIR"
  if [ -d "$WEB_DIR/.git" ]; then
    git -C "$WEB_DIR" fetch --all --tags
    git -C "$WEB_DIR" pull --ff-only
  elif [ -f "./api.php" ] && [ -d "./app" ]; then
    SRC="$(pwd)"
    if [ "$SRC" != "$WEB_DIR" ]; then
      tar --exclude .git --exclude config.php --exclude data --exclude mysql-data --exclude .gocache --exclude .gomodcache --exclude bin -cf - . | tar -C "$WEB_DIR" -xf -
    fi
  else
    TMP_DIR="/tmp/linode-panel-src.$$"
    git clone "$REPO_URL" "$TMP_DIR"
    tar --exclude .git --exclude config.php -C "$TMP_DIR" -cf - . | tar -C "$WEB_DIR" -xf -
    rm -rf "$TMP_DIR"
  fi
}

write_config() {
  echo "步骤 4/5：生成 config.php。"
  cat > "$WEB_DIR/config.php" <<EOF_PHP
<?php

return [
    'db' => [
        'host' => '$(quote_php "$DB_HOST")',
        'port' => $(printf "%s" "$DB_PORT"),
        'name' => '$(quote_php "$DB_NAME")',
        'user' => '$(quote_php "$DB_USER")',
        'password' => '$(quote_php "$DB_PASSWORD")',
    ],
    'session_name' => 'linode_panel_session',
];
EOF_PHP
  chmod 640 "$WEB_DIR/config.php" 2>/dev/null || true
  if id www >/dev/null 2>&1; then
    chown -R www:www "$WEB_DIR" 2>/dev/null || true
  fi
}

test_database_connection() {
  echo "步骤 5/5：验证数据库连接。"
  if have mysql; then
    MYSQL_PWD="$DB_PASSWORD"
    export MYSQL_PWD
    if mysql --protocol=TCP -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" -e "SELECT 1;" >/dev/null 2>&1; then
      unset MYSQL_PWD 2>/dev/null || true
      echo "数据库连接验证通过。"
      return
    fi
    unset MYSQL_PWD 2>/dev/null || true
  fi

  echo "数据库连接未验证通过。请确认 aaPanel 中数据库、用户和密码正确。"
  echo "你也可以在 phpMyAdmin SQL 页面执行："
  echo "-------------------- SQL 开始 --------------------"
  print_manual_db_sql
  echo "-------------------- SQL 结束 --------------------"
  echo
  echo "如果 aaPanel 已创建数据库但本机没有 mysql 命令，可继续在浏览器访问面板；PHP 会在首次访问时建表。"
}

print_notes() {
  cat <<EOF_NOTE

Linode Panel PHP 版已部署到：
  $WEB_DIR

请在 aaPanel 中确认：
  1. 网站根目录指向 $WEB_DIR
  2. PHP 版本为 8.1 或更高
  3. PHP 扩展已启用：pdo_mysql、curl、json、session
  4. 防跨站/运行目录限制允许访问当前网站目录
  5. 访问你的域名，首次打开会进入面板初始化页面

健康检查地址：
  https://你的域名/api.php?action=health

升级命令：
  sh $WEB_DIR/update.sh

EOF_NOTE
}

need_root
install_packages
collect_config
sync_source
write_config
test_database_connection
print_notes
