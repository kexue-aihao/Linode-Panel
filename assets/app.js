const state = {
  configured: false,
  authenticated: false,
  settings: {},
  view: "vms",
  catalog: null,
  proxies: [],
  accounts: [],
  dnsConfigs: [],
  dnsBindings: [],
  replenishPolicies: [],
  notifications: null,
  logs: [],
  admin: null,
};

const $ = (selector, root = document) => root.querySelector(selector);
const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
const apiPath = (action) => {
  const [name, query = ""] = String(action).split("?", 2);
  return `api.php?action=${encodeURIComponent(name)}${query ? `&${query}` : ""}`;
};

const titles = {
  vms: ["VM 管理", "查看 Linode 实例状态、IP 和生命周期操作。"],
  officialApi: ["官方 API", "通过默认 Linode 号池 Token 调用官方 API v4 全部接口。"],
  accounts: ["Linode 号池", "维护多个 Linode Token，可为账号绑定外部代理。"],
  proxies: ["代理配置", "管理 HTTP/SOCKS5 外部代理，支持代理 API 批量导入。"],
  dns: ["DNS 管理", "内部集成彩虹 DNS 面板接入、域名和解析记录管理。"],
  replenish: ["自动补机", "保留补机入口，组合号池、代理、DNS 和通知数据。"],
  notifications: ["通知设置", "配置 Telegram Bot Token、个人和群组 Chat ID。"],
  logs: ["执行日志", "查看账号、代理、DNS、通知和实例操作日志。"],
  security: ["账号安全", "修改管理员账号和密码。"],
  admin: ["管理员后台", "查看面板资源统计和运行环境。"],
};

const officialApiTemplates = [
  { group: "账号", name: "当前账号资料", method: "GET", path: "/v4/profile", listAll: false, payload: "" },
  { group: "账号", name: "账号信息", method: "GET", path: "/v4/account", listAll: false, payload: "" },
  { group: "账号", name: "账单列表", method: "GET", path: "/v4/account/invoices", listAll: true, payload: "" },
  { group: "账号", name: "事件列表", method: "GET", path: "/v4/account/events", listAll: true, payload: "" },
  { group: "计算", name: "实例列表", method: "GET", path: "/v4/linode/instances", listAll: true, payload: "" },
  { group: "计算", name: "创建实例示例", method: "POST", path: "/v4/linode/instances", listAll: false, payload: { label: "demo-linode", region: "us-east", type: "g6-nanode-1", image: "linode/ubuntu24.04", root_pass: "请改成强密码" } },
  { group: "计算", name: "实例套餐", method: "GET", path: "/v4/linode/types", listAll: true, payload: "" },
  { group: "计算", name: "公开镜像", method: "GET", path: "/v4/images?is_public=true", listAll: true, payload: "" },
  { group: "计算", name: "StackScripts", method: "GET", path: "/v4/linode/stackscripts?is_public=true", listAll: true, payload: "" },
  { group: "网络", name: "区域列表", method: "GET", path: "/v4/regions", listAll: true, payload: "" },
  { group: "网络", name: "IP 列表", method: "GET", path: "/v4/networking/ips", listAll: true, payload: "" },
  { group: "网络", name: "新增公网 IPv4", method: "POST", path: "/v4/networking/ips", listAll: false, payload: { type: "ipv4", public: true, linode_id: 123456 } },
  { group: "网络", name: "防火墙列表", method: "GET", path: "/v4/networking/firewalls", listAll: true, payload: "" },
  { group: "网络", name: "VPC 列表", method: "GET", path: "/v4/vpcs", listAll: true, payload: "" },
  { group: "网络", name: "NodeBalancer 列表", method: "GET", path: "/v4/nodebalancers", listAll: true, payload: "" },
  { group: "存储", name: "卷列表", method: "GET", path: "/v4/volumes", listAll: true, payload: "" },
  { group: "存储", name: "创建卷示例", method: "POST", path: "/v4/volumes", listAll: false, payload: { label: "demo-volume", region: "us-east", size: 20 } },
  { group: "对象存储", name: "对象存储集群", method: "GET", path: "/v4/object-storage/clusters", listAll: true, payload: "" },
  { group: "对象存储", name: "Bucket 列表", method: "GET", path: "/v4/object-storage/buckets", listAll: true, payload: "" },
  { group: "对象存储", name: "Access Key 列表", method: "GET", path: "/v4/object-storage/keys", listAll: true, payload: "" },
  { group: "LKE", name: "Kubernetes 集群", method: "GET", path: "/v4/lke/clusters", listAll: true, payload: "" },
  { group: "数据库", name: "MySQL 数据库", method: "GET", path: "/v4/databases/mysql/instances", listAll: true, payload: "" },
  { group: "数据库", name: "PostgreSQL 数据库", method: "GET", path: "/v4/databases/postgresql/instances", listAll: true, payload: "" },
  { group: "域名", name: "Linode DNS 域名", method: "GET", path: "/v4/domains", listAll: true, payload: "" },
  { group: "支持", name: "工单列表", method: "GET", path: "/v4/support/tickets", listAll: true, payload: "" },
  { group: "市场", name: "Marketplace 应用", method: "GET", path: "/v4/linode/stackscripts?is_public=true&mine=false", listAll: true, payload: "" },
  { group: "Beta", name: "Beta 计划", method: "GET", path: "/v4/betas", listAll: true, payload: "" },
];

async function api(action, options = {}) {
  const res = await fetch(apiPath(action), {
    credentials: "same-origin",
    headers: { "Content-Type": "application/json", ...(options.headers || {}) },
    ...options,
  });
  const text = await res.text();
  const data = text ? JSON.parse(text) : {};
  if (!res.ok) {
    const message = data?.errors?.map((e) => e.reason || e).join("; ") || `请求失败：${res.status}`;
    throw new Error(message);
  }
  return data;
}

function showNotice(message, type = "") {
  const node = $("#notice");
  node.textContent = message;
  node.className = `notice ${type}`.trim();
  node.classList.remove("hidden");
  clearTimeout(showNotice.timer);
  showNotice.timer = setTimeout(() => node.classList.add("hidden"), 5200);
}

async function init() {
  bindNavigation();
  bindForms();
  await loadSession();
}

async function loadSession() {
  try {
    const session = await api("session");
    state.configured = session.configured;
    state.authenticated = session.authenticated;
    state.settings = session.settings || {};
    renderShell();
    if (state.authenticated) await refreshCurrent();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function renderShell() {
  const locked = !state.configured || !state.authenticated;
  $("#app")?.classList.toggle("auth-mode", locked);
  $("#authPanel").classList.toggle("hidden", !locked);
  $$(".view").forEach((view) => view.classList.toggle("hidden", locked));
  $(".topbar").classList.toggle("hidden", locked);
  $(".sidebar").classList.toggle("hidden", locked);
  if (locked) {
    $("#authTitle").textContent = state.configured ? "登录面板" : "初始化面板";
    $("#authHint").textContent = state.configured
      ? "输入管理员账号继续管理 Linode。"
      : "先创建本面板的管理员账号，进入面板后再到 Linode 号池添加 Token。";
    $("#authUser").value = state.settings.admin_user || "admin";
    return;
  }
  $("#tokenState").textContent = state.settings.has_linode_token || state.accounts.length ? "Token 已保存" : "未连接";
  $("#tokenState").classList.toggle("muted", !(state.settings.has_linode_token || state.accounts.length));
  setView(state.view, { silent: true });
}

function bindNavigation() {
  $$(".nav-item").forEach((button) => button.addEventListener("click", () => setView(button.dataset.view)));
  $$("[data-jump]").forEach((button) => button.addEventListener("click", () => setView(button.dataset.jump)));
  $$("[data-open-create]").forEach((button) => button.addEventListener("click", () => openCreatePanel()));
  $$("[data-close-create]").forEach((button) => button.addEventListener("click", () => $("#createForm").classList.add("hidden")));
  $("#refreshBtn").addEventListener("click", () => refreshCurrent(true));
  $("#logoutBtn").addEventListener("click", logout);
  $("#loadCatalogBtn").addEventListener("click", () => loadCatalog(true));
  $("#checkPoolBtn").addEventListener("click", loadAccounts);
  $("#checkAllProxiesBtn").addEventListener("click", checkAllProxies);
  $("#loadDnsDomainsBtn").addEventListener("click", loadDnsDomains);
  $("#testNotifyBtn").addEventListener("click", testNotification);
  $("#exportLogsBtn").addEventListener("click", exportLogs);
  $("#clearOfficialApiBodyBtn").addEventListener("click", () => {
    $("#officialApiForm").elements.payload.value = "";
    $("#officialApiResult").textContent = "请求体已清空。";
  });
  $("#closeAccountModalBtn").addEventListener("click", closeAccountModal);
  $("#accountModalOkBtn").addEventListener("click", closeAccountModal);
  $("#accountModal").addEventListener("click", (event) => {
    if (event.target.id === "accountModal") closeAccountModal();
  });
}

function bindForms() {
  $("#authForm").addEventListener("submit", async (event) => {
    event.preventDefault();
    const payload = { admin_user: $("#authUser").value.trim() || "admin", password: $("#authPassword").value };
    const endpoint = state.configured ? "login" : "setup";
    const firstSetup = !state.configured;
    try {
      const settings = await api(endpoint, { method: "POST", body: JSON.stringify(payload) });
      state.configured = true;
      state.authenticated = true;
      state.settings = settings;
      $("#authPassword").value = "";
      state.view = firstSetup ? "accounts" : state.view;
      renderShell();
      showNotice(firstSetup ? "管理员账号已创建，请添加 Linode 号池 Token" : "已进入面板", "success");
      await refreshCurrent();
    } catch (err) {
      showNotice(err.message, "error");
    }
  });

  $("#createForm").addEventListener("submit", submitCreateInstance);
  $("#officialApiForm").addEventListener("submit", submitOfficialApi);
  $("#accountForm").addEventListener("submit", submitAccount);
  $("#proxyForm").addEventListener("submit", submitProxy);
  $("#dnsConfigForm").addEventListener("submit", submitDnsConfig);
  $("#dnsRecordForm").addEventListener("submit", submitDnsRecord);
  $("#dnsBindingForm").addEventListener("submit", submitDnsBinding);
  $("#replenishForm").addEventListener("submit", submitReplenish);
  $("#notificationForm").addEventListener("submit", submitNotification);
  $("#securityForm").addEventListener("submit", submitSecurity);
  $("#promoCodeForm").addEventListener("submit", submitPromoCode);
}

function setView(view, options = {}) {
  state.view = view;
  $$(".nav-item").forEach((button) => button.classList.toggle("active", button.dataset.view === view));
  $$(".view").forEach((node) => node.classList.toggle("active", node.id === `view-${view}`));
  const [title, subtitle] = titles[view] || titles.vms;
  $("#viewTitle").textContent = title;
  $("#viewSubtitle").textContent = subtitle;
  if (!options.silent) refreshCurrent();
}

async function refreshCurrent(force = false) {
  if (state.view === "vms") return loadInstances();
  if (state.view === "officialApi") return renderOfficialApiTemplates();
  if (state.view === "accounts") return Promise.all([loadProxies(), loadAccounts()]);
  if (state.view === "proxies") return loadProxies(force);
  if (state.view === "dns") return loadDns();
  if (state.view === "replenish") return loadReplenish();
  if (state.view === "notifications") return loadNotifications();
  if (state.view === "logs") return loadLogs();
  if (state.view === "security") return loadSecurity();
  if (state.view === "admin") return loadAdmin();
}

async function loadInstances() {
  try {
    $("#instanceGrid").innerHTML = `<div class="panel">加载实例中...</div>`;
    const data = await api("linode/instances");
    const instances = data.data || [];
    if (!instances.length) {
      $("#instanceGrid").innerHTML = `<div class="panel">还没有实例。你可以点击右上角创建实例。</div>`;
      return;
    }
    $("#instanceGrid").innerHTML = instances.map(instanceCard).join("");
    $$(".instance-card [data-action]").forEach((button) => button.addEventListener("click", () => instanceAction(button.dataset.id, button.dataset.action, button.dataset.label)));
  } catch (err) {
    $("#instanceGrid").innerHTML = `<div class="panel">${escapeHTML(err.message)}</div>`;
  }
}

function renderOfficialApiTemplates() {
  const node = $("#officialApiTemplates");
  if (!node) return;
  const groups = officialApiTemplates.reduce((acc, item, index) => {
    (acc[item.group] ||= []).push({ ...item, index });
    return acc;
  }, {});
  node.innerHTML = Object.entries(groups).map(([group, items]) => `
    <div class="api-template-group">
      <h3>${escapeHTML(group)}</h3>
      <div class="api-template-list">
        ${items.map((item) => `
          <button class="secondary small api-template" type="button" data-api-template="${item.index}">
            <span>${escapeHTML(item.method)}</span>${escapeHTML(item.name)}
          </button>`).join("")}
      </div>
    </div>`).join("");
  $$("[data-api-template]").forEach((button) => button.addEventListener("click", () => applyOfficialApiTemplate(Number(button.dataset.apiTemplate))));
}

function applyOfficialApiTemplate(index) {
  const template = officialApiTemplates[index];
  if (!template) return;
  const form = $("#officialApiForm");
  form.elements.method.value = template.method;
  form.elements.path.value = template.path;
  form.elements.list_all.checked = !!template.listAll;
  form.elements.payload.value = template.payload && typeof template.payload === "object" ? JSON.stringify(template.payload, null, 2) : "";
  $("#officialApiResult").textContent = `已载入模板：${template.group} / ${template.name}`;
}

async function submitOfficialApi(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const method = form.elements.method.value.toUpperCase();
  const path = form.elements.path.value.trim();
  const bodyText = form.elements.payload.value.trim();
  let payload = null;
  if (bodyText) {
    try {
      payload = JSON.parse(bodyText);
      if (!payload || Array.isArray(payload) || typeof payload !== "object") {
        throw new Error("请求体必须是 JSON 对象");
      }
    } catch (err) {
      showNotice(`JSON 请求体不正确：${err.message}`, "error");
      return;
    }
  }
  if (!path.startsWith("/v4/")) {
    showNotice("官方 API 路径必须以 /v4/ 开头", "error");
    return;
  }
  if (method !== "GET" && !window.confirm(`确认执行 ${method} ${path}？该操作可能会创建、修改、删除资源或产生费用。`)) return;
  $("#officialApiResult").textContent = "正在执行官方 API...";
  try {
    const result = await api("linode/api/official", {
      method: "POST",
      body: JSON.stringify({
        method,
        path,
        payload,
        list_all: form.elements.list_all.checked,
      }),
    });
    $("#officialApiResult").textContent = JSON.stringify(result.result, null, 2);
    showNotice(`官方 API 已执行：${method} ${path}`, "success");
  } catch (err) {
    $("#officialApiResult").textContent = err.message;
    showNotice(err.message, "error");
  }
}

function openCreatePanel() {
  setView("vms", { silent: true });
  $("#createForm").classList.remove("hidden");
  if (!state.catalog) loadCatalog();
}

async function loadCatalog(force = false) {
  if (state.catalog && !force) return populateCatalog();
  try {
    state.catalog = await api("linode/catalog");
    populateCatalog();
    showNotice("创建选项已载入", "success");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function populateCatalog() {
  if (!state.catalog) return;
  fillSelect($("#createForm").region, state.catalog.regions?.data || [], (item) => item.id, (item) => `${item.label || item.id} (${item.id})`);
  fillSelect($("#createForm").type, state.catalog.types?.data || [], (item) => item.id, (item) => `${item.label || item.id} ${item.price?.monthly ? `$${item.price.monthly}/月` : ""}`);
  fillSelect($("#createForm").image, state.catalog.images?.data || [], (item) => item.id, (item) => item.label || item.id);
  fillSelect($("#createForm").firewall_id, state.catalog.firewalls?.data || [], (item) => item.id, (item) => `${item.label || item.id}`, true);
}

async function submitCreateInstance(event) {
  event.preventDefault();
  if (!window.confirm("确认创建 Linode 实例？该操作会产生费用。")) return;
  const form = event.currentTarget;
  const rootPass = form.root_pass.value;
  const keys = form.authorized_keys.value.split(/\n+/).map((item) => item.trim()).filter(Boolean);
  if (!rootPass && keys.length === 0) {
    showNotice("请填写 Root 密码或至少一个 SSH 公钥", "error");
    return;
  }
  const payload = {
    label: form.label.value.trim(),
    region: form.region.value,
    type: form.type.value,
    image: form.image.value,
    backups_enabled: form.backups_enabled.checked,
    private_ip: form.private_ip.checked,
  };
  if (rootPass) payload.root_pass = rootPass;
  if (keys.length) payload.authorized_keys = keys;
  if (form.firewall_id.value) payload.firewall_id = Number(form.firewall_id.value);
  const tags = form.tags.value.split(",").map((item) => item.trim()).filter(Boolean);
  if (tags.length) payload.tags = tags;
  try {
    const instance = await api("linode/instances", { method: "POST", body: JSON.stringify(payload) });
    showNotice(`实例 ${instance.label || payload.label} 已提交创建`, "success");
    form.root_pass.value = "";
    $("#createForm").classList.add("hidden");
    await loadInstances();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function instanceCard(item) {
  const statusClass = item.status === "running" ? "" : "offline";
  const ipv4 = (item.ipv4 || []).join(", ") || "-";
  const ipv6 = item.ipv6 || "-";
  const uptime = item.status === "running" ? (item.uptime_display || formatUptimeHours(item.uptime_hours)) : "-";
  return `
    <article class="instance-card">
      <div class="card-top">
        <div><h3>${escapeHTML(item.label || `linode-${item.id}`)}</h3><p class="muted">#${item.id}</p></div>
        <span class="badge ${statusClass}">${escapeHTML(item.status || "unknown")}</span>
      </div>
      <div class="meta-grid">
        <div class="meta"><span>区域</span><strong>${escapeHTML(item.region || "-")}</strong></div>
        <div class="meta"><span>套餐</span><strong>${escapeHTML(item.type || "-")}</strong></div>
        <div class="meta"><span>IPv4</span><strong>${escapeHTML(ipv4)}</strong></div>
        <div class="meta"><span>IPv6</span><strong>${escapeHTML(ipv6)}</strong></div>
        <div class="meta"><span>开机时长</span><strong>${escapeHTML(uptime)}</strong></div>
        <div class="meta span-two"><span>DDoS 防护</span><strong>官方暂无 VM 实例级 API 开关</strong></div>
      </div>
      <div class="card-actions">
        <button class="secondary small" data-action="boot" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">开机</button>
        <button class="secondary small" data-action="reboot" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">重启</button>
        <button class="secondary small" data-action="shutdown" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">关机</button>
        <button class="secondary small" data-action="allocate-ip" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">换 IP</button>
        <button class="secondary small" data-action="ddos-protection" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">DDoS 说明</button>
        <button class="danger small" data-action="delete" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">删除</button>
      </div>
    </article>`;
}

async function instanceAction(id, action, label) {
  const names = { boot: "开机", reboot: "重启", shutdown: "关机", delete: "删除", "allocate-ip": "换 IP", "ddos-protection": "查看 DDoS 说明" };
  if (action === "ddos-protection") {
    try {
      const status = await api(`linode/instances/${id}/ddos-protection`);
      showNotice(status.message || "Linode 官方暂无实例级 DDoS 防护 API 开关。");
    } catch (err) {
      showNotice(err.message, "error");
    }
    return;
  }
  const extra = action === "delete"
    ? " 删除后不可恢复。"
    : action === "allocate-ip"
      ? " 将调用 Linode 官方 API 新增一个公网 IPv4；旧 IP 不会自动删除，请确认 DNS 和业务切换后再处理旧 IP。"
      : "";
  if (!window.confirm(`确认${names[action] || action}实例 ${label || id}？${extra}`)) return;
  try {
    if (action === "delete") await api(`linode/instances/${id}`, { method: "DELETE" });
    else if (action === "allocate-ip") {
      const result = await api(`linode/instances/${id}/allocate-ip`, { method: "POST", body: "{}" });
      showNotice(`已分配新公网 IPv4：${result.address || "请刷新查看"}`, "success");
    } else {
      await api(`linode/instances/${id}/${action}`, { method: "POST", body: "{}" });
      showNotice(`已提交${names[action] || action}操作`, "success");
    }
    await loadInstances();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadAccounts() {
  try {
    const data = await api("linode/accounts");
    state.accounts = data.data || [];
    renderAccountList();
    $("#tokenState").textContent = state.settings.has_linode_token || state.accounts.length ? "Token 已保存" : "未连接";
    $("#tokenState").classList.toggle("muted", !(state.settings.has_linode_token || state.accounts.length));
  } catch (err) {
    $("#accountList").innerHTML = `<div class="row single">${escapeHTML(err.message)}</div>`;
  }
}

async function submitAccount(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = {
    label: form.label.value.trim(),
    token: form.token.value.trim(),
    proxy_profile_id: form.proxy_profile_id.value ? Number(form.proxy_profile_id.value) : 0,
    remark: form.remark.value.trim(),
    is_default: form.is_default.checked,
  };
  try {
    await api("linode/accounts", { method: "POST", body: JSON.stringify(payload) });
    form.token.value = "";
    showNotice("Linode 号池账号已添加", "success");
    await loadAccounts();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function renderAccountList() {
  $("#accountList").innerHTML = state.accounts.length
    ? state.accounts.map((item) => `
      <div class="row account-row">
        <div><strong>${escapeHTML(item.label)}</strong><span>${escapeHTML(item.email || item.username || item.token_masked || "-")}</span></div>
        <div>${item.is_default ? '<span class="badge">默认</span>' : '<span class="badge offline">备用</span>'}</div>
        <div>${escapeHTML(item.proxy_name || "不使用代理")}</div>
        <div>${escapeHTML(item.status)}${item.last_error ? `<span>${escapeHTML(item.last_error)}</span>` : ""}</div>
        <div class="row-actions">
          <button class="secondary small" data-account-info="${item.id}">账户信息</button>
          <button class="secondary small" data-account-promo="${item.id}">优惠码</button>
          <button class="secondary small" data-account-check="${item.id}">检测</button>
          <button class="secondary small" data-account-default="${item.id}">设默认</button>
          <button class="danger small" data-account-delete="${item.id}">删除</button>
        </div>
      </div>`).join("")
    : `<div class="row single">还没有 Linode 号池账号。</div>`;
  $$("[data-account-info]").forEach((button) => button.addEventListener("click", () => showAccountInfo(button.dataset.accountInfo)));
  $$("[data-account-promo]").forEach((button) => button.addEventListener("click", () => openPromoCode(button.dataset.accountPromo)));
  $$("[data-account-check]").forEach((button) => button.addEventListener("click", () => checkAccount(button.dataset.accountCheck)));
  $$("[data-account-default]").forEach((button) => button.addEventListener("click", () => setDefaultAccount(button.dataset.accountDefault)));
  $$("[data-account-delete]").forEach((button) => button.addEventListener("click", () => deleteAccount(button.dataset.accountDelete)));
}

async function showAccountInfo(id) {
  openAccountModal("账户信息", "正在读取账户信息...");
  $("#promoCodeForm").classList.add("hidden");
  try {
    const data = await api(`linode/accounts/${id}/info`);
    renderAccountInfo(data);
  } catch (err) {
    $("#accountInfoBody").innerHTML = `<p class="modal-error">${escapeHTML(err.message)}</p>`;
  }
}

function openPromoCode(id) {
  const account = state.accounts.find((item) => String(item.id) === String(id));
  openAccountModal("应用优惠码", account ? `为 ${escapeHTML(account.label)} 应用优惠码。` : "请输入优惠码。");
  const form = $("#promoCodeForm");
  form.classList.remove("hidden");
  form.elements.account_id.value = id;
  form.elements.promo_code.value = "";
  form.elements.promo_code.focus();
}

async function submitPromoCode(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const id = form.elements.account_id.value;
  const code = form.elements.promo_code.value.trim();
  if (!code) return showNotice("请填写优惠码", "error");
  if (!window.confirm(`确认应用优惠码 ${code}？`)) return;
  $("#accountInfoBody").textContent = "正在应用优惠码...";
  try {
    const data = await api(`linode/accounts/${id}/promo-code`, { method: "POST", body: JSON.stringify({ promo_code: code }) });
    renderAccountInfo(data.info);
    form.elements.promo_code.value = "";
    showNotice("优惠码已提交应用", "success");
  } catch (err) {
    $("#accountInfoBody").innerHTML = `<p class="modal-error">${escapeHTML(err.message)}</p>`;
    showNotice(err.message, "error");
  }
}

function openAccountModal(title, content = "") {
  $("#accountModalTitle").textContent = title;
  $("#accountInfoBody").innerHTML = `<p>${escapeHTML(content)}</p>`;
  $("#accountModal").classList.remove("hidden");
}

function closeAccountModal() {
  $("#accountModal").classList.add("hidden");
  $("#promoCodeForm").classList.add("hidden");
}

function renderAccountInfo(data) {
  const summary = data.summary || {};
  const rows = [
    ["邮箱", summary.email || data.profile?.email || "-"],
    ["注册时间", formatFullDate(summary.created)],
    ["未出账", formatMoney(summary.uninvoiced)],
    ["未付账单", formatMoney(summary.balance)],
    ["优惠内容", summary.promotion_code || "-"],
    ["优惠过期时间", formatFullDate(summary.promotion_expires)],
    ["优惠剩余金额", formatMoney(summary.promotion_remaining)],
  ];
  $("#accountInfoBody").innerHTML = rows.map(([label, value]) => `
    <div class="account-info-row"><span>${escapeHTML(label)}:</span><strong>${escapeHTML(value)}</strong></div>
  `).join("");
}

async function checkAccount(id) {
  try {
    const result = await api(`linode/accounts/${id}/check`, { method: "POST", body: "{}" });
    showNotice(result.ok ? "账号可用" : `账号检测失败：${result.error}`, result.ok ? "success" : "error");
    await loadAccounts();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function setDefaultAccount(id) {
  try {
    await api(`linode/accounts/${id}/default`, { method: "POST", body: "{}" });
    showNotice("默认账号已切换", "success");
    await loadAccounts();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function deleteAccount(id) {
  if (!window.confirm("确认删除这个 Linode 号池账号吗？")) return;
  try {
    await api(`linode/accounts/${id}`, { method: "DELETE" });
    showNotice("账号已删除", "success");
    await loadAccounts();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadProxies(force = false) {
  if (state.proxies.length && !force && state.view !== "proxies" && state.view !== "accounts") {
    return;
  }
  try {
    const data = await api("proxies");
    state.proxies = data.data || [];
    renderProxyList();
    fillProxySelects();
  } catch (err) {
    $("#proxyList").innerHTML = `<div class="row single">${escapeHTML(err.message)}</div>`;
  }
}

async function submitProxy(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  payload.validate = form.validate.checked;
  payload.proxy_api_limit = Number(payload.proxy_api_limit || 10);
  payload.proxy_api_url = normalizeProxyApiUrl(payload.proxy_api_url || "");
  try {
    const result = payload.proxy_api_url ? await importProxyApiWithProgress(payload) : await api("proxies", { method: "POST", body: JSON.stringify(payload) });
    showNotice(result.mode === "api" ? `代理 API 导入完成：成功 ${result.imported} 个，失败 ${result.failed} 个` : "代理已添加", "success");
    form.password.value = "";
    await loadProxies(true);
  } catch (err) {
    finishProxyProgress("failed", err.message);
    showNotice(err.message, "error");
  }
}

function normalizeProxyApiUrl(value) {
  const raw = String(value || "").trim();
  if (!raw) return "";
  return raw.replace(/\s+/g, "");
}

async function importProxyApiWithProgress(payload) {
  setProxyProgress({ visible: true, title: "正在拉取代理 API", done: 0, total: 0, text: "正在请求代理接口并解析返回内容。", results: [] });
  const parsed = await api("proxies/api/parse", { method: "POST", body: JSON.stringify(payload) });
  const candidates = (parsed.proxies || [])
    .map((item, index) => item.proxy ? item : { name: `API代理-${index + 1}`, proxy: item })
    .filter((item) => item.proxy?.host && item.proxy?.port)
    .slice(0, payload.proxy_api_limit || 10);
  const errors = [...(parsed.errors || [])];
  if (!candidates.length) {
    const message = `代理 API 未解析到可导入代理。已识别 ${parsed.total_candidates || 0} 条。`;
    finishProxyProgress("failed", message);
    throw new Error(errors.slice(0, 3).join("；") || message);
  }

  let imported = 0;
  const progressRows = [];
  setProxyProgress({ visible: true, title: "正在逐条测活并导入", done: 0, total: candidates.length, text: "开始验证代理端口连通性。", results: progressRows });

  for (let index = 0; index < candidates.length; index++) {
    const item = candidates[index];
    const proxy = item.proxy;
    const namePrefix = String(payload.name || "").trim();
    const savePayload = {
      name: namePrefix ? `${namePrefix}-${index + 1}` : item.name || `API代理-${index + 1}`,
      type: proxy.type,
      host: proxy.host,
      port: proxy.port,
      username: proxy.username || "",
      password: proxy.password || "",
      validate: true,
      source: "api",
    };

    setProxyProgress({ visible: true, title: "正在测活代理", done: index, total: candidates.length, text: `${savePayload.host}:${savePayload.port}`, results: progressRows });
    try {
      const saved = await api("proxies", { method: "POST", body: JSON.stringify(savePayload) });
      imported++;
      progressRows.unshift({ status: "success", message: `${saved.name} 可用，已保存` });
    } catch (err) {
      const message = `${savePayload.host}:${savePayload.port} 不可用：${err.message}`;
      errors.push(message);
      progressRows.unshift({ status: "failed", message });
    }
    setProxyProgress({ visible: true, title: "正在逐条测活并导入", done: index + 1, total: candidates.length, text: `已处理 ${index + 1}/${candidates.length}，成功 ${imported} 个，失败 ${errors.length} 个`, results: progressRows });
  }

  finishProxyProgress(imported > 0 ? "success" : "failed", `导入完成：成功 ${imported} 个，失败 ${errors.length} 个`);
  return {
    mode: "api",
    raw_type: parsed.raw_type,
    total_candidates: parsed.total_candidates,
    imported,
    failed: errors.length,
    errors: errors.slice(0, 20),
  };
}

function setProxyProgress({ visible, title, done, total, text, results }) {
  const panel = $("#proxyImportProgress");
  if (!panel) return;
  panel.classList.toggle("hidden", !visible);
  panel.classList.remove("success", "failed");
  $("#proxyProgressTitle").textContent = title || "正在处理代理";
  $("#proxyProgressCount").textContent = total ? `${done}/${total}` : "准备中";
  $("#proxyProgressText").textContent = text || "";
  const percent = total ? Math.max(4, Math.round((done / total) * 100)) : 12;
  $("#proxyProgressBar").style.width = `${Math.min(100, percent)}%`;
  if (Array.isArray(results)) {
    $("#proxyProgressResults").innerHTML = results.slice(0, 8).map((item) => `<div class="progress-result ${item.status}">${escapeHTML(item.message)}</div>`).join("");
  }
}

function finishProxyProgress(status, message) {
  const panel = $("#proxyImportProgress");
  if (!panel || panel.classList.contains("hidden")) return;
  panel.classList.remove("success", "failed");
  panel.classList.add(status);
  $("#proxyProgressTitle").textContent = status === "success" ? "代理导入完成" : "代理导入失败";
  $("#proxyProgressText").textContent = message || "";
  if (status === "success") $("#proxyProgressBar").style.width = "100%";
}

function renderProxyList() {
  const node = $("#proxyList");
  if (!node) return;
  node.innerHTML = state.proxies.length
    ? state.proxies.map((proxy) => `
      <div class="row proxy-row">
        <div><strong>${escapeHTML(proxy.name)}</strong><span>${escapeHTML(proxy.label)}</span></div>
        <div>${proxy.type.toUpperCase()}</div>
        <div>${escapeHTML(proxy.source || "fixed")}</div>
        <div>${escapeHTML(proxy.status || "unknown")}${proxy.last_error ? `<span>${escapeHTML(proxy.last_error)}</span>` : ""}</div>
        <div class="row-actions">
          <button class="secondary small" data-proxy-check="${proxy.id}">测活</button>
          <button class="danger small" data-proxy-delete="${proxy.id}">删除</button>
        </div>
      </div>`).join("")
    : `<div class="row single">还没有代理配置。可手动添加或填写代理 API 链接批量导入。</div>`;
  $$("[data-proxy-check]").forEach((button) => button.addEventListener("click", () => checkProxy(button.dataset.proxyCheck)));
  $$("[data-proxy-delete]").forEach((button) => button.addEventListener("click", () => deleteProxy(button.dataset.proxyDelete)));
}

async function checkProxy(id, silent = false) {
  const result = await api(`proxies/${id}/check`, { method: "POST", body: JSON.stringify({ delete_on_fail: false }) });
  if (!silent) showNotice(result.message, result.status === "available" ? "success" : "error");
  await loadProxies(true);
  return result;
}

async function checkAllProxies() {
  if (!state.proxies.length) return showNotice("没有可测活的代理", "error");
  let available = 0;
  let failed = 0;
  for (const proxy of state.proxies) {
    try {
      const result = await checkProxy(proxy.id, true);
      if (result.status === "available") available++;
      else failed++;
    } catch {
      failed++;
    }
  }
  showNotice(`代理测活完成：可用 ${available} 个，失败 ${failed} 个`, failed ? "error" : "success");
}

async function deleteProxy(id) {
  if (!window.confirm("确认删除这个代理配置吗？")) return;
  try {
    await api(`proxies/${id}`, { method: "DELETE" });
    showNotice("代理已删除", "success");
    await loadProxies(true);
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function fillProxySelects() {
  $$('select[name="proxy_profile_id"]').forEach((select) => {
    const current = select.value;
    select.innerHTML = `<option value="">不使用代理</option>` + state.proxies.map((proxy) => `<option value="${proxy.id}">${escapeHTML(proxy.name)} - ${escapeHTML(proxy.label)}</option>`).join("");
    select.value = current;
  });
}

async function loadDns() {
  try {
    const [data, bindings] = await Promise.all([api("dns/configs"), api("dns/bindings")]);
    state.dnsConfigs = data.data || [];
    state.dnsBindings = bindings.data || [];
    renderDnsConfigs();
    renderDnsBindings();
    fillDnsConfigSelects();
  } catch (err) {
    $("#dnsConfigList").innerHTML = `<div class="row single">${escapeHTML(err.message)}</div>`;
  }
}

function renderDnsBindings() {
  const node = $("#dnsBindingList");
  if (!node) return;
  node.innerHTML = state.dnsBindings.length
    ? `<div class="section-label">DNS 绑定</div>` + state.dnsBindings.map((binding) => `
      <div class="row dns-row">
        <div><strong>${escapeHTML(binding.name)}</strong><span>${escapeHTML(binding.fqdn)}</span></div>
        <div>${escapeHTML(binding.record_type)}</div>
        <div>${binding.enabled ? '<span class="badge">启用</span>' : '<span class="badge offline">停用</span>'}</div>
        <div class="row-actions"><button class="danger small" data-dns-binding-delete="${binding.id}">删除</button></div>
      </div>`).join("")
    : `<div class="row single">还没有 DNS 绑定。保存绑定后可用于自动补机和 IP 同步。</div>`;
  $$("[data-dns-binding-delete]").forEach((button) => button.addEventListener("click", () => deleteDnsBinding(button.dataset.dnsBindingDelete)));
}

async function submitDnsConfig(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  payload.enabled = form.enabled.checked;
  payload.uid = Number(payload.uid || 0);
  if (!payload.id) delete payload.id;
  try {
    await api("dns/configs", { method: "POST", body: JSON.stringify(payload) });
    form.password.value = "";
    form.api_key.value = "";
    showNotice("DNS 配置已保存", "success");
    await loadDns();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function renderDnsConfigs() {
  $("#dnsConfigList").innerHTML = state.dnsConfigs.length
    ? state.dnsConfigs.map((cfg) => `
      <div class="row dns-row">
        <div><strong>${escapeHTML(cfg.name)}</strong><span>${escapeHTML(cfg.base_url)}</span></div>
        <div>${cfg.auth_mode === "password" ? "账号密码" : "API Key"}</div>
        <div>${cfg.enabled ? '<span class="badge">启用</span>' : '<span class="badge offline">停用</span>'}</div>
        <div class="row-actions">
          <button class="secondary small" data-dns-test="${cfg.id}">测试</button>
          <button class="secondary small" data-dns-domains="${cfg.id}">域名</button>
          <button class="danger small" data-dns-delete="${cfg.id}">删除</button>
        </div>
      </div>`).join("")
    : `<div class="row single">还没有 DNS 配置。</div>`;
  $$("[data-dns-test]").forEach((button) => button.addEventListener("click", () => testDns(button.dataset.dnsTest)));
  $$("[data-dns-domains]").forEach((button) => button.addEventListener("click", () => loadDnsDomains(button.dataset.dnsDomains)));
  $$("[data-dns-delete]").forEach((button) => button.addEventListener("click", () => deleteDnsConfig(button.dataset.dnsDelete)));
}

function fillDnsConfigSelects() {
  $$('select[name="config_id"]').forEach((select) => {
    const current = select.value;
    select.innerHTML = state.dnsConfigs.map((cfg) => `<option value="${cfg.id}">${escapeHTML(cfg.name)}</option>`).join("");
    select.value = current || state.dnsConfigs[0]?.id || "";
  });
}

async function testDns(id) {
  try {
    const result = await api(`dns/configs/${id}/test`, { method: "POST", body: "{}" });
    showNotice(`DNS 连接成功，域名数量：${result.total}`, "success");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadDnsDomains(id = "") {
  const configId = id || $("#dnsRecordForm").config_id.value;
  if (!configId) return showNotice("请先选择 DNS 配置", "error");
  try {
    const result = await api(`dns/configs/${configId}/domains`);
    $("#dnsDomainList").innerHTML = (result.rows || []).length
      ? result.rows.map((domain) => `
        <div class="row dns-domain-row">
          <div><strong>${escapeHTML(domain.name || "-")}</strong><span>域名 ID：${escapeHTML(domain.id || "-")}</span></div>
          <div>记录数：${escapeHTML(domain.recordcount ?? "-")}</div>
          <div>${escapeHTML(domain.typename || domain.type || "")}</div>
          <div class="row-actions"><button class="secondary small" data-fill-domain="${domain.id}" data-domain-name="${escapeHTML(domain.name || "")}">填入表单</button></div>
        </div>`).join("")
      : `<div class="row single">未读取到域名。</div>`;
    $$("[data-fill-domain]").forEach((button) => button.addEventListener("click", () => {
      $("#dnsRecordForm").domain_id.value = button.dataset.fillDomain;
      showNotice(`已填入域名：${button.dataset.domainName}`, "success");
    }));
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function submitDnsRecord(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  const configId = payload.config_id;
  const domainId = payload.domain_id;
  try {
    await api(`dns/configs/${configId}/records?domain_id=${encodeURIComponent(domainId)}`, { method: "POST", body: JSON.stringify(payload) });
    showNotice("DNS 解析记录已保存", "success");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function submitDnsBinding(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  payload.enabled = form.enabled.checked;
  payload.domain_id = Number(payload.domain_id || 0);
  payload.ttl = Number(payload.ttl || 60);
  try {
    await api("dns/bindings", { method: "POST", body: JSON.stringify(payload) });
    showNotice("DNS 绑定已保存", "success");
    await loadDns();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function deleteDnsConfig(id) {
  if (!window.confirm("确认删除这个 DNS 配置和相关绑定吗？")) return;
  try {
    await api(`dns/configs/${id}`, { method: "DELETE" });
    showNotice("DNS 配置已删除", "success");
    await loadDns();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function deleteDnsBinding(id) {
  if (!window.confirm("确认删除这个 DNS 绑定吗？")) return;
  try {
    await api(`dns/bindings/${id}`, { method: "DELETE" });
    showNotice("DNS 绑定已删除", "success");
    await loadDns();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadNotifications() {
  try {
    state.notifications = await api("notifications");
    const form = $("#notificationForm");
    form.enabled.checked = state.notifications.enabled;
    form.telegram_chat_id.value = state.notifications.telegram_chat_id || "";
    form.telegram_group_chat_ids.value = state.notifications.telegram_group_chat_ids || "";
    form.check_interval_hours.value = state.notifications.check_interval_hours || 6;
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function submitNotification(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  payload.enabled = form.enabled.checked;
  payload.check_interval_hours = Number(payload.check_interval_hours || 6);
  try {
    await api("notifications", { method: "POST", body: JSON.stringify(payload) });
    form.bot_token.value = "";
    showNotice("通知设置已保存", "success");
    await loadNotifications();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function testNotification() {
  try {
    const result = await api("notifications/test", { method: "POST", body: JSON.stringify({ message: "Linode Panel 通知测试成功" }) });
    showNotice(`测试完成：成功 ${result.sent.length} 个，失败 ${result.failed.length} 个`, result.sent.length ? "success" : "error");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadLogs() {
  try {
    const data = await api("logs?limit=200");
    state.logs = data.data || [];
    $("#logList").innerHTML = state.logs.length
      ? state.logs.map((log) => `
        <div class="row log-row">
          <div><strong>${escapeHTML(log.action)}</strong><span>${escapeHTML(log.message)}</span></div>
          <div>${escapeHTML(log.source)}</div>
          <div>${escapeHTML(log.status)}</div>
          <div>${formatTime(log.created_at)}</div>
        </div>`).join("")
      : `<div class="row single">暂无执行日志。</div>`;
  } catch (err) {
    $("#logList").innerHTML = `<div class="row single">${escapeHTML(err.message)}</div>`;
  }
}

function exportLogs() {
  const lines = state.logs.map((log) => `[${log.created_at}] ${log.source}/${log.action}/${log.status} ${log.message}`);
  const blob = new Blob([lines.join("\n")], { type: "text/plain;charset=utf-8" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = `linode-panel-logs-${Date.now()}.txt`;
  a.click();
  URL.revokeObjectURL(url);
}

async function loadSecurity() {
  try {
    const data = await api("security");
    $("#securityForm").admin_user.value = data.admin_user || "admin";
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function submitSecurity(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  try {
    state.settings = await api("security", { method: "POST", body: JSON.stringify(payload) });
    form.current_password.value = "";
    form.new_password.value = "";
    showNotice("账号安全设置已保存", "success");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadAdmin() {
  try {
    state.admin = await api("admin/dashboard");
    const stats = state.admin.stats || {};
    $("#adminStats").innerHTML = [
      ["Linode 号池", stats.linode_accounts],
      ["可用账号", stats.available_accounts],
      ["代理配置", stats.proxies],
      ["DNS 配置", stats.dns_configs],
      ["DNS 绑定", stats.dns_bindings],
      ["补机策略", stats.replenish_policies],
      ["执行日志", stats.logs],
    ].map(([label, value]) => `<div class="meta"><span>${label}</span><strong>${value ?? 0}</strong></div>`).join("");
    $("#adminUsers").innerHTML = (state.admin.users || []).map((user) => `
      <div class="row admin-row">
        <div><strong>${escapeHTML(user.email)}</strong><span>${escapeHTML(user.role)}</span></div>
        <div>账号 ${user.account_count}</div>
        <div>代理 ${user.proxy_count}</div>
        <div>DNS ${user.dns_config_count}/${user.dns_binding_count}</div>
        <div>策略 ${user.workflow_count}<span>日志 ${user.execution_log_count}</span></div>
      </div>`).join("");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadReplenish() {
  try {
    const [data, policies, bindings] = await Promise.all([api("admin/dashboard"), api("replenish/policies"), api("dns/bindings")]);
    const stats = data.stats || {};
    state.replenishPolicies = policies.data || [];
    state.dnsBindings = bindings.data || [];
    $("#replenishStats").innerHTML = [
      ["Linode 号池", stats.linode_accounts],
      ["可用账号", stats.available_accounts],
      ["代理池", stats.proxies],
      ["DNS 绑定", stats.dns_bindings],
    ].map(([label, value]) => `<div class="meta"><span>${label}</span><strong>${value ?? 0}</strong></div>`).join("");
    fillDnsBindingSelects();
    renderReplenishPolicies();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function submitReplenish(event) {
  event.preventDefault();
  const form = event.currentTarget;
  const payload = Object.fromEntries(new FormData(form).entries());
  payload.enabled = form.enabled.checked;
  payload.backups_enabled = form.backups_enabled.checked;
  payload.private_ip = form.private_ip.checked;
  payload.target_count = Number(payload.target_count || 1);
  payload.min_running_count = Number(payload.min_running_count || 0);
  payload.firewall_id = Number(payload.firewall_id || 0);
  payload.dns_binding_id = Number(payload.dns_binding_id || 0);
  try {
    await api("replenish/policies", { method: "POST", body: JSON.stringify(payload) });
    form.root_pass.value = "";
    showNotice("自动补机策略已保存", "success");
    await loadReplenish();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function renderReplenishPolicies() {
  const node = $("#replenishList");
  node.innerHTML = state.replenishPolicies.length
    ? state.replenishPolicies.map((policy) => `
      <div class="row account-row">
        <div><strong>${escapeHTML(policy.name)}</strong><span>${escapeHTML(policy.name_prefix)} / ${escapeHTML(policy.region)}</span></div>
        <div>${policy.enabled ? '<span class="badge">启用</span>' : '<span class="badge offline">停用</span>'}</div>
        <div>${escapeHTML(policy.linode_type)}<span>${escapeHTML(policy.image)}</span></div>
        <div>目标 ${policy.target_count}<span>最少运行 ${policy.min_running_count}</span></div>
        <div class="row-actions">
          <button class="secondary small" data-replenish-run="${policy.id}">执行一次</button>
          <button class="danger small" data-replenish-delete="${policy.id}">删除</button>
        </div>
      </div>`).join("")
    : `<div class="row single">还没有自动补机策略。</div>`;
  $$("[data-replenish-run]").forEach((button) => button.addEventListener("click", () => runReplenish(button.dataset.replenishRun)));
  $$("[data-replenish-delete]").forEach((button) => button.addEventListener("click", () => deleteReplenish(button.dataset.replenishDelete)));
}

async function runReplenish(id) {
  if (!window.confirm("确认立即执行一次自动补机策略吗？可能会创建新的 Linode 并产生费用。")) return;
  try {
    const result = await api(`replenish/policies/${id}/run`, { method: "POST", body: "{}" });
    showNotice(result.message, "success");
    await loadReplenish();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function deleteReplenish(id) {
  if (!window.confirm("确认删除这个自动补机策略吗？")) return;
  try {
    await api(`replenish/policies/${id}`, { method: "DELETE" });
    showNotice("自动补机策略已删除", "success");
    await loadReplenish();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function fillDnsBindingSelects() {
  $$('select[name="dns_binding_id"]').forEach((select) => {
    const current = select.value;
    select.innerHTML = `<option value="">不绑定</option>` + state.dnsBindings.map((binding) => `<option value="${binding.id}">${escapeHTML(binding.name)} - ${escapeHTML(binding.fqdn)}</option>`).join("");
    select.value = current;
  });
}

async function logout() {
  await api("logout", { method: "POST", body: "{}" }).catch(() => undefined);
  state.authenticated = false;
  renderShell();
}

function fillSelect(select, items, valueFn, labelFn, includeEmpty = false) {
  select.innerHTML = `${includeEmpty ? '<option value="">不绑定</option>' : ""}${items.map((item) => `<option value="${escapeHTML(valueFn(item))}">${escapeHTML(labelFn(item))}</option>`).join("")}`;
}

function formatTime(value) {
  if (!value) return "-";
  return String(value).replace("T", " ").replace("+00:00", "");
}

function formatFullDate(value) {
  if (!value) return "-";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toString();
}

function formatMoney(value) {
  if (value === null || value === undefined || value === "") return "-";
  const number = Number(value);
  if (!Number.isFinite(number)) return String(value);
  return number.toFixed(2);
}

function formatUptimeHours(value) {
  const hours = Number(value);
  if (!Number.isFinite(hours)) return "-";
  if (hours < 1) return "不足1小时";
  const wholeHours = Math.floor(hours);
  const days = Math.floor(wholeHours / 24);
  const rest = wholeHours % 24;
  if (days > 0) return `${days}天${rest ? `${rest}小时` : ""}`;
  return `${wholeHours}小时`;
}

function escapeHTML(value) {
  return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

init();
