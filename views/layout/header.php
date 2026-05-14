<!DOCTYPE html>
<html lang="id" data-theme="<?= htmlspecialchars($_COOKIE['theme'] ?? 'light') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Sistem Akademik') ?> — PeTIK Jombang</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f0f4ff; --surface: #ffffff; --surface2: #f8faff;
      --border: #e2e8f8; --text: #0f1c3f; --text2: #5a6a8a;
      --accent: #2563eb; --accent2: #1d4ed8; --accent-glow: rgba(37,99,235,0.15);
      --green: #059669; --green-bg: rgba(5,150,105,0.1);
      --yellow: #d97706; --yellow-bg: rgba(217,119,6,0.1);
      --red: #dc2626; --red-bg: rgba(220,38,38,0.1);
      --purple: #7c3aed; --purple-bg: rgba(124,58,237,0.1);
      --shadow: 0 1px 3px rgba(15,28,63,0.08), 0 4px 16px rgba(15,28,63,0.06);
      --shadow-lg: 0 8px 32px rgba(15,28,63,0.12);
      --radius: 14px; --sidebar-w: 260px; --header-h: 64px;
      --transition: 0.2s cubic-bezier(.4,0,.2,1);
    }
    [data-theme="dark"] {
      --bg: #0a0f1e; --surface: #111827; --surface2: #1a2236;
      --border: #1e2d4a; --text: #e8edf8; --text2: #8898b8;
      --accent: #3b82f6; --accent2: #60a5fa; --accent-glow: rgba(59,130,246,0.2);
      --green: #10b981; --green-bg: rgba(16,185,129,0.12);
      --yellow: #f59e0b; --yellow-bg: rgba(245,158,11,0.12);
      --red: #ef4444; --red-bg: rgba(239,68,68,0.12);
      --purple: #a78bfa; --purple-bg: rgba(167,139,250,0.12);
      --shadow: 0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
      --shadow-lg: 0 8px 32px rgba(0,0,0,0.4);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); transition: background var(--transition), color var(--transition); min-height: 100vh; }

    /* ── HEADER ── */
    .header { position: fixed; top: 0; left: 0; right: 0; height: var(--header-h); background: var(--surface); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; z-index: 100; box-shadow: var(--shadow); }
    .header-left { display: flex; align-items: center; gap: 1rem; }
    .logo { display: flex; align-items: center; gap: 0.75rem; font-weight: 800; font-size: 1.1rem; color: var(--accent); letter-spacing: -0.3px; text-decoration: none; }
    .logo-icon { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, var(--accent), var(--purple)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem; font-weight: 800; box-shadow: 0 4px 12px var(--accent-glow); }
    .menu-btn { display: none; background: none; border: none; color: var(--text2); cursor: pointer; font-size: 1.2rem; padding: 0.4rem; border-radius: 8px; transition: all var(--transition); }
    .menu-btn:hover { background: var(--surface2); color: var(--text); }
    .header-right { display: flex; align-items: center; gap: 0.75rem; }
    .icon-btn { background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text2); font-size: 0.95rem; transition: all var(--transition); text-decoration: none; }
    .icon-btn:hover { color: var(--accent); border-color: var(--accent); background: var(--accent-glow); }
    .user-chip { display: flex; align-items: center; gap: 0.6rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; padding: 0.4rem 0.9rem 0.4rem 0.5rem; cursor: pointer; transition: all var(--transition); }
    .user-chip:hover { border-color: var(--accent); }
    .user-ava { width: 30px; height: 30px; border-radius: 8px; background: linear-gradient(135deg, var(--accent), var(--purple)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem; }
    .user-chip-name { font-size: 0.85rem; font-weight: 600; }
    .user-chip-role { font-size: 0.72rem; color: var(--text2); }

    /* ── SIDEBAR ── */
    .sidebar { position: fixed; top: var(--header-h); left: 0; bottom: 0; width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); padding: 1.5rem 0; overflow-y: auto; transition: transform var(--transition), background var(--transition); z-index: 90; }
    .nav-section { margin-bottom: 1.5rem; }
    .nav-label { padding: 0 1.25rem; margin-bottom: 0.4rem; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text2); }
    .nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 1.25rem; color: var(--text2); cursor: pointer; font-size: 0.9rem; font-weight: 500; margin: 0 0.75rem; border-radius: 10px; transition: all var(--transition); position: relative; text-decoration: none; }
    .nav-item:hover { background: var(--surface2); color: var(--text); }
    .nav-item.active { background: var(--accent-glow); color: var(--accent); font-weight: 700; }
    .nav-item.active::before { content: ''; position: absolute; left: -12px; top: 50%; transform: translateY(-50%); width: 4px; height: 24px; border-radius: 0 4px 4px 0; background: var(--accent); }
    .nav-icon { width: 20px; text-align: center; font-size: 0.95rem; }
    .nav-badge { margin-left: auto; background: var(--accent); color: white; font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 20px; }
    .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 80; backdrop-filter: blur(2px); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); margin-top: var(--header-h); padding: 2rem; min-height: calc(100vh - var(--header-h)); transition: margin var(--transition); }

    /* ── PAGE HEADER ── */
    .page-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap; }
    .page-title { font-size: 1.6rem; font-weight: 800; letter-spacing: -0.4px; }
    .page-sub { color: var(--text2); font-size: 0.88rem; margin-top: 0.2rem; }
    .breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text2); margin-bottom: 0.5rem; }
    .breadcrumb a, .breadcrumb span { color: var(--accent); font-weight: 600; text-decoration: none; }

    /* ── STATS GRID ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem; }
    .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem; transition: all var(--transition); position: relative; overflow: hidden; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
    .stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; }
    .stat-card.blue::after  { background: linear-gradient(90deg, var(--accent), var(--purple)); }
    .stat-card.green::after { background: linear-gradient(90deg, var(--green), #34d399); }
    .stat-card.yellow::after{ background: linear-gradient(90deg, var(--yellow), #fbbf24); }
    .stat-card.purple::after{ background: linear-gradient(90deg, var(--purple), #a78bfa); }
    .stat-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .stat-label { font-size: 0.82rem; font-weight: 600; color: var(--text2); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-ico { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .stat-ico.blue   { background: rgba(37,99,235,0.12); color: var(--accent); }
    .stat-ico.green  { background: var(--green-bg); color: var(--green); }
    .stat-ico.yellow { background: var(--yellow-bg); color: var(--yellow); }
    .stat-ico.purple { background: var(--purple-bg); color: var(--purple); }
    .stat-num  { font-size: 2.2rem; font-weight: 800; letter-spacing: -1px; font-family: 'JetBrains Mono', monospace; }
    .stat-info { font-size: 0.78rem; color: var(--text2); margin-top: 0.3rem; }

    /* ── CARD ── */
    .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
    .card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; font-weight: 700; font-size: 0.95rem; }
    .card-body { padding: 1.25rem; }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead tr { border-bottom: 2px solid var(--border); }
    th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text2); white-space: nowrap; }
    td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr { transition: background var(--transition); }
    tbody tr:hover { background: var(--surface2); }
    tbody tr:last-child td { border-bottom: none; }

    /* ── BADGE ── */
    .badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-green  { background: var(--green-bg);  color: var(--green); }
    .badge-yellow { background: var(--yellow-bg); color: var(--yellow); }
    .badge-red    { background: var(--red-bg);    color: var(--red); }
    .badge-blue   { background: var(--accent-glow); color: var(--accent); }
    .badge-purple { background: var(--purple-bg); color: var(--purple); }

    /* ── BUTTONS ── */
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.1rem; border-radius: 10px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all var(--transition); font-family: inherit; white-space: nowrap; text-decoration: none; }
    .btn-primary { background: var(--accent); color: white; }
    .btn-primary:hover { background: var(--accent2); transform: translateY(-1px); box-shadow: 0 4px 12px var(--accent-glow); }
    .btn-outline { background: transparent; color: var(--text2); border: 1px solid var(--border); }
    .btn-outline:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }
    .btn-danger { background: var(--red); color: white; }
    .btn-danger:hover { opacity: 0.85; }
    .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; border-radius: 8px; }
    .btn-icon { width: 32px; height: 32px; padding: 0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: var(--surface2); border: 1px solid var(--border); cursor: pointer; color: var(--text2); font-size: 0.85rem; transition: all var(--transition); text-decoration: none; }

    /* ── FILTER BAR ── */
    .filter-bar { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; margin-bottom: 1.25rem; }
    .search-wrap { position: relative; flex: 1; min-width: 220px; }
    .search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--text2); font-size: 0.85rem; pointer-events: none; }
    .form-input, .form-select { width: 100%; padding: 0.65rem 1rem 0.65rem 2.4rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 0.875rem; font-family: inherit; transition: all var(--transition); outline: none; }
    .form-select { padding-left: 1rem; }
    .form-input:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .form-input::placeholder { color: var(--text2); }

    /* ── PAGINATION ── */
    .pagination { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
    .page-btn { min-width: 36px; height: 36px; padding: 0 0.6rem; border-radius: 8px; background: var(--surface2); border: 1px solid var(--border); color: var(--text2); font-size: 0.83rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all var(--transition); }
    .page-btn:hover, .page-btn.active { background: var(--accent); border-color: var(--accent); color: white; }

    /* ── DETAIL ── */
    .detail-grid { display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem; }
    .profile-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem 1.5rem; text-align: center; }
    .profile-ava { width: 110px; height: 110px; border-radius: 50%; margin: 0 auto 1.25rem; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; font-weight: 800; color: white; background: linear-gradient(135deg, var(--accent), var(--purple)); box-shadow: 0 8px 24px var(--accent-glow); }
    .profile-name { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.25rem; }
    .profile-nim  { color: var(--text2); font-size: 0.9rem; font-family: 'JetBrains Mono', monospace; margin-bottom: 1rem; }
    .info-row   { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1rem; text-align: left; }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text2); font-weight: 700; }
    .info-value { font-size: 0.9rem; font-weight: 600; }
    .info-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    /* ── TOAST / ALERT ── */
    .alert { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1.25rem; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.875rem; font-weight: 600; }
    .alert-success { background: var(--green-bg); color: var(--green); border-left: 4px solid var(--green); }
    .alert-error   { background: var(--red-bg);   color: var(--red);   border-left: 4px solid var(--red); }
    .alert-info    { background: var(--accent-glow); color: var(--accent); border-left: 4px solid var(--accent); }

    /* ── FORM MODAL ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: var(--surface); border-radius: 16px; padding: 2rem; width: 90%; max-width: 580px; box-shadow: var(--shadow-lg); max-height: 90vh; overflow-y: auto; animation: slideUp 0.25s ease; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-title { font-size: 1.2rem; font-weight: 800; margin-bottom: 1.5rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.4rem; color: var(--text2); text-transform: uppercase; letter-spacing: 0.4px; }
    .form-field { width: 100%; padding: 0.7rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 0.875rem; font-family: inherit; outline: none; transition: all var(--transition); }
    .form-field:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); background: var(--surface); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .modal-footer { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border); }

    /* ── MISC ── */
    .empty { text-align: center; padding: 3rem 2rem; color: var(--text2); }
    .empty i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; display: block; }
    .empty-title { font-weight: 700; margin-bottom: 0.5rem; }
    .nim-mono { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 0.85rem; }
    .action-btns { display: flex; gap: 0.4rem; }
    .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 20px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } .content-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
      :root { --sidebar-w: 0px; }
      .sidebar { transform: translateX(-260px); width: 260px; }
      .sidebar.open { transform: translateX(0); }
      .main { margin-left: 0 !important; }
      .menu-btn { display: flex; }
      .stats-grid { grid-template-columns: 1fr 1fr; }
      .overlay.open { display: block; }
      .logo-text { display: none; }
    }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } .info-grid-2 { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

<!-- ══ HEADER ══ -->
<header class="header">
  <div class="header-left">
    <button class="menu-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <a href="index.php" class="logo">
      <div class="logo-icon">P</div>
      <span class="logo-text">PeTIK Jombang</span>
    </a>
  </div>
  <div class="header-right">
    <button class="icon-btn" onclick="toggleTheme()" title="Toggle tema" id="themeBtn">
      <i class="fas fa-moon"></i>
    </button>
    <span class="icon-btn" title="Notifikasi"><i class="fas fa-bell"></i></span>
    <div class="user-chip">
      <div class="user-ava">AD</div>
      <div>
        <div class="user-chip-name">Admin</div>
        <div class="user-chip-role">Administrator</div>
      </div>
    </div>
  </div>
</header>
