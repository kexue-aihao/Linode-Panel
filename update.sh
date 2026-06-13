#!/usr/bin/env sh
set -eu

DEFAULT_WEB_DIR="/www/wwwroot/linode-panel"
DEFAULT_REPO_URL="https://github.com/kexue-aihao/Linode-Panel.git"

need_root() {
  if [ "$(id -u)" -ne 0 ]; then
    echo "请使用 root 运行：sudo sh update.sh"
    exit 1
  fi
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

need_root

WEB_DIR="${WEB_DIR:-$(ask "网站根目录" "$DEFAULT_WEB_DIR")}"
REPO_URL="${REPO_URL:-$(ask "源码仓库地址" "$DEFAULT_REPO_URL")}"
if [ ! -d "$WEB_DIR" ]; then
  echo "未找到网站目录：$WEB_DIR"
  exit 1
fi

echo "步骤 1/3：备份 config.php。"
if [ -f "$WEB_DIR/config.php" ]; then
  BACKUP_DIR="${BACKUP_DIR:-/root/linode-panel-backups}"
  mkdir -p "$BACKUP_DIR"
  chmod 700 "$BACKUP_DIR" 2>/dev/null || true
  cp "$WEB_DIR/config.php" "$BACKUP_DIR/config.php.bak.$(date +%Y%m%d%H%M%S)"
  echo "已备份配置到：$BACKUP_DIR"
fi

echo "步骤 2/3：同步源码。"
if [ -d "$WEB_DIR/.git" ]; then
  cd "$WEB_DIR"
  git fetch --all --tags
  git pull --ff-only
else
  TMP_DIR="/tmp/linode-panel-update.$$"
  git clone "$REPO_URL" "$TMP_DIR"
  tar --exclude .git --exclude config.php -C "$TMP_DIR" -cf - . | tar -C "$WEB_DIR" -xf -
  rm -rf "$TMP_DIR"
fi

echo "步骤 3/3：完成。"
echo "PHP 版无需重启 systemd。若 aaPanel 开启了 PHP Opcache，可在 aaPanel 中重载 PHP。"
