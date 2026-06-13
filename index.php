<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Linode Panel</title>
    <link rel="stylesheet" href="assets/styles.css" />
  </head>
  <body>
    <div id="app">
      <aside class="sidebar">
        <div class="brand">
          <div class="brand-mark">LP</div>
          <div>
            <strong>Linode Panel</strong>
            <span>快速启动与管理</span>
          </div>
        </div>
        <nav>
          <button class="nav-item active" data-view="instances"><span class="icon">□</span>实例</button>
          <button class="nav-item" data-view="create"><span class="icon">＋</span>创建</button>
          <button class="nav-item" data-view="firewalls"><span class="icon">◇</span>防火墙</button>
          <button class="nav-item" data-view="events"><span class="icon">◷</span>事件</button>
          <button class="nav-item" data-view="settings"><span class="icon">⚙</span>设置</button>
        </nav>
        <div class="sidebar-foot">
          <div id="tokenState" class="status-dot muted">未连接</div>
          <button id="logoutBtn" class="ghost small">退出</button>
        </div>
      </aside>

      <main class="main">
        <header class="topbar">
          <div>
            <h1 id="viewTitle">实例</h1>
            <p id="viewSubtitle">查看状态、IP、套餐和生命周期操作。</p>
          </div>
          <div class="top-actions">
            <button id="refreshBtn" class="secondary">刷新</button>
            <button class="primary" data-jump="create">创建实例</button>
          </div>
        </header>

        <section id="notice" class="notice hidden"></section>

        <section id="authPanel" class="auth-panel hidden">
          <div class="auth-card">
            <h2 id="authTitle">初始化面板</h2>
            <p id="authHint">创建本面板的管理员账号，并保存 Linode Token。</p>
            <form id="authForm" class="form-grid">
              <label>管理员<input id="authUser" autocomplete="username" placeholder="admin" /></label>
              <label>密码<input id="authPassword" type="password" autocomplete="current-password" placeholder="至少 10 位" /></label>
              <label id="authTokenWrap">Linode Token<input id="authToken" type="password" autocomplete="off" placeholder="Personal Access Token" /></label>
              <button class="primary wide" type="submit">继续</button>
            </form>
          </div>
        </section>

        <section id="view-instances" class="view active">
          <div id="instanceGrid" class="instance-grid"></div>
        </section>

        <section id="view-create" class="view">
          <form id="createForm" class="workspace">
            <div class="panel">
              <div class="panel-head">
                <h2>创建 Linode</h2>
                <button type="button" id="loadCatalogBtn" class="secondary small">载入选项</button>
              </div>
              <div class="form-grid two">
                <label>实例名称<input name="label" placeholder="web-1" required /></label>
                <label>区域<select name="region" required></select></label>
                <label>套餐<select name="type" required></select></label>
                <label>镜像<select name="image" required></select></label>
                <label>Root 密码<input name="root_pass" type="password" autocomplete="new-password" placeholder="强密码" /></label>
                <label>SSH 公钥<textarea name="authorized_keys" rows="3" placeholder="ssh-ed25519 AAAA..."></textarea></label>
                <label>防火墙<select name="firewall_id"><option value="">不绑定</option></select></label>
                <label>标签<input name="tags" placeholder="panel,production" /></label>
              </div>
              <div class="toggle-row">
                <label><input type="checkbox" name="backups_enabled" /> 启用备份</label>
                <label><input type="checkbox" name="private_ip" /> 分配私网 IP</label>
              </div>
              <div class="danger-note">创建实例会产生费用。请确认区域、套餐、镜像和安全设置。</div>
              <button class="primary" type="submit">创建实例</button>
            </div>
          </form>
        </section>

        <section id="view-firewalls" class="view">
          <div class="workspace">
            <div class="panel">
              <div class="panel-head">
                <h2>防火墙</h2>
                <button id="createDefaultFirewallBtn" class="secondary small">创建默认 SSH/HTTP/HTTPS</button>
              </div>
              <div id="firewallList" class="table-list"></div>
            </div>
          </div>
        </section>

        <section id="view-events" class="view">
          <div class="workspace">
            <div class="panel">
              <div class="panel-head">
                <h2>最近事件</h2>
                <button id="loadEventsBtn" class="secondary small">刷新事件</button>
              </div>
              <div id="eventList" class="table-list"></div>
            </div>
          </div>
        </section>

        <section id="view-settings" class="view">
          <form id="settingsForm" class="workspace">
            <div class="panel">
              <h2>设置</h2>
              <div class="form-grid two">
                <label>管理员<input name="admin_user" /></label>
                <label>新密码<input name="password" type="password" autocomplete="new-password" placeholder="留空则不修改" /></label>
                <label>Linode Token<input name="linode_token" type="password" autocomplete="off" placeholder="留空则不修改" /></label>
                <label>代理 URL<input name="proxy_url" placeholder="http://127.0.0.1:7890" /></label>
              </div>
              <div class="toggle-row">
                <label><input type="checkbox" name="clear_linode_token" /> 清空 Token</label>
                <label><input type="checkbox" name="clear_proxy_url" /> 清空代理</label>
              </div>
              <button class="primary" type="submit">保存设置</button>
            </div>
          </form>
        </section>
      </main>
    </div>

    <script src="assets/app.js"></script>
  </body>
</html>

