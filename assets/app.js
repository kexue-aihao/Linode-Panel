const state = { configured: false, authenticated: false, settings: {}, view: "instances", catalog: null };
const $ = (selector, root = document) => root.querySelector(selector);
const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
const apiPath = (action) => `api.php?action=${encodeURIComponent(action)}`;
const titles = {
  instances: ["实例", "查看状态、IP、套餐和生命周期操作。"],
  create: ["创建", "选择区域、套餐、镜像并启动新实例。"],
  firewalls: ["防火墙", "管理常用入站规则和实例绑定前的安全基线。"],
  events: ["事件", "查看创建、开关机、重建等异步操作进度。"],
  settings: ["设置", "管理面板账号、Linode Token 和代理。"],
};

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
    if (state.authenticated) await loadInstances();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function renderShell() {
  const locked = !state.configured || !state.authenticated;
  $("#app").classList.toggle("auth-mode", locked);
  $("#authPanel").classList.toggle("hidden", !locked);
  $$(".view").forEach((view) => view.classList.toggle("hidden", locked));
  $(".topbar").classList.toggle("hidden", locked);
  $(".sidebar").classList.toggle("hidden", locked);
  if (locked) {
    $("#authTitle").textContent = state.configured ? "登录面板" : "初始化面板";
    $("#authHint").textContent = state.configured ? "输入管理员账号继续管理 Linode。" : "先创建本面板的管理员账号，进入面板后再到设置中保存 Linode Token。";
    $("#authUser").value = state.settings.admin_user || "admin";
    return;
  }
  $("#tokenState").textContent = state.settings.has_linode_token ? "Token 已保存" : "未连接";
  $("#tokenState").classList.toggle("muted", !state.settings.has_linode_token);
  const settingsForm = $("#settingsForm");
  settingsForm.admin_user.value = state.settings.admin_user || "admin";
  settingsForm.proxy_url.value = state.settings.proxy_url || "";
  setView(state.view);
}

function bindNavigation() {
  $$(".nav-item").forEach((button) => button.addEventListener("click", () => setView(button.dataset.view)));
  $$("[data-jump]").forEach((button) => button.addEventListener("click", () => setView(button.dataset.jump)));
  $("#refreshBtn").addEventListener("click", () => refreshCurrent());
  $("#logoutBtn").addEventListener("click", logout);
  $("#loadCatalogBtn").addEventListener("click", () => loadCatalog(true));
  $("#loadEventsBtn").addEventListener("click", loadEvents);
  $("#createDefaultFirewallBtn").addEventListener("click", createDefaultFirewall);
}

function bindForms() {
  $("#authForm").addEventListener("submit", async (event) => {
    event.preventDefault();
    const payload = { admin_user: $("#authUser").value.trim() || "admin", password: $("#authPassword").value };
    const endpoint = state.configured ? "login" : "setup";
    const isFirstSetup = !state.configured;
    try {
      const settings = await api(endpoint, { method: "POST", body: JSON.stringify(payload) });
      state.configured = true;
      state.authenticated = true;
      state.settings = settings;
      $("#authPassword").value = "";
      state.view = isFirstSetup && !settings.has_linode_token ? "settings" : state.view;
      renderShell();
      showNotice(isFirstSetup ? "管理员账号已创建，请在设置中保存 Linode Token" : "已进入面板", "success");
      if (state.settings.has_linode_token) await loadInstances();
    } catch (err) {
      showNotice(err.message, "error");
    }
  });

  $("#settingsForm").addEventListener("submit", async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const payload = {
      admin_user: form.admin_user.value.trim(),
      password: form.password.value,
      linode_token: form.linode_token.value.trim(),
      proxy_url: form.proxy_url.value.trim(),
      clear_linode_token: form.clear_linode_token.checked,
      clear_proxy_url: form.clear_proxy_url.checked,
    };
    if (!payload.password) delete payload.password;
    if (!payload.linode_token) delete payload.linode_token;
    if (!payload.proxy_url) delete payload.proxy_url;
    try {
      state.settings = await api("settings", { method: "PUT", body: JSON.stringify(payload) });
      form.password.value = "";
      form.linode_token.value = "";
      form.clear_linode_token.checked = false;
      form.clear_proxy_url.checked = false;
      renderShell();
      showNotice("设置已保存", "success");
    } catch (err) {
      showNotice(err.message, "error");
    }
  });

  $("#createForm").addEventListener("submit", async (event) => {
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
      setView("instances");
      await loadInstances();
    } catch (err) {
      showNotice(err.message, "error");
    }
  });
}

function setView(view) {
  state.view = view;
  $$(".nav-item").forEach((button) => button.classList.toggle("active", button.dataset.view === view));
  $$(".view").forEach((node) => node.classList.toggle("active", node.id === `view-${view}`));
  const [title, subtitle] = titles[view] || titles.instances;
  $("#viewTitle").textContent = title;
  $("#viewSubtitle").textContent = subtitle;
  if (view === "create" && !state.catalog) loadCatalog();
  if (view === "firewalls") loadFirewalls();
  if (view === "events") loadEvents();
}

async function refreshCurrent() {
  if (state.view === "instances") return loadInstances();
  if (state.view === "create") return loadCatalog(true);
  if (state.view === "firewalls") return loadFirewalls();
  if (state.view === "events") return loadEvents();
  return loadSession();
}

async function loadInstances() {
  try {
    $("#instanceGrid").innerHTML = `<div class="panel">加载实例中...</div>`;
    const data = await api("linode/instances");
    const instances = data.data || [];
    if (!instances.length) {
      $("#instanceGrid").innerHTML = `<div class="panel">还没有实例。</div>`;
      return;
    }
    $("#instanceGrid").innerHTML = instances.map(instanceCard).join("");
    $$(".instance-card [data-action]").forEach((button) => button.addEventListener("click", () => instanceAction(button.dataset.id, button.dataset.action, button.dataset.label)));
  } catch (err) {
    $("#instanceGrid").innerHTML = `<div class="panel">${escapeHTML(err.message)}</div>`;
  }
}

function instanceCard(item) {
  const statusClass = item.status === "running" ? "" : "offline";
  const ipv4 = (item.ipv4 || []).join(", ") || "-";
  const ipv6 = item.ipv6 || "-";
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
      </div>
      <div class="card-actions">
        <button class="secondary small" data-action="boot" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">开机</button>
        <button class="secondary small" data-action="reboot" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">重启</button>
        <button class="secondary small" data-action="shutdown" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">关机</button>
        <button class="danger small" data-action="delete" data-id="${item.id}" data-label="${escapeHTML(item.label || "")}">删除</button>
      </div>
    </article>`;
}

async function instanceAction(id, action, label) {
  const names = { boot: "开机", reboot: "重启", shutdown: "关机", delete: "删除" };
  if (!window.confirm(`确认${names[action] || action}实例 ${label || id}？${action === "delete" ? " 删除后不可恢复。" : ""}`)) return;
  try {
    if (action === "delete") {
      await api(`linode/instances/${id}`, { method: "DELETE" });
    } else {
      await api(`linode/instances/${id}/${action}`, { method: "POST", body: "{}" });
    }
    showNotice(`已提交${names[action] || action}操作`, "success");
    await loadInstances();
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadCatalog(force = false) {
  if (state.catalog && !force) return populateCatalog();
  try {
    state.catalog = await api("linode/catalog");
    populateCatalog();
    showNotice("选项已载入", "success");
  } catch (err) {
    showNotice(err.message, "error");
  }
}

function populateCatalog() {
  if (!state.catalog) return;
  fillSelect($("#createForm").region, state.catalog.regions?.data || [], (item) => item.id, (item) => `${item.label || item.id} (${item.id})`);
  fillSelect($("#createForm").type, state.catalog.types?.data || [], (item) => item.id, (item) => `${item.label || item.id} - $${item.price?.monthly || "?"}/mo`);
  fillSelect($("#createForm").image, state.catalog.images?.data || [], (item) => item.id, (item) => item.label || item.id);
  fillSelect($("#createForm").firewall_id, state.catalog.firewalls?.data || [], (item) => item.id, (item) => item.label || item.id, "不绑定");
}

function fillSelect(select, items, valueOf, labelOf, emptyLabel) {
  select.innerHTML = emptyLabel ? `<option value="">${emptyLabel}</option>` : "";
  for (const item of items) {
    const option = document.createElement("option");
    option.value = valueOf(item);
    option.textContent = labelOf(item);
    select.appendChild(option);
  }
}

async function loadFirewalls() {
  try {
    $("#firewallList").innerHTML = "加载中...";
    const data = await api("linode/firewalls");
    const items = data.data || [];
    $("#firewallList").innerHTML = items.length ? items.map((item) => `
      <div class="row"><strong>${escapeHTML(item.label)}</strong><span>${escapeHTML(item.status || "-")}</span><span>入站 ${item.rules?.inbound?.length || 0}</span><span>出站 ${item.rules?.outbound?.length || 0}</span></div>
    `).join("") : "还没有防火墙。";
  } catch (err) {
    $("#firewallList").textContent = err.message;
  }
}

async function createDefaultFirewall() {
  if (!window.confirm("创建默认防火墙，开放 SSH、HTTP、HTTPS 入站？")) return;
  const payload = {
    label: `linode-panel-default-${Date.now()}`,
    rules: {
      inbound_policy: "DROP",
      outbound_policy: "ACCEPT",
      inbound: [
        { action: "ACCEPT", protocol: "TCP", ports: "22", addresses: { ipv4: ["0.0.0.0/0"], ipv6: ["::/0"] }, label: "SSH" },
        { action: "ACCEPT", protocol: "TCP", ports: "80,443", addresses: { ipv4: ["0.0.0.0/0"], ipv6: ["::/0"] }, label: "Web" },
      ],
      outbound: [],
    },
  };
  try {
    await api("linode/firewalls", { method: "POST", body: JSON.stringify(payload) });
    showNotice("默认防火墙已创建", "success");
    await loadFirewalls();
    state.catalog = null;
  } catch (err) {
    showNotice(err.message, "error");
  }
}

async function loadEvents() {
  try {
    $("#eventList").innerHTML = "加载中...";
    const data = await api("linode/events");
    const items = (data.data || []).slice(0, 30);
    $("#eventList").innerHTML = items.length ? items.map((item) => `
      <div class="row"><strong>${escapeHTML(item.action || "-")}</strong><span>${escapeHTML(item.status || "-")}</span><span>${escapeHTML(item.entity?.label || item.entity?.id || "-")}</span><span>${escapeHTML(formatTime(item.created))}</span></div>
    `).join("") : "还没有事件。";
  } catch (err) {
    $("#eventList").textContent = err.message;
  }
}

async function logout() {
  await api("logout", { method: "POST", body: "{}" });
  state.authenticated = false;
  renderShell();
}

function formatTime(value) { return value ? new Date(value).toLocaleString() : "-"; }
function escapeHTML(value) {
  return String(value ?? "").replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;").replaceAll('"', "&quot;").replaceAll("'", "&#039;");
}

init();
