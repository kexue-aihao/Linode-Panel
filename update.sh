#!/usr/bin/env sh
set -eu

APP_NAME="linode-panel"
DEFAULT_INSTALL_DIR="/opt/linode-panel"
DEFAULT_ENV_FILE="/etc/linode-panel/linode-panel.env"

need_root() {
  if [ "$(id -u)" -ne 0 ]; then
    echo "请使用 root 运行：sudo sh update.sh"
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

ensure_go() {
  echo "步骤 1/5：检查 Go 编译环境。"
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

load_env() {
  ENV_FILE="${ENV_FILE:-$(ask "配置文件路径" "$DEFAULT_ENV_FILE")}"
  if [ -f "$ENV_FILE" ]; then
    . "$ENV_FILE"
  fi
}

maybe_update_env() {
  echo "步骤 2/5：确认是否修改数据库连接配置。"
  if ! ask_yes_no "是否修改 MySQL/MariaDB 数据库连接？" "n"; then
    return
  fi
  DB_HOST="$(ask "MySQL/MariaDB 主机" "${LINODE_PANEL_DB_HOST:-127.0.0.1}")"
  DB_PORT="$(ask "MySQL/MariaDB 端口" "${LINODE_PANEL_DB_PORT:-3306}")"
  DB_NAME="$(ask "数据库名" "${LINODE_PANEL_DB_NAME:-linode_panel}")"
  DB_USER="$(ask "数据库用户" "${LINODE_PANEL_DB_USER:-linode_panel}")"
  validate_identifier "数据库名" "$DB_NAME"
  validate_identifier "数据库用户" "$DB_USER"
  DB_PASSWORD="$(ask_secret "数据库密码" "${LINODE_PANEL_DB_PASSWORD:-}")"
  PANEL_HOST="${LINODE_PANEL_HOST:-127.0.0.1}"
  PANEL_PORT="${LINODE_PANEL_PORT:-8088}"
  mkdir -p "$(dirname "$ENV_FILE")"
  cat > "$ENV_FILE" <<EOF_ENV
LINODE_PANEL_HOST=$(quote_env "$PANEL_HOST")
LINODE_PANEL_PORT=$(quote_env "$PANEL_PORT")
LINODE_PANEL_DB_HOST=$(quote_env "$DB_HOST")
LINODE_PANEL_DB_PORT=$(quote_env "$DB_PORT")
LINODE_PANEL_DB_NAME=$(quote_env "$DB_NAME")
LINODE_PANEL_DB_USER=$(quote_env "$DB_USER")
LINODE_PANEL_DB_PASSWORD=$(quote_env "$DB_PASSWORD")
EOF_ENV
  chmod 600 "$ENV_FILE"
  echo "数据库连接配置已更新。"
}

sync_source() {
  echo "步骤 3/5：同步源码。"
  INSTALL_DIR="${INSTALL_DIR:-$(ask "安装目录" "$DEFAULT_INSTALL_DIR")}"
  if [ ! -d "$INSTALL_DIR" ]; then
    echo "未找到安装目录：$INSTALL_DIR"
    exit 1
  fi
  cd "$INSTALL_DIR"
  if [ -d .git ]; then
    git fetch --all --tags
    git pull --ff-only
  else
    echo "当前安装目录不是 Git 仓库，将使用本地源码直接编译。"
  fi
}

build_binary() {
  echo "步骤 4/5：重新编译面板程序。"
  export PATH="/usr/local/go/bin:$PATH"
  CGO_ENABLED=0 GOOS=linux go build -trimpath -ldflags="-s -w" -o "/usr/local/bin/$APP_NAME" ./cmd/linode-panel
}

restart_service() {
  echo "步骤 5/5：重启服务。"
  systemctl daemon-reload
  systemctl restart linode-panel
  systemctl status linode-panel --no-pager
  echo "Linode Panel 已升级并重启。"
}

need_root
ensure_go
load_env
maybe_update_env
sync_source
build_binary
restart_service
