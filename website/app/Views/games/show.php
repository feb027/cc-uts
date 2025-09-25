<?php 
/** @var array $game */
/** @var array[] $configurations */
/** @var array[] $benchmarks */
/** @var ?string $from */
/** @var ?string $to */
// Derive benchmark statistics
$benchStats = [
  'count' => count($benchmarks),
  'latest_avg' => null,
  'best_avg' => null,
  'worst_avg' => null,
  'trend' => null, // up/down/flat
];
if (!empty($benchmarks)) {
  $sortedByDate = $benchmarks;
  usort($sortedByDate, function($a,$b){ return strcmp(($a['benchmark_date']??''), ($b['benchmark_date']??'')); });
  $latest = end($sortedByDate);
  $benchStats['latest_avg'] = $latest['avg_fps'];
  // best & worst avg
  $valid = array_filter($benchmarks, fn($b)=> $b['avg_fps'] !== null);
  if ($valid) {
    usort($valid, fn($a,$b)=> ($b['avg_fps'] <=> $a['avg_fps']));
    $benchStats['best_avg'] = $valid[0]['avg_fps'];
    $benchStats['worst_avg'] = $valid[count($valid)-1]['avg_fps'];
    if (count($valid) >= 2) {
      $lastTwo = array_slice($sortedByDate, -2);
      $prevAvg = $lastTwo[0]['avg_fps'];
      $currAvg = $lastTwo[1]['avg_fps'];
      if ($prevAvg !== null && $currAvg !== null) {
        $benchStats['trend'] = $currAvg > $prevAvg ? 'up' : ($currAvg < $prevAvg ? 'down' : 'flat');
      }
    }
  }
}
// Determine best benchmark id
$bestBenchmarkId = null;
if (!empty($benchmarks)) {
  $candidates = array_filter($benchmarks, fn($b)=> $b['avg_fps'] !== null);
  if ($candidates) {
    usort($candidates, fn($a,$b)=> ($b['avg_fps'] <=> $a['avg_fps']));
    $bestBenchmarkId = $candidates[0]['id'];
  }
}
require __DIR__ . '/../layouts/header.php'; ?>
<a href="index.php" class="btn outline back-link">← Kembali</a>
<div class="game-detail-layout">
  <aside class="game-aside">
    <figure class="cover-figure">
      <?php if ($game['cover_image_url']): ?>
  <img src="<?= htmlspecialchars($game['cover_image_url']); ?>" alt="Cover <?= htmlspecialchars($game['name']); ?>" class="game-cover" loading="lazy" />
      <?php else: ?>
        <img src="https://placehold.co/400x533/1E293B/94A3B8?text=No+Cover" alt="Tidak ada cover" class="game-cover placeholder" />
      <?php endif; ?>
    </figure>
  </aside>
  <main class="game-main">
    <header class="game-hero">
      <div class="hero-text">
        <h2 class="game-title"><?= htmlspecialchars($game['name']); ?></h2>
        <p class="game-meta">ID: <?= $game['id']; ?> • Dibuat: <?= $game['created_at']; ?></p>
      </div>
      <div class="hero-actions">
  <a href="index.php?action=edit&id=<?= $game['id']; ?>" class="btn outline small">Edit Game</a>
  <a href="index.php?action=delete&id=<?= $game['id']; ?>" class="btn danger small" onclick="return confirm('Hapus game dan semua data terkait?');">Hapus</a>
      </div>
    </header>
    <?php if ($benchStats['count']): ?>
    <section class="panel-block stats-panel" aria-label="Ringkasan benchmark">
      <ul class="stats-grid" role="list">
        <li class="stat-card">
          <span class="stat-label">Total Benchmark</span>
          <span class="stat-value"><?= (int)$benchStats['count']; ?></span>
        </li>
        <li class="stat-card">
          <span class="stat-label">Avg Terbaru</span>
          <span class="stat-value"><?= $benchStats['latest_avg'] !== null ? htmlspecialchars($benchStats['latest_avg']) : '-'; ?><span class="stat-unit"> FPS</span></span>
        </li>
        <li class="stat-card">
          <span class="stat-label">Avg Terbaik</span>
          <span class="stat-value best"><?= $benchStats['best_avg'] !== null ? htmlspecialchars($benchStats['best_avg']) : '-'; ?><span class="stat-unit"> FPS</span></span>
        </li>
        <li class="stat-card">
          <span class="stat-label">Avg Terendah</span>
          <span class="stat-value worst"><?= $benchStats['worst_avg'] !== null ? htmlspecialchars($benchStats['worst_avg']) : '-'; ?><span class="stat-unit"> FPS</span></span>
        </li>
        <li class="stat-card trend">
          <span class="stat-label">Trend</span>
          <span class="stat-value trend-value <?= $benchStats['trend'] ? htmlspecialchars($benchStats['trend']) : 'flat'; ?>">
            <?php if ($benchStats['trend']==='up'): ?>⬈<span class="sr-only">Naik</span><?php elseif ($benchStats['trend']==='down'): ?>⬊<span class="sr-only">Turun</span><?php else: ?>→<span class="sr-only">Stabil</span><?php endif; ?>
          </span>
        </li>
      </ul>
    </section>
    <?php endif; ?>

    <section class="panel-block" aria-labelledby="configsHeading">
      <div class="panel-head-row">
        <h3 id="configsHeading" class="panel-title">Konfigurasi</h3>
        <button class="btn small" type="button" data-open-modal="configModal">+ Konfigurasi</button>
      </div>
      <?php if (empty($configurations)): ?>
        <div class="empty compact">Belum ada konfigurasi.</div>
      <?php else: ?>
        <ul class="config-list">
          <?php foreach ($configurations as $c): ?>
            <li class="config-item">
              <div class="config-main">
                <strong class="config-name"><?= htmlspecialchars($c['profile_name']); ?></strong>
                <div class="config-meta">DPI: <?= htmlspecialchars($c['mouse_dpi'] ?? '-'); ?> • Sens: <?= htmlspecialchars($c['in_game_sensitivity'] ?? '-'); ?> • Crosshair: <?= htmlspecialchars($c['crosshair_code'] ?? '-'); ?></div>
                <?php if ($c['graphics_notes']): ?><p class="config-notes"><?= nl2br(htmlspecialchars($c['graphics_notes'])); ?></p><?php endif; ?>
              </div>
              <div class="item-actions">
                <a class="btn outline small" href="index.php?action=config_edit&id=<?= $c['id']; ?>">Edit</a>
                <a class="btn danger small" href="index.php?action=config_delete&id=<?= $c['id']; ?>" onclick="return confirm('Hapus konfigurasi ini?');">Hapus</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

    <section class="panel-block" aria-labelledby="benchmarkHeading">
      <div class="panel-head-col">
        <div class="panel-head-row wrap">
          <h3 id="benchmarkHeading" class="panel-title">Benchmark</h3>
          <form method="get" class="filter-form" aria-label="Filter tanggal benchmark">
            <input type="hidden" name="action" value="show" />
            <input type="hidden" name="id" value="<?= (int)$game['id']; ?>" />
            <label class="filter-field">
              <span class="label">Dari</span>
              <input type="date" name="from" value="<?= htmlspecialchars($from ?? ''); ?>" />
            </label>
            <label class="filter-field">
              <span class="label">Sampai</span>
              <input type="date" name="to" value="<?= htmlspecialchars($to ?? ''); ?>" />
            </label>
            <button class="btn small" type="submit">Filter</button>
            <?php if (!empty($from) || !empty($to)): ?><a class="reset-link" href="index.php?action=show&id=<?= (int)$game['id']; ?>">Reset</a><?php endif; ?>
          </form>
        </div>
        <div class="panel-head-row">
          <h4 class="panel-sub">Benchmark Input</h4>
          <button class="btn small" type="button" data-open-modal="benchModal">+ Benchmark</button>
        </div>
      </div>
      <?php if (empty($benchmarks)): ?>
        <div class="empty compact">Belum ada benchmark.</div>
      <?php else: ?>
        <div class="chart-panel">
          <div class="chart-head"><h5>Grafik FPS</h5></div>
          <div class="sort-controls" aria-label="Urutkan benchmark">
            <button type="button" class="btn small active" data-sort="date-desc" aria-pressed="true">Tanggal ↓</button>
            <button type="button" class="btn small" data-sort="date-asc" aria-pressed="false">Tanggal ↑</button>
            <button type="button" class="btn small" data-sort="avg-desc" aria-pressed="false">Avg FPS ↓</button>
            <button type="button" class="btn small" data-sort="avg-asc" aria-pressed="false">Avg FPS ↑</button>
          </div>
          <div id="fpsChartWrapper" class="chart-wrapper"><canvas id="fpsChart"></canvas></div>
        </div>
  <ul class="bench-list" id="benchList" role="list" data-best-id="<?= $bestBenchmarkId ? (int)$bestBenchmarkId : ''; ?>" data-source='<?= json_encode($benchmarks, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>'>
          <?php foreach ($benchmarks as $b): ?>
            <li class="bench-item <?= ($bestBenchmarkId && $b['id']==$bestBenchmarkId)?'is-best':''; ?>">
              <?php if ($bestBenchmarkId && $b['id']==$bestBenchmarkId): ?><span class="best-badge" aria-label="Benchmark terbaik">BEST</span><?php endif; ?>
              <div class="bench-main">
                <strong class="bench-date"><?= htmlspecialchars($b['benchmark_date'] ?: 'Tanggal?'); ?></strong>
                <div class="bench-meta">Driver: <?= htmlspecialchars($b['driver_version'] ?: '-'); ?> • Avg: <?= htmlspecialchars($b['avg_fps'] ?: '-'); ?> FPS • 1%: <?= htmlspecialchars($b['low_1_percent_fps'] ?: '-'); ?> • CPU <?= htmlspecialchars($b['cpu_temp'] ?: '-'); ?>°C • GPU <?= htmlspecialchars($b['gpu_temp'] ?: '-'); ?>°C</div>
                <?php if ($b['notes']): ?><p class="bench-notes"><?= nl2br(htmlspecialchars($b['notes'])); ?></p><?php endif; ?>
              </div>
              <div class="item-actions">
                <a class="btn outline small" href="index.php?action=bench_edit&id=<?= $b['id']; ?>">Edit</a>
                <a class="btn danger small" href="index.php?action=bench_delete&id=<?= $b['id']; ?>" onclick="return confirm('Hapus benchmark ini?');">Hapus</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </main>
</div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    (function(){
      const rawData = <?php
        // Prepare chronological (oldest first)
        $chron = $benchmarks;
        usort($chron, function($a,$b){
          return strcmp(($a['benchmark_date'] ?? ''), ($b['benchmark_date'] ?? '')) ?: strcmp($a['id'], $b['id']);
        });
        echo json_encode(array_map(function($b){
          return [
            'date' => $b['benchmark_date'] ?: null,
            'avg' => $b['avg_fps'] !== null ? (float)$b['avg_fps'] : null,
            'low1' => $b['low_1_percent_fps'] !== null ? (float)$b['low_1_percent_fps'] : null,
          ];
        }, $chron)); ?>;
      if (!rawData.length) return;
      let currentData = [...rawData];
      function datasetFrom(list){
        return {
          labels: list.map(d => d.date || '?'),
          avg: list.map(d => d.avg),
          low1: list.map(d => d.low1)
        };
      }
      let { labels, avg, low1 } = datasetFrom(currentData);
      const ctx = document.getElementById('fpsChart');
      if (!ctx) return;
      // Destroy previous instance if navigating via PJAX / partial reload etc.
      if (window.fpsChart) { try { window.fpsChart.destroy(); } catch(e){} }
      function chartColors(){
        const cs = getComputedStyle(document.documentElement);
        return {
          avgLine: cs.getPropertyValue('--chart-avg-line').trim() || '#4299E1',
            avgFill: cs.getPropertyValue('--chart-avg-fill').trim() || 'rgba(66,153,225,.15)',
            lowLine: cs.getPropertyValue('--chart-low-line').trim() || '#F59E0B',
            lowFill: cs.getPropertyValue('--chart-low-fill').trim() || 'rgba(245,158,11,.15)',
            text: cs.getPropertyValue('--text').trim() || '#E2E8F0',
            grid: cs.getPropertyValue('--border').trim() || 'rgba(255,255,255,0.08)',
            panel: cs.getPropertyValue('--panel-soft').trim() || '#1E293B'
        };
      }
      function buildChart(){
        const c = chartColors();
        return new Chart(ctx, {
          type: 'line',
          data: { labels, datasets:[
            { label:'Avg FPS', data: avg, spanGaps:true, borderColor:c.avgLine, backgroundColor:c.avgFill, tension:.25, pointRadius:3 },
            { label:'1% Low', data: low1, spanGaps:true, borderColor:c.lowLine, backgroundColor:c.lowFill, tension:.25, pointRadius:3 }
          ]},
          options:{
            responsive:true,
            maintainAspectRatio:false,
            animation:{ duration:400 },
            plugins:{
              legend:{ labels:{ color:c.text, font:{ size:10 } } },
              tooltip:{ backgroundColor:c.panel, titleColor:c.text, bodyColor:c.text }
            },
            scales:{
              x:{ ticks:{ color:c.text, maxRotation:50, minRotation:30, font:{ size:10 } }, grid:{ color:'rgba(255,255,255,0.05)' } },
              y:{ ticks:{ color:c.text, font:{ size:10 } }, grid:{ color:'rgba(255,255,255,0.08)' } }
            }
          }
        });
      }
      window.fpsChart = buildChart();
      // Rebuild chart when theme toggles
      document.getElementById('themeToggle')?.addEventListener('click', ()=>{
        setTimeout(()=>{ try { window.fpsChart.destroy(); } catch(e){} window.fpsChart = buildChart(); }, 30);
      });
      // Sorting logic
      const listEl = document.getElementById('benchList');
      const sortButtons = document.querySelectorAll('.sort-controls [data-sort]');
      function renderList(items){
        const bestId = listEl.getAttribute('data-best-id');
        listEl.innerHTML = items.map(b => {
          const notes = b.notes ? `<p class=\"bench-notes\">${b.notes.replace(/</g,'&lt;').replace(/\n/g,'<br>')}</p>` : '';
          const isBest = bestId && String(b.id) === String(bestId);
          const badge = isBest ? '<span class=\"best-badge\" aria-label=\"Benchmark terbaik\">BEST</span>' : '';
          return `<li class=\"bench-item ${isBest?'is-best':''}\">${badge}<div class=\"bench-main\"><strong class=\"bench-date\">${b.benchmark_date || 'Tanggal?'}<\/strong><div class=\"bench-meta\">Driver: ${b.driver_version || '-'} • Avg: ${(b.avg_fps ?? '-')} FPS • 1%: ${(b.low_1_percent_fps ?? '-')} • CPU ${(b.cpu_temp ?? '-')}°C • GPU ${(b.gpu_temp ?? '-')}°C<\/div>${notes}<\/div><div class=\"item-actions\"><a class=\"btn outline small\" href=\"index.php?action=bench_edit&id=${b.id}\">Edit<\/a><a class=\"btn danger small\" href=\"index.php?action=bench_delete&id=${b.id}\" onclick=\"return confirm('Hapus benchmark ini?');\">Hapus<\/a><\/div><\/li>`;
        }).join('');
      }
  const live = document.createElement('div');
  live.setAttribute('aria-live','polite');
  live.className='sr-only';
  document.body.appendChild(live);
  function applySort(mode){
        try {
          const full = JSON.parse(listEl.getAttribute('data-source'));
          switch(mode){
            case 'date-asc': full.sort((a,b)=>(a.benchmark_date||'').localeCompare(b.benchmark_date||'')); break;
            case 'date-desc': full.sort((a,b)=>(b.benchmark_date||'').localeCompare(a.benchmark_date||'')); break;
            case 'avg-asc': full.sort((a,b)=> (a.avg_fps??-Infinity) - (b.avg_fps??-Infinity)); break;
            case 'avg-desc': full.sort((a,b)=> (b.avg_fps??-Infinity) - (a.avg_fps??-Infinity)); break;
          }
          renderList(full);
          const labelMap = { 'date-asc':'Tanggal naik', 'date-desc':'Tanggal turun', 'avg-asc':'Avg FPS naik', 'avg-desc':'Avg FPS turun' };
          live.textContent = 'Diurutkan: ' + (labelMap[mode]||mode) + '. Total ' + full.length + ' benchmark.';
          const chartList = full.map(b=>({ date:b.benchmark_date||null, avg: b.avg_fps!==null? parseFloat(b.avg_fps):null, low1: b.low_1_percent_fps!==null? parseFloat(b.low_1_percent_fps):null }));
          const ds = datasetFrom(chartList);
          labels = ds.labels; avg = ds.avg; low1 = ds.low1;
          try { window.fpsChart.destroy(); } catch(e){}
          window.fpsChart = buildChart();
        } catch(e){ console.warn('Sort error', e); }
      }
      sortButtons.forEach((btn, idx)=>{
        btn.addEventListener('click', ()=>{
          sortButtons.forEach(b=> { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
          btn.classList.add('active');
          btn.setAttribute('aria-pressed','true');
          applySort(btn.getAttribute('data-sort'));
        });
        btn.addEventListener('keydown', (e)=>{
          if(['ArrowRight','ArrowDown','ArrowLeft','ArrowUp','Home','End'].includes(e.key)){
            e.preventDefault();
            let nextIndex = idx;
            if(e.key==='ArrowRight' || e.key==='ArrowDown') nextIndex = (idx+1)%sortButtons.length;
            if(e.key==='ArrowLeft' || e.key==='ArrowUp') nextIndex = (idx-1+sortButtons.length)%sortButtons.length;
            if(e.key==='Home') nextIndex = 0;
            if(e.key==='End') nextIndex = sortButtons.length-1;
            sortButtons[nextIndex].focus();
          }
        });
      });
    })();
  </script>
<!-- Modals -->
<div id="configModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(2px); z-index:1000; align-items:center; justify-content:center; padding:1.5rem;">
  <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="configModalTitle" style="background:var(--panel-soft); border:1px solid var(--border); padding:1.25rem 1.25rem 1.5rem; width:100%; max-width:640px; border-radius:12px; position:relative;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 id="configModalTitle" style="margin:0; font-size:1rem;">Tambah Konfigurasi</h3>
      <button type="button" data-close-modal style="background:none; border:none; color:#94A3B8; font-size:1.2rem; cursor:pointer;">×</button>
    </div>
    <form method="post" action="index.php?action=config_store" style="display:grid; gap:.75rem; grid-template-columns:repeat(auto-fit,minmax(150px,1fr));">
      <input type="hidden" name="game_id" value="<?= $game['id']; ?>" />
      <input type="text" name="profile_name" placeholder="Nama Profil *" required autofocus />
      <input type="number" name="mouse_dpi" placeholder="DPI" />
      <input type="text" name="in_game_sensitivity" placeholder="Sensitivitas" />
      <input type="text" name="crosshair_code" placeholder="Crosshair Code" />
  <textarea name="graphics_notes" placeholder="Catatan Grafis" style="grid-column:1/-1; min-height:90px; background:var(--input-bg); border:1px solid var(--input-border); color:var(--text); padding:.65rem .75rem; border-radius:8px; font-size:.8rem;"></textarea>
      <div style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:.5rem;">
  <button type="button" data-close-modal class="btn outline" style="background:var(--panel-soft);">Batal</button>
        <button class="btn" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>
<div id="benchModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(2px); z-index:1000; align-items:center; justify-content:center; padding:1.5rem;">
  <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="benchModalTitle" style="background:var(--panel-soft); border:1px solid var(--border); padding:1.25rem 1.25rem 1.5rem; width:100%; max-width:760px; border-radius:12px; position:relative;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 id="benchModalTitle" style="margin:0; font-size:1rem;">Tambah Benchmark</h3>
      <button type="button" data-close-modal style="background:none; border:none; color:#94A3B8; font-size:1.2rem; cursor:pointer;">×</button>
    </div>
    <form method="post" action="index.php?action=bench_store" style="display:grid; gap:.75rem; grid-template-columns:repeat(auto-fit,minmax(150px,1fr));">
      <input type="hidden" name="game_id" value="<?= $game['id']; ?>" />
      <input type="date" name="benchmark_date" />
      <input type="text" name="driver_version" placeholder="Driver" />
      <input type="number" name="avg_fps" placeholder="Avg FPS" />
      <input type="number" name="low_1_percent_fps" placeholder="1% Low" />
      <input type="number" name="cpu_temp" placeholder="CPU °C" />
      <input type="number" name="gpu_temp" placeholder="GPU °C" />
  <textarea name="notes" placeholder="Catatan" style="grid-column:1/-1; min-height:90px; background:var(--input-bg); border:1px solid var(--input-border); color:var(--text); padding:.65rem .75rem; border-radius:8px; font-size:.8rem;"></textarea>
      <div style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:.5rem;">
  <button type="button" data-close-modal class="btn outline" style="background:var(--panel-soft);">Batal</button>
        <button class="btn" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>
<script>
// Modal handling (accessible-ish)
(function(){
  const openers = document.querySelectorAll('[data-open-modal]');
  const body = document.body;
  let lastFocused = null;
  function trapFocus(modal){
    const focusables = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if(!focusables.length) return;
    const first = focusables[0];
    const last = focusables[focusables.length -1];
    function key(e){
      if(e.key === 'Escape'){ close(modal); }
      if(e.key === 'Tab'){
        if(e.shiftKey && document.activeElement === first){ e.preventDefault(); last.focus(); }
        else if(!e.shiftKey && document.activeElement === last){ e.preventDefault(); first.focus(); }
      }
    }
    modal.addEventListener('keydown', key);
  }
  function open(id){
    const modal = document.getElementById(id);
    if(!modal) return;
    lastFocused = document.activeElement;
    modal.style.display='flex';
    body.style.overflow='hidden';
    const firstInput = modal.querySelector('input,textarea,select,button');
    if(firstInput) firstInput.focus();
    trapFocus(modal);
    modal.addEventListener('click', (e)=>{ if(e.target === modal) close(modal); });
  }
  function close(modal){
    modal.style.display='none';
    body.style.overflow='';
    if(lastFocused) lastFocused.focus();
  }
  document.querySelectorAll('[data-close-modal]').forEach(btn=>{
    btn.addEventListener('click', ()=> close(btn.closest('.modal-overlay')));
  });
  openers.forEach(btn=>{
    btn.addEventListener('click', ()=> open(btn.getAttribute('data-open-modal')));
  });
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
