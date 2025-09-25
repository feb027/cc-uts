<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title><?= isset($title)? htmlspecialchars($title):'GameSpec Tracker'; ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<style>
:root {
  --bg:#111827;
  --bg-alt:rgba(31,41,55,0.85);
  --bg-alt-solid:#1F2937;
  --panel:#243044;
  --panel-soft:#1E293B;
  --panel-hover:#32415A;
  --badge-bg:#1E293B;
  --input-bg:#1E293B;
  --input-border:#334155;
  --accent:#4299E1;
  --accent-hover:#60A5FA;
  --text:#E2E8F0;
  --text-dim:#94A3B8;
  --danger:#E53E3E;
  --chart-avg-line:#4299E1;
  --chart-avg-fill:rgba(66,153,225,.15);
  --chart-low-line:#F59E0B;
  --chart-low-fill:rgba(245,158,11,.15);
  --radius:10px;
  --gap:1rem;
  --card-width:180px;
  --border:#334155;
  --shadow:0 4px 12px -2px rgba(0,0,0,.45),0 2px 4px -1px rgba(0,0,0,.4);
  font-family:'Segoe UI',system-ui,Arial,sans-serif;
}
[data-theme="light"] {
  --bg:#F1F5F9;
  --bg-alt:rgba(255,255,255,0.85);
  --bg-alt-solid:#FFFFFF;
  --panel:#FFFFFF;
  --panel-soft:#FFFFFF;
  --panel-hover:#F1F5F9;
  --badge-bg:#F1F5F9;
  --input-bg:#FFFFFF;
  --input-border:#CBD5E1;
  --text:#1E293B;
  --text-dim:#64748B;
  --border:#CBD5E1;
  --danger:#DC2626;
  --chart-avg-line:#2563EB;
  --chart-avg-fill:rgba(37,99,235,.15);
  --chart-low-line:#D97706;
  --chart-low-fill:rgba(217,119,6,.15);
  --shadow:0 2px 8px rgba(0,0,0,.08),0 1px 2px rgba(0,0,0,.08);
}
* { box-sizing:border-box; }
body { margin:0; background:var(--bg); color:var(--text); transition:background .35s, color .35s; display:flex; flex-direction:column; min-height:100vh; }
.skip-link { position:absolute; left:-999px; top:auto; width:1px; height:1px; overflow:hidden; }
.skip-link:focus { left:1rem; top:1rem; width:auto; height:auto; background:var(--accent); color:#fff; padding:.6rem .9rem; border-radius:6px; z-index:2000; }
header { backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:space-between; padding:.75rem 1.25rem; background:var(--bg-alt); border-bottom:1px solid var(--border); position:sticky; top:0; z-index:150; box-shadow:var(--shadow); transition:padding .35s, background .35s, border-color .35s; }
header.condensed { padding:.45rem 1rem; background:rgba(31,41,55,0.72); }
[data-theme="light"] header.condensed { background:rgba(255,255,255,0.78); }
header h1 { margin:0; font-size:1.15rem; letter-spacing:.5px; font-weight:600; }
nav { display:flex; align-items:center; gap:.25rem; }
nav a { color:var(--text-dim); text-decoration:none; padding:.55rem .75rem; border-radius:6px; font-size:.78rem; font-weight:500; line-height:1; position:relative; transition:color .25s, background .25s; --underline-h:2px; }
nav a::after { content:""; position:absolute; left:14px; right:14px; bottom:4px; height:var(--underline-h); background:linear-gradient(90deg,var(--accent),var(--accent-hover)); border-radius:1px; transform:scaleX(0); transform-origin:left; transition:transform .35s; }
nav a:hover::after, nav a:focus-visible::after, nav a[aria-current="page"]::after { transform:scaleX(1); }
nav a[aria-current="page"], nav a.active { color:var(--accent); background:rgba(66,153,225,.12); }
nav a:hover { color:var(--accent); background:rgba(66,153,225,.12); }
.nav-group { display:flex; align-items:center; gap:.35rem; }
.theme-toggle { background:transparent; border:1px solid var(--border); color:var(--text-dim); padding:.5rem .65rem; border-radius:8px; display:inline-flex; align-items:center; gap:.4rem; cursor:pointer; font-size:.7rem; line-height:1; transition:.25s; position:relative; }
.theme-toggle:hover { color:var(--accent); border-color:var(--accent); }
.hamburger { display:none; background:transparent; border:1px solid var(--border); color:var(--text-dim); padding:.55rem .65rem; border-radius:8px; cursor:pointer; }
.hamburger:focus { outline:2px solid var(--accent); outline-offset:2px; }
.container { max-width:1180px; margin:0 auto; padding:1.5rem 1.5rem 2.2rem; flex:1 0 auto; width:100%; }
.grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(var(--card-width),1fr)); gap:var(--gap); }
.card { background:var(--panel); border:1px solid var(--border); border-radius:var(--radius); padding:.75rem; display:flex; flex-direction:column; gap:.5rem; position:relative; transition:.3s background; }
.card img { width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:6px; background:#111; }
.card h3 { margin:.25rem 0 .5rem; font-size:.95rem; font-weight:600; line-height:1.2; }
.actions { display:flex; gap:.5rem; margin-top:auto; }
button, .btn { cursor:pointer; font:inherit; border:none; background:var(--accent); color:#fff; padding:.55rem .9rem; border-radius:6px; font-size:.75rem; text-decoration:none; display:inline-flex; align-items:center; gap:.35rem; font-weight:500; letter-spacing:.25px; box-shadow:0 1px 2px rgba(0,0,0,.4); }
button:hover,.btn:hover { filter:brightness(1.07); }
.btn.outline { background:transparent; border:1px solid var(--accent); color:var(--accent); }
.btn.danger { background:var(--danger); }
form.inline { display:inline; }
form .field { display:flex; flex-direction:column; gap:.35rem; margin-bottom:1rem; }
input[type=text], input[type=url], input[type=date], input[type=number], textarea { background:var(--input-bg); border:1px solid var(--input-border); color:var(--text); padding:.6rem .7rem; border-radius:6px; font-size:.8rem; font-family:inherit; transition:.25s; }
[data-theme="light"] input[type=text], [data-theme="light"] input[type=url], [data-theme="light"] input[type=date], [data-theme="light"] input[type=number], [data-theme="light"] textarea { color:var(--text); }
input:focus, textarea:focus, button:focus-visible, a:focus-visible { outline:2px solid var(--accent); outline-offset:2px; }
.error { background:#742A2A; color:#FED7D7; padding:.6rem .8rem; border-radius:6px; margin-bottom:1rem; font-size:.8rem; }
header .brand { display:flex; align-items:center; gap:.55rem; }
header .brand span.logo { width:34px; height:34px; background:linear-gradient(145deg,var(--accent),var(--accent-hover)); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; color:#fff; letter-spacing:.5px; box-shadow:0 4px 10px -2px rgba(66,153,225,.45); }
footer { text-align:center; padding:2.5rem 1rem; font-size:.65rem; opacity:.65; }
.panel-form { background:var(--panel); border:1px solid var(--border); border-radius:var(--radius); padding:1.2rem 1.25rem 1.7rem; box-shadow:var(--shadow); max-width:640px; }
.panel-form .panel-head { display:flex; align-items:center; gap:.75rem; margin-bottom:.25rem; }
.panel-form .panel-title { margin:0; font-size:1.05rem; font-weight:600; letter-spacing:.4px; }
.panel-form .panel-intro { margin:.15rem 0 1.1rem; font-size:.7rem; color:var(--text-dim); line-height:1.4; }
.panel-form .req { color:var(--danger); font-weight:600; }
.panel-form .optional { color:var(--text-dim); font-weight:400; font-size:.7rem; }
.form-grid { display:grid; gap:1rem 1.2rem; }
.form-grid[style*="--cols:2"] { grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); }
.form-grid .field.full { grid-column:1/-1; }
.form-grid .hint { font-size:.6rem; color:var(--text-dim); margin-top:.3rem; line-height:1.3; }
.form-actions { display:flex; gap:.6rem; margin-top:1.1rem; flex-wrap:wrap; }
.btn.small { padding:.45rem .7rem; font-size:.65rem; }
.sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
.form-layout { display:grid; grid-template-columns: minmax(0,640px) minmax(260px,1fr); gap:2rem; align-items:start; }
.panel-side { background:var(--panel-soft); border:1px solid var(--border); border-radius:var(--radius); padding:1rem 1rem 1.25rem; position:relative; box-shadow:var(--shadow); display:flex; flex-direction:column; gap:.9rem; }
.preview-box { display:flex; flex-direction:column; gap:.75rem; }
.preview-img-wrap { position:relative; background:var(--bg-alt-solid); border:1px solid var(--border); border-radius:8px; aspect-ratio:3/4; display:flex; align-items:center; justify-content:center; overflow:hidden; }
.preview-img-wrap img { max-width:100%; max-height:100%; object-fit:cover; display:none; }
.preview-empty { font-size:.6rem; color:var(--text-dim); text-align:center; padding:.6rem; display:flex; flex-direction:column; gap:.3rem; align-items:center; justify-content:center; }
.preview-meta { display:flex; flex-direction:column; gap:.25rem; font-size:.65rem; }
.preview-meta strong { font-size:.75rem; letter-spacing:.4px; }
.p-status { color:var(--text-dim); }
.field-error { font-size:.6rem; color:var(--danger); margin-top:.25rem; min-height:.8rem; }
button.loading { position:relative; opacity:.8; }
.spinner { width:14px; height:14px; border:2px solid var(--accent); border-right-color:transparent; border-radius:50%; display:inline-block; animation:spin .7s linear infinite; vertical-align:middle; }
@keyframes spin { to { transform:rotate(360deg); } }
@media (prefers-reduced-motion: reduce){
  * { animation-duration:.001ms !important; animation-iteration-count:1 !important; transition:none !important; }
}
body.no-scroll { overflow:hidden; }
nav.primary:focus { outline:none; }
nav.primary a { outline:none; }
@media (max-width:980px){ .form-layout { grid-template-columns:1fr; } .panel-side { order:-1; } }
.site-footer { margin-top:auto; background:var(--bg-alt); border-top:1px solid var(--border); padding:2.2rem 1.2rem 2.8rem; backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
.site-footer .footer-inner { max-width:1180px; margin:0 auto; display:flex; flex-wrap:wrap; gap:2rem 3rem; align-items:flex-start; justify-content:space-between; }
.site-footer a { color:var(--text-dim); text-decoration:none; font-size:.7rem; padding:.35rem .5rem; border-radius:6px; transition:.25s; display:inline-block; }
.site-footer a:hover { color:var(--accent); background:rgba(66,153,225,.12); }
.site-footer .f-brand { display:flex; align-items:center; gap:.8rem; min-width:180px; }
.site-footer .f-brand .logo-sm { width:38px; height:38px; border-radius:10px; background:linear-gradient(145deg,var(--accent),var(--accent-hover)); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:#fff; letter-spacing:.5px; box-shadow:0 3px 8px -2px rgba(66,153,225,.4); }
.site-footer .f-brand .meta { display:flex; flex-direction:column; font-size:.7rem; line-height:1.15; }
.site-footer .f-brand .meta strong { font-size:.8rem; letter-spacing:.5px; font-weight:600; }
.site-footer .f-brand .version { color:var(--text-dim); font-weight:500; }
.site-footer .f-nav { display:flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
.site-footer .f-nav .to-top { background:transparent; border:1px solid var(--border); color:var(--text-dim); padding:.4rem .55rem; border-radius:6px; cursor:pointer; font-size:.65rem; line-height:1; transition:.25s; position:relative; }
.site-footer .f-nav .to-top:hover { color:var(--accent); border-color:var(--accent); }
.site-footer .f-nav .to-top:focus { outline:2px solid var(--accent); outline-offset:2px; color:var(--accent); border-color:var(--accent); box-shadow:0 0 0 3px rgba(66,153,225,.35); }
.site-footer .f-meta { font-size:.65rem; display:flex; align-items:center; flex-wrap:wrap; gap:.5rem; opacity:.75; }
.site-footer .f-meta .sep { opacity:.4; }
@media (max-width:760px){
  .site-footer .footer-inner { flex-direction:column; align-items:flex-start; }
  .site-footer .f-nav { order:3; }
  .site-footer .f-meta { order:4; }
}
.empty { padding:2rem; text-align:center; border:2px dashed var(--border); border-radius:var(--radius); }
 .flash {background:#2F855A; color:#E6FFFA; padding:.7rem 1rem; border-radius:8px; margin:0 0 1rem; font-size:.8rem; box-shadow:0 2px 4px rgba(0,0,0,.35);}
 .flash.error {background:#742A2A; color:#FED7D7;}
/* Game detail layout */
.back-link { margin-bottom:1rem; }
.game-detail-layout { display:flex; gap:2rem; flex-wrap:wrap; align-items:flex-start; }
.game-aside { width:240px; flex:0 0 auto; }
.cover-figure { margin:0; }
.game-cover { width:100%; border-radius:10px; object-fit:cover; aspect-ratio:3/4; display:block; }
.game-cover.placeholder { object-fit:cover; }
.game-main { flex:1 1 260px; min-width:260px; }
.game-hero { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; }
.game-title { margin:.1rem 0 .3rem; font-size:1.45rem; font-weight:600; letter-spacing:.5px; }
.game-meta { margin:0; font-size:.7rem; opacity:.7; letter-spacing:.4px; }
.hero-actions { display:flex; gap:.5rem; }
.panel-block { background:var(--panel); border:1px solid var(--border); border-radius:var(--radius); padding:1rem 1rem 1.3rem; margin-bottom:1.5rem; box-shadow:var(--shadow); }
.panel-block + .panel-block { margin-top:0; }
.panel-head-row { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:.9rem; }
.panel-head-row.wrap { flex-wrap:wrap; }
.panel-title { margin:0; font-size:1rem; letter-spacing:.4px; font-weight:600; }
.panel-sub { margin:0; font-size:.85rem; font-weight:600; letter-spacing:.4px; }
.config-list, .bench-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.75rem; }
.config-item, .bench-item { background:var(--panel-soft); border:1px solid var(--border); border-radius:8px; padding:.8rem 1rem; display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.config-main, .bench-main { flex:1 1 auto; min-width:200px; }
.config-meta, .bench-meta { font-size:.65rem; opacity:.7; margin-top:.25rem; line-height:1.4; }
.config-notes, .bench-notes { margin:.55rem 0 0; font-size:.7rem; line-height:1.35; white-space:pre-wrap; }
.item-actions { display:flex; gap:.45rem; align-items:center; }
.filter-form { display:flex; gap:.6rem; align-items:flex-end; flex-wrap:wrap; }
.filter-field { display:flex; flex-direction:column; gap:.2rem; font-size:.55rem; text-transform:uppercase; letter-spacing:.5px; }
.filter-field input { padding:.35rem .5rem; border-radius:6px; }
.reset-link { color:var(--text-dim); font-size:.6rem; text-decoration:none; display:inline-block; margin-left:.25rem; }
.reset-link:hover { color:var(--accent); }
.empty.compact { padding:1rem; }
.chart-panel { background:var(--panel-soft); border:1px solid var(--border); border-radius:10px; padding:1rem 1rem 1.1rem; margin:1.1rem 0 1.35rem; }
.chart-head { display:flex; justify-content:space-between; align-items:center; margin:0 0 .6rem; }
.chart-head h5 { margin:0; font-size:.8rem; letter-spacing:.5px; font-weight:600; }
.chart-wrapper { position:relative; height:260px; width:100%; }
.sort-controls { display:flex; gap:.45rem; flex-wrap:wrap; align-items:center; margin:.25rem 0 .75rem; }
.sort-controls .btn.small { font-size:.6rem; padding:.4rem .55rem; }
.sort-controls .btn.active { filter:brightness(1.1); box-shadow:0 0 0 2px rgba(66,153,225,.35); }
/* Stats panel */
.stats-grid { list-style:none; margin:.35rem 0 .2rem; padding:0; display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:.75rem; }
.stat-card { background:var(--panel-soft); border:1px solid var(--border); border-radius:8px; padding:.6rem .7rem .7rem; display:flex; flex-direction:column; gap:.35rem; position:relative; }
.stat-label { font-size:.55rem; text-transform:uppercase; letter-spacing:.6px; opacity:.65; }
.stat-value { font-size:1rem; font-weight:600; letter-spacing:.5px; display:inline-flex; align-items:baseline; gap:.2rem; line-height:1; }
.stat-value.best { color:var(--accent); }
.stat-value.worst { color:var(--danger); }
.stat-unit { font-size:.55rem; font-weight:500; opacity:.6; letter-spacing:.5px; }
.stat-card.trend .trend-value { font-size:1.15rem; }
.trend-value.up { color:#16A34A; }
.trend-value.down { color:var(--danger); }
.trend-value.flat { color:var(--text-dim); }
/* Best benchmark highlight */
.bench-item.is-best { border-color:var(--accent); box-shadow:0 0 0 1px var(--accent); position:relative; }
.bench-item.is-best .bench-date { color:var(--accent); }
.best-badge { position:absolute; top:6px; right:6px; background:var(--accent); color:#fff; font-size:.5rem; padding:.25rem .4rem .3rem; border-radius:4px; letter-spacing:.7px; font-weight:600; text-transform:uppercase; box-shadow:0 2px 4px -1px rgba(0,0,0,.4); }
@media (max-width:860px){ .game-aside { width:200px; } }
@media (max-width:700px){ .game-detail-layout { flex-direction:column; } .game-aside { width:100%; max-width:320px; } .game-hero { flex-direction:column; align-items:flex-start; } }
@media (max-width:520px){ .filter-form { flex-direction:row; } .config-item, .bench-item { padding:.75rem .85rem; } .game-title { font-size:1.25rem; } }
@media (max-width:820px){
  nav.primary { position:fixed; top:60px; right:0; background:var(--bg-alt); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); padding:.75rem .9rem 1rem; display:none; flex-direction:column; align-items:stretch; gap:.25rem; min-width:190px; border-left:1px solid var(--border); border-bottom:1px solid var(--border); box-shadow:var(--shadow); max-height:calc(100vh - 70px); overflow:auto; }
  nav.primary.open { display:flex; }
  .hamburger { display:inline-flex; }
  nav a { margin-right:0; }
}
@media (max-width:600px){ header h1 { font-size:1rem; } .grid { grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); } }
</style>
</head>
<body>
<a href="#main" class="skip-link">Lewati ke konten</a>
<header>
  <div class="brand">
    <span class="logo">GS</span>
    <h1>GameSpec Tracker</h1>
  </div>
  <div class="nav-group">
    <button class="hamburger" id="navToggle" aria-label="Toggle navigasi" aria-expanded="false" aria-controls="primaryNav">☰</button>
    <nav class="primary" id="primaryNav" aria-label="Navigasi utama">
      <?php $act = $_GET['action'] ?? null; ?>
      <a href="index.php" class="<?= (!$act?'active':'') ?>" <?= (!$act?'aria-current="page"':'') ?>>Games</a>
      <a href="index.php?action=create" class="<?= ($act==='create'?'active':'') ?>" <?= ($act==='create'?'aria-current="page"':'') ?>>Tambah Game</a>
    </nav>
    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Ganti tema" aria-pressed="false" title="Ganti tema">🌙 <span style="font-size:.65rem; letter-spacing:.5px; font-weight:600;">Tema</span></button>
  </div>
</header>
<script>
// Header UX Enhancements
(function(){
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const navToggle = document.getElementById('navToggle');
  const nav = document.getElementById('primaryNav');
  const headerEl = document.querySelector('header');
  // Theme persistence + aria-pressed
  const stored = localStorage.getItem('gstheme');
  if(stored){ root.setAttribute('data-theme', stored); toggle?.setAttribute('aria-pressed', stored==='light'?'true':'false'); }
  function renderThemeLabel(mode){ return (mode==='light'?'🌞':'🌙') + ' <span style="font-size:.65rem; letter-spacing:.5px; font-weight:600;">Tema</span>'; }
  function apply(next){
    root.setAttribute('data-theme', next);
    localStorage.setItem('gstheme', next);
    toggle.innerHTML = renderThemeLabel(next);
    toggle.setAttribute('aria-pressed', next==='light');
  }
  toggle?.addEventListener('click', ()=>{
    const current = root.getAttribute('data-theme')||'dark';
    apply(current==='dark'?'light':'dark');
  });
  if(!stored && window.matchMedia('(prefers-color-scheme: light)').matches){ apply('light'); }
  // Scroll condense
  let lastScroll=0; const threshold=8;
  window.addEventListener('scroll', ()=>{
    const y = window.scrollY||0;
    if(y>threshold && y>lastScroll){ headerEl.classList.add('condensed'); }
    else if(y<=threshold){ headerEl.classList.remove('condensed'); }
    lastScroll = y;
  }, { passive:true });
  // Mobile nav open/close + focus trap
  let restoreFocus=null;
  function openNav(){
    if(!nav) return;
    restoreFocus=document.activeElement;
    nav.classList.add('open');
    navToggle.setAttribute('aria-expanded','true');
    document.body.classList.add('no-scroll');
    const firstLink = nav.querySelector('a');
    if(firstLink) firstLink.focus();
  }
  function closeNav(){
    if(!nav) return;
    nav.classList.remove('open');
    navToggle.setAttribute('aria-expanded','false');
    document.body.classList.remove('no-scroll');
    if(restoreFocus) restoreFocus.focus();
  }
  navToggle?.addEventListener('click', ()=>{ nav.classList.contains('open')? closeNav(): openNav(); });
  document.addEventListener('click', (e)=>{
    if(window.innerWidth<=820 && nav && nav.classList.contains('open')){
      if(!nav.contains(e.target) && e.target!==navToggle){ closeNav(); }
    }
  });
  document.addEventListener('keydown', (e)=>{
    if(e.key==='Escape' && nav.classList.contains('open')){ closeNav(); }
    if(e.key==='Tab' && nav.classList.contains('open')){
      const focusables=[...nav.querySelectorAll('a')];
      if(!focusables.length) return;
      const first=focusables[0]; const last=focusables[focusables.length-1];
      if(e.shiftKey && document.activeElement===first){ e.preventDefault(); last.focus(); }
      else if(!e.shiftKey && document.activeElement===last){ e.preventDefault(); first.focus(); }
    }
  });
  // Close nav when link clicked (mobile)
  nav?.addEventListener('click', (e)=>{ if(e.target.tagName==='A' && window.innerWidth<=820){ closeNav(); } });
})();
</script>
<div class="container" id="main">
<?php 
  use function App\Core\get_flash; 
  if ($m = get_flash('success')) echo '<div class="flash">'.htmlspecialchars($m).'</div>'; 
  if ($m = get_flash('error')) echo '<div class="flash error">'.htmlspecialchars($m).'</div>'; 
?>
