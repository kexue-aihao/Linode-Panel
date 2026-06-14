<?php
declare(strict_types=1);

function asset_url(string $path): string
{
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    $version = is_file($file) ? (string)filemtime($file) : '1';
    return htmlspecialchars($path . '?v=' . $version, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Linode Panel</title>
    <link rel="stylesheet" href="<?= asset_url('assets/styles.css') ?>" />
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
          <button class="nav-item active" data-view="vms"><span class="icon">□</span>VM 管理</button>
          <button class="nav-item" data-view="officialApi"><span class="icon">⌁</span>官方 API</button>
          <button class="nav-item" data-view="accounts"><span class="icon">＋</span>Linode 号池</button>
          <button class="nav-item" data-view="proxies"><span class="icon">◇</span>代理配置</button>
          <button class="nav-item" data-view="dns"><span class="icon">◎</span>DNS 管理</button>
          <button class="nav-item" data-view="replenish"><span class="icon">↻</span>自动补机</button>
          <button class="nav-item" data-view="notifications"><span class="icon">◌</span>通知设置</button>
          <button class="nav-item" data-view="logs"><span class="icon">≡</span>执行日志</button>
          <button class="nav-item" data-view="security"><span class="icon">⚙</span>账号安全</button>
          <button class="nav-item" data-view="admin"><span class="icon">★</span>管理员后台</button>
        </nav>
        <div class="sidebar-foot">
          <div id="tokenState" class="status-dot muted">未连接</div>
          <button id="logoutBtn" class="ghost small">退出</button>
        </div>
      </aside>

      <main class="main">
        <header class="topbar">
          <div>
            <h1 id="viewTitle">VM 管理</h1>
            <p id="viewSubtitle">查看 Linode 实例状态、IP 和生命周期操作。</p>
          </div>
          <div class="top-actions">
            <button id="refreshBtn" class="secondary">刷新</button>
            <button class="primary" data-jump="vms" data-open-create="1">创建实例</button>
          </div>
        </header>

        <section id="notice" class="notice hidden"></section>

        <section id="authPanel" class="auth-panel hidden">
          <div class="auth-card">
            <h2 id="authTitle">初始化面板</h2>
            <p id="authHint">先创建本面板的管理员账号，进入面板后再到 Linode 号池添加 Token。</p>
            <form id="authForm" class="form-grid">
              <label>管理员<input id="authUser" autocomplete="username" placeholder="admin" /></label>
              <label>密码<input id="authPassword" type="password" autocomplete="current-password" placeholder="至少 10 位" /></label>
              <button class="primary wide" type="submit">继续</button>
            </form>
          </div>
        </section>

        <section id="view-vms" class="view active">
          <div class="workspace wide-workspace">
            <div class="panel">
              <div class="panel-head">
                <div>
                  <h2>Linode 实例</h2>
                  <p class="muted">默认使用 Linode 号池中的默认账号；未设置号池时兼容历史单 Token 配置。</p>
                </div>
                <button id="loadCatalogBtn" class="secondary small">载入创建选项</button>
              </div>
              <div id="instanceGrid" class="instance-grid"></div>
            </div>

            <form id="createForm" class="panel create-panel hidden">
              <div class="panel-head">
                <h2>创建 Linode</h2>
                <button type="button" class="secondary small" data-close-create="1">收起</button>
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
            </form>
          </div>
        </section>

        <section id="view-accounts" class="view">
          <div class="workspace wide-workspace">
            <form id="accountForm" class="panel">
              <div class="panel-head"><h2>添加 Linode 号池账号</h2></div>
              <div class="form-grid two">
                <label>名称<input name="label" placeholder="US Pool 1" /></label>
                <label>绑定代理<select name="proxy_profile_id"><option value="">不使用代理</option></select></label>
                <label class="span-two">Linode Token<input name="token" type="password" autocomplete="off" placeholder="Personal Access Token" required /></label>
                <label class="span-two">备注<input name="remark" placeholder="用途、区域或来源" /></label>
              </div>
              <div class="toggle-row"><label><input type="checkbox" name="is_default" checked /> 设为默认账号</label></div>
              <button class="primary" type="submit">验证并添加</button>
            </form>
            <div class="panel">
              <div class="panel-head"><h2>Linode 号池</h2><button id="checkPoolBtn" class="secondary small">刷新号池</button></div>
              <div id="accountList" class="table-list"></div>
            </div>
          </div>
        </section>

        <section id="view-officialApi" class="view">
          <div class="workspace wide-workspace">
            <form id="officialApiForm" class="panel api-console">
              <div class="panel-head">
                <div>
                  <h2>官方 API 控制台</h2>
                  <p class="muted">使用默认 Linode 号池 Token 调用 Akamai/Linode 官方 API v4，覆盖官方 API 支持的全部模块。</p>
                </div>
                <a class="secondary small link-button" href="https://techdocs.akamai.com/linode-api/reference/api" target="_blank" rel="noreferrer">官方文档</a>
              </div>
              <div class="form-grid api-request-grid">
                <label>请求方法
                  <select name="method">
                    <option>GET</option>
                    <option>POST</option>
                    <option>PUT</option>
                    <option>PATCH</option>
                    <option>DELETE</option>
                  </select>
                </label>
                <label class="api-path-field">API 路径
                  <input name="path" value="/v4/profile" placeholder="/v4/linode/instances" required />
                </label>
                <label class="api-list-all"><input type="checkbox" name="list_all" /> GET 自动翻页</label>
              </div>
              <label>JSON 请求体（GET 可留空）
                <textarea name="payload" rows="8" spellcheck="false" placeholder='{"label":"demo","region":"us-east"}'></textarea>
              </label>
              <div class="api-actions">
                <button class="primary" type="submit">执行官方 API</button>
                <button class="secondary" type="button" id="clearOfficialApiBodyBtn">清空请求体</button>
              </div>
            </form>
            <div class="panel">
              <div class="panel-head">
                <div>
                  <h2>常用接口模板</h2>
                  <p class="muted">点击模板会自动填入方法、路径和示例请求体，执行前请确认会产生费用或修改资源的操作。</p>
                </div>
              </div>
              <div id="officialApiTemplates" class="api-template-grid"></div>
            </div>
            <div class="panel">
              <div class="panel-head"><h2>返回结果</h2></div>
              <pre id="officialApiResult" class="api-result">等待执行。</pre>
            </div>
          </div>
        </section>

        <section id="view-proxies" class="view">
          <div class="workspace wide-workspace">
            <form id="proxyForm" class="panel proxy-compose">
              <div class="panel-head">
                <div>
                  <h2>添加自托管代理</h2>
                  <p class="muted">这里配置固定自托管代理，支持手动添加 HTTP/SOCKS 代理，或通过代理 API 批量导入。</p>
                </div>
              </div>
              <div class="proxy-api-box">
                <label>代理 API 链接（可选，支持批量导入）
                  <textarea name="proxy_api_url" rows="4" placeholder="例如 https://www.miyaip.com/api/ProxyLogic/Generate?&#10;Num=10&SessionTime=30&Server=us&Format=0&Crc=...&GenType=socks5"></textarea>
                </label>
                <div class="proxy-api-controls">
                  <label>
                    <select name="raw_type">
                      <option value="auto">自动识别 http/socks</option>
                      <option value="socks5">SOCKS5</option>
                      <option value="http">HTTP</option>
                    </select>
                  </label>
                  <label><input name="proxy_api_limit" type="number" min="1" max="100" value="10" /></label>
                  <p class="muted">API 返回支持一行一个代理、JSON 数组、或嵌套字段；若链接里有 GenType=socks5/http，会自动按该协议优先识别。</p>
                </div>
                <p class="muted">填写代理 API 链接后点击下方提交，会自动拉取、解析、逐条验证并保存可用代理；API Key 会只保存在页面请求中，不会写入数据库。</p>
              </div>
              <div class="form-grid proxy-manual-grid">
                <label class="span-two"><input name="name" placeholder="标注或批量导入前缀" /></label>
                <label class="span-two">
                  <select name="type">
                    <option value="http">http</option>
                    <option value="socks5">socks5</option>
                  </select>
                </label>
                <label class="proxy-host"><input name="host" placeholder="主机名，例如 127.0.0.1" /></label>
                <label class="proxy-port"><input name="port" type="number" min="1" max="65535" placeholder="0" /></label>
                <label class="span-two"><input name="username" autocomplete="off" placeholder="用户名" /></label>
                <label class="span-two"><input name="password" type="password" autocomplete="off" placeholder="密码（可选）" /></label>
              </div>
              <div class="proxy-submit-row">
                <p class="muted">保存前会验证代理端口可连接，验证通过后可在账号、开机和补机流程中选择使用。</p>
                <label class="hidden"><input type="checkbox" name="validate" checked /> 保存前测活</label>
                <button class="primary" type="submit">验证并提交</button>
              </div>
              <div id="proxyImportProgress" class="proxy-progress hidden">
                <div class="proxy-progress-head">
                  <span class="spinner"></span>
                  <strong id="proxyProgressTitle">正在准备代理导入</strong>
                  <span id="proxyProgressCount">0/0</span>
                </div>
                <div class="progress-track"><div id="proxyProgressBar" class="progress-bar"></div></div>
                <p id="proxyProgressText" class="muted">等待开始。</p>
                <div id="proxyProgressResults" class="progress-results"></div>
              </div>
            </form>
            <div class="panel">
              <div class="panel-head">
                <h2>代理档案</h2>
                <button id="checkAllProxiesBtn" class="secondary small">一键测活</button>
              </div>
              <div id="proxyList" class="table-list"></div>
            </div>
          </div>
        </section>

        <section id="view-dns" class="view">
          <div class="workspace wide-workspace">
            <form id="dnsConfigForm" class="panel">
              <div class="panel-head"><h2>彩虹 DNS 面板接入</h2></div>
              <div class="form-grid two">
                <input type="hidden" name="id" />
                <label>配置名称<input name="name" placeholder="Rainbow DNS" /></label>
                <label>面板地址<input name="base_url" placeholder="https://dns.example.com" /></label>
                <label>用户名<input name="username" autocomplete="off" /></label>
                <label>密码<input name="password" type="password" autocomplete="off" placeholder="编辑时留空不修改" /></label>
                <label>UID<input name="uid" type="number" min="0" placeholder="API 模式可填" /></label>
                <label>API Key<input name="api_key" autocomplete="off" placeholder="API 模式可填" /></label>
              </div>
              <div class="toggle-row"><label><input type="checkbox" name="enabled" checked /> 启用</label></div>
              <button class="primary" type="submit">保存 DNS 配置</button>
            </form>
            <div class="panel">
              <div class="panel-head"><h2>DNS 配置</h2></div>
              <div id="dnsConfigList" class="table-list"></div>
            </div>
            <form id="dnsRecordForm" class="panel">
              <div class="panel-head"><h2>解析记录管理</h2></div>
              <div class="form-grid two">
                <label>DNS 配置<select name="config_id"></select></label>
                <label>域名 ID<input name="domain_id" type="number" min="1" placeholder="在域名列表中查看" /></label>
                <label>记录 ID<input name="record_id" placeholder="新增留空，编辑填写" /></label>
                <label>主机记录<input name="name" placeholder="@ 或 www" /></label>
                <label>类型<select name="type"><option>A</option><option>AAAA</option><option>CNAME</option><option>TXT</option></select></label>
                <label>记录值<input name="value" placeholder="1.2.3.4" /></label>
                <label>线路<input name="line" value="default" /></label>
                <label>TTL<input name="ttl" type="number" value="60" /></label>
              </div>
              <button class="primary" type="submit">保存解析记录</button>
              <button class="secondary" type="button" id="loadDnsDomainsBtn">读取域名列表</button>
            </form>
            <form id="dnsBindingForm" class="panel">
              <div class="panel-head"><h2>DNS 绑定</h2></div>
              <div class="form-grid two">
                <label>绑定名称<input name="name" placeholder="生产站点 A 记录" /></label>
                <label>DNS 配置<select name="config_id"></select></label>
                <label>域名 ID<input name="domain_id" type="number" min="1" /></label>
                <label>域名<input name="domain_name" placeholder="example.com" /></label>
                <label>主机记录<input name="subdomain" placeholder="@ 或 www" /></label>
                <label>记录类型<select name="record_type"><option>A</option><option>AAAA</option><option>A+AAAA</option></select></label>
                <label>线路<input name="line" value="default" /></label>
                <label>TTL<input name="ttl" type="number" value="60" /></label>
                <label class="span-two">备注<input name="remark" /></label>
              </div>
              <div class="toggle-row"><label><input type="checkbox" name="enabled" checked /> 启用</label></div>
              <button class="primary" type="submit">保存 DNS 绑定</button>
            </form>
            <div class="panel">
              <div class="panel-head"><h2>域名 / 绑定</h2></div>
              <div id="dnsDomainList" class="table-list"></div>
              <div id="dnsBindingList" class="table-list dns-binding-list"></div>
            </div>
          </div>
        </section>

        <section id="view-replenish" class="view">
          <div class="workspace wide-workspace">
            <form id="replenishForm" class="panel">
              <div class="panel-head">
                <div>
                  <h2>自动补机策略</h2>
                  <p class="muted">保存策略后可手动执行一次；也可用 aaPanel 计划任务定时请求执行接口。</p>
                </div>
              </div>
              <div class="form-grid two">
                <label>策略名称<input name="name" placeholder="生产补机" /></label>
                <label>名称前缀<input name="name_prefix" value="auto-linode" /></label>
                <label>区域<input name="region" placeholder="us-east" /></label>
                <label>套餐<input name="linode_type" placeholder="g6-nanode-1" /></label>
                <label>镜像<input name="image" placeholder="linode/ubuntu24.04" /></label>
                <label>目标数量<input name="target_count" type="number" min="1" value="1" /></label>
                <label>最少运行数量<input name="min_running_count" type="number" min="0" value="1" /></label>
                <label>防火墙 ID<input name="firewall_id" type="number" min="0" /></label>
                <label>DNS 绑定<select name="dns_binding_id"><option value="">不绑定</option></select></label>
                <label>标签<input name="tags" placeholder="panel,auto" /></label>
                <label>Root 密码<input name="root_pass" type="password" autocomplete="new-password" /></label>
                <label>SSH 公钥<textarea name="authorized_keys" rows="3"></textarea></label>
                <label class="span-two">备注<input name="remark" /></label>
              </div>
              <div class="toggle-row">
                <label><input type="checkbox" name="enabled" checked /> 启用策略</label>
                <label><input type="checkbox" name="backups_enabled" /> 启用备份</label>
                <label><input type="checkbox" name="private_ip" /> 分配私网 IP</label>
              </div>
              <button class="primary" type="submit">保存策略</button>
            </form>
            <div class="panel">
              <div class="panel-head"><h2>补机策略列表</h2></div>
              <div class="meta-grid dashboard-grid" id="replenishStats"></div>
              <div id="replenishList" class="table-list"></div>
            </div>
          </div>
        </section>

        <section id="view-notifications" class="view">
          <form id="notificationForm" class="workspace wide-workspace panel">
            <div class="panel-head"><h2>Telegram 通知设置</h2><button id="testNotifyBtn" class="secondary small" type="button">测试通知</button></div>
            <div class="form-grid two">
              <label class="span-two">Bot Token<input name="bot_token" type="password" autocomplete="off" placeholder="留空则不修改" /></label>
              <label>个人 UID / Chat ID<input name="telegram_chat_id" /></label>
              <label>检查间隔（小时）<input name="check_interval_hours" type="number" min="1" value="6" /></label>
              <label class="span-two">群组 Chat ID<textarea name="telegram_group_chat_ids" rows="4" placeholder="-1001234567890，一行一个"></textarea></label>
            </div>
            <div class="toggle-row"><label><input type="checkbox" name="enabled" /> 启用 Telegram 通知</label></div>
            <button class="primary" type="submit">保存通知设置</button>
          </form>
        </section>

        <section id="view-logs" class="view">
          <div class="workspace wide-workspace panel">
            <div class="panel-head"><h2>执行日志</h2><button id="exportLogsBtn" class="secondary small">导出日志</button></div>
            <div id="logList" class="table-list"></div>
          </div>
        </section>

        <section id="view-security" class="view">
          <form id="securityForm" class="workspace wide-workspace panel">
            <div class="panel-head"><h2>账号安全</h2></div>
            <div class="form-grid two">
              <label>管理员<input name="admin_user" /></label>
              <label>当前密码<input name="current_password" type="password" autocomplete="current-password" /></label>
              <label>新密码<input name="new_password" type="password" autocomplete="new-password" placeholder="至少 10 位，留空不修改" /></label>
            </div>
            <button class="primary" type="submit">保存安全设置</button>
          </form>
        </section>

        <section id="view-admin" class="view">
          <div class="workspace wide-workspace">
            <div class="panel">
              <div class="panel-head"><h2>管理员后台</h2></div>
              <div id="adminStats" class="meta-grid dashboard-grid"></div>
            </div>
            <div class="panel">
              <div class="panel-head"><h2>用户与资源统计</h2></div>
              <div id="adminUsers" class="table-list"></div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script src="<?= asset_url('assets/app.js') ?>"></script>
  </body>
</html>
