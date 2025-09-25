<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="error" role="alert"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>

<main id="content" tabindex="-1" style="outline:none; display:flex; flex-direction:column; gap:1.5rem;">
  <section aria-labelledby="libraryHeading" style="display:flex; flex-direction:column; gap:1.25rem; margin-bottom:.5rem;">
    <div style="display:flex; flex-wrap:wrap; gap:1.5rem; align-items:flex-end;">
      <div style="flex:1 1 320px; min-width:260px;">
        <h1 id="libraryHeading" style="margin:.2rem 0 .6rem; font-size:clamp(1.4rem,2.2vw,1.75rem); letter-spacing:.5px;">Library Game Anda</h1>
        <p style="margin:0; font-size:.8rem; line-height:1.5; opacity:.85; max-width:640px;">Kelola game, simpan konfigurasi performa, dan catat hasil benchmark untuk memantau peningkatan FPS dari waktu ke waktu. Gunakan pencarian & filter untuk menemukan judul lebih cepat.</p>
      </div>
      <form method="get" action="index.php" role="search" aria-label="Pencarian game" onsubmit="return false;" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:center; background:var(--panel); padding:.75rem .9rem; border:1px solid var(--border); border-radius:14px; box-shadow:0 2px 4px -2px rgba(0,0,0,.4), 0 4px 16px -6px rgba(0,0,0,.25);">
        <input type="hidden" name="action" value="index" />
        <div style="position:relative; flex:1; min-width:240px;">
          <input id="searchInput" name="q" value="<?= htmlspecialchars($query ?? ''); ?>" placeholder="Ketik untuk mencari..." autocomplete="off" aria-describedby="searchHint" style="width:100%; background:var(--input-bg); border:1px solid var(--input-border); padding:.6rem .9rem .6rem 2.05rem; border-radius:8px; color:var(--text); font-size:.85rem;" />
          <span aria-hidden="true" style="position:absolute; left:.7rem; top:50%; transform:translateY(-50%); opacity:.5; font-size:.85rem;">🔍</span>
          <div id="searchHint" style="position:absolute; left:0; top:100%; margin-top:.25rem; font-size:.55rem; opacity:.5; letter-spacing:.5px;">Realtime pencarian. Tekan Esc untuk kosongkan.</div>
        </div>
        <button type="reset" id="resetSearch" class="btn outline" style="font-size:.65rem; padding:.45rem .7rem; display:none;">Reset</button>
        <a class="btn" href="index.php?action=create" style="white-space:nowrap;">+ Game</a>
      </form>
    </div>
    <?php if (!empty($stats)): ?>
      <div class="stat-cards" style="display:grid; gap:.9rem; grid-template-columns:repeat(auto-fit,minmax(150px,1fr));">
        <div class="stat" style="--accent:#6366F1; background:var(--panel); border:1px solid var(--border); border-radius:14px; padding:.85rem .95rem; position:relative; overflow:hidden;">
          <p style="margin:0 0 .4rem; font-size:.6rem; letter-spacing:1px; text-transform:uppercase; opacity:.65; display:flex; justify-content:space-between; align-items:center;">Total Game <span style="font-size:.7rem; opacity:.4;">🎮</span></p>
          <strong style="font-size:1.35rem; font-weight:600;"><?= (int)$stats['total_games']; ?></strong>
          <span aria-hidden="true" style="content:''; position:absolute; inset:auto -10% -40% -10%; height:120%; background:linear-gradient(120deg,var(--accent)11%,transparent 75%); opacity:.07; transform:rotate(-8deg);"></span>
        </div>
        <div class="stat" style="--accent:#0EA5E9; background:var(--panel); border:1px solid var(--border); border-radius:14px; padding:.85rem .95rem; position:relative; overflow:hidden;">
          <p style="margin:0 0 .4rem; font-size:.6rem; letter-spacing:1px; text-transform:uppercase; opacity:.65; display:flex; justify-content:space-between; align-items:center;">Konfigurasi <span style="font-size:.7rem; opacity:.45;">⚙️</span></p>
          <strong style="font-size:1.35rem; font-weight:600;"><?= (int)$stats['total_configs']; ?></strong>
          <span aria-hidden="true" style="content:''; position:absolute; inset:auto -10% -40% -10%; height:120%; background:linear-gradient(120deg,var(--accent)11%,transparent 75%); opacity:.07; transform:rotate(-8deg);"></span>
        </div>
        <div class="stat" style="--accent:#10B981; background:var(--panel); border:1px solid var(--border); border-radius:14px; padding:.85rem .95rem; position:relative; overflow:hidden;">
          <p style="margin:0 0 .4rem; font-size:.6rem; letter-spacing:1px; text-transform:uppercase; opacity:.65; display:flex; justify-content:space-between; align-items:center;">Benchmark <span style="font-size:.7rem; opacity:.45;">📊</span></p>
          <strong style="font-size:1.35rem; font-weight:600;"><?= (int)$stats['total_benchmarks']; ?></strong>
          <span aria-hidden="true" style="content:''; position:absolute; inset:auto -10% -40% -10%; height:120%; background:linear-gradient(120deg,var(--accent)11%,transparent 75%); opacity:.07; transform:rotate(-8deg);"></span>
        </div>
      </div>
    <?php endif; ?>
  </section>

  <section aria-labelledby="listHeading" style="display:flex; flex-direction:column; gap:.75rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;">
      <h2 id="listHeading" style="margin:0; font-size:1rem; letter-spacing:.5px; font-weight:600; display:flex; align-items:center; gap:.5rem;">Daftar Game
        <?php if(!empty($query)): ?><span style="font-size:.6rem; font-weight:500; background:#1E293B; border:1px solid #334155; padding:.25rem .55rem; border-radius:24px; letter-spacing:.5px;">Hasil pencarian</span><?php endif; ?>
      </h2>
      <div class="chips" aria-label="Filter cepat" role="group" style="display:flex; gap:.4rem; flex-wrap:wrap; font-size:.55rem;">
        <button type="button" class="chip-filter" data-filter="has-bench" aria-pressed="false" style="--on:#0EA5E9; background:#1E293B; border:1px solid #334155; padding:.45rem .85rem; border-radius:24px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Ada Benchmark</button>
        <button type="button" class="chip-filter" data-filter="no-bench" aria-pressed="false" style="--on:#64748B; background:#1E293B; border:1px solid #334155; padding:.45rem .85rem; border-radius:24px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Belum Benchmark</button>
        <button type="button" class="chip-filter" data-filter="has-config" aria-pressed="false" style="--on:#6366F1; background:#1E293B; border:1px solid #334155; padding:.45rem .85rem; border-radius:24px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Ada Config</button>
        <button type="button" class="chip-filter" data-filter="no-config" aria-pressed="false" style="--on:#475569; background:#1E293B; border:1px solid #334155; padding:.45rem .85rem; border-radius:24px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Belum Config</button>
        <button type="button" class="chip-filter-reset" data-filter-reset aria-pressed="false" style="background:#0F172A; border:1px dashed #334155; padding:.45rem .85rem; border-radius:24px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Reset Filter</button>
      </div>
  </div>

<?php if (count($games)===0): ?>
  <div class="empty" style="padding:2.5rem;">
    <?php if (!empty($query)): ?>Tidak ada hasil untuk "<?= htmlspecialchars($query); ?>". Coba kata kunci lain atau <a href="index.php" style="color:#4299E1;">reset pencarian</a>.<?php else: ?>Belum ada game. Mulai dengan klik <strong>+ Game</strong> untuk menambahkan judul pertama Anda.<?php endif; ?>
  </div>
<?php else: ?>
  <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin:.25rem 0 .35rem; font-size:.65rem;">
    <div style="display:flex; align-items:center; gap:.55rem; flex-wrap:wrap;">
      <span style="opacity:.6; letter-spacing:.5px; font-weight:600;">Sortir:</span>
      <div role="radiogroup" aria-label="Opsi sortir" style="display:flex; gap:.4rem; flex-wrap:wrap;">
        <button type="button" class="chip-sort" data-sort="name" aria-pressed="true" style="background:#1E293B; border:1px solid #334155; padding:.35rem .75rem; border-radius:20px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Nama A-Z</button>
        <button type="button" class="chip-sort" data-sort="configs" aria-pressed="false" style="background:#1E293B; border:1px solid #334155; padding:.35rem .75rem; border-radius:20px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Konfigurasi Terbanyak</button>
        <button type="button" class="chip-sort" data-sort="bench" aria-pressed="false" style="background:#1E293B; border:1px solid #334155; padding:.35rem .75rem; border-radius:20px; cursor:pointer; font-weight:600; letter-spacing:.5px;">Benchmark Terbanyak</button>
      </div>
    </div>
    <div style="flex:1; min-width:200px; display:flex; align-items:center; gap:.5rem; font-size:.6rem; opacity:.65;">
      <span style="white-space:nowrap;">Menampilkan: <strong id="countVisible" style="font-weight:600; opacity:.85; font-size:.6rem;">-</strong> / <span id="countTotal" style="font-weight:600; font-size:.6rem;">-</span> game</span>
    </div>
    <span id="statusLive" aria-live="polite" style="position:absolute; left:-9999px;">Siap</span>
    <span id="sortStatus" aria-live="polite" style="position:absolute; left:-9999px;">Urutan: Nama A-Z</span>
  </div>
  <div class="grid" style="--card-width:190px;">
    <?php foreach ($games as $g): ?>
      <div class="card" data-name="<?= htmlspecialchars(mb_strtolower($g['name'])); ?>" data-configs="<?= (int)$g['config_count']; ?>" data-bench="<?= (int)$g['bench_count']; ?>" style="transition:.25s background, .25s transform; position:relative; overflow:hidden;">
        <a href="index.php?action=show&id=<?= $g['id']; ?>" style="text-decoration:none; color:inherit; display:block;">
          <?php if ($g['cover_image_url']): ?>
            <img src="<?= htmlspecialchars($g['cover_image_url']); ?>" alt="Cover game <?= htmlspecialchars($g['name']); ?>" loading="lazy" decoding="async" style="transition:opacity .4s;" />
          <?php else: ?>
            <img src="https://placehold.co/300x400/1E293B/94A3B8?text=No+Cover" alt="Tidak ada cover" loading="lazy" decoding="async" />
          <?php endif; ?>
          <h3 style="display:flex; flex-direction:column; gap:.45rem;">
            <span style="font-size:.9rem; line-height:1.2; max-height:2.4em; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; line-clamp:2;">
              <?= htmlspecialchars($g['name']); ?>
            </span>
            <span style="display:flex; gap:.4rem; font-size:.55rem; font-weight:600; letter-spacing:.5px;">
              <span style="background:#1E293B; padding:.25rem .55rem; border-radius:20px; border:1px solid #334155;" aria-label="Jumlah konfigurasi">CFG <?= (int)$g['config_count']; ?></span>
              <span style="background:#1E293B; padding:.25rem .55rem; border-radius:20px; border:1px solid #334155;" aria-label="Jumlah benchmark">BM <?= (int)$g['bench_count']; ?></span>
            </span>
          </h3>
        </a>
        <div class="actions" style="opacity:0; transform:translateY(4px); transition:.25s;">
          <a class="btn outline" href="index.php?action=edit&id=<?= $g['id']; ?>">Edit</a>
          <a class="btn danger" href="index.php?action=delete&id=<?= $g['id']; ?>" onclick="return confirm('Hapus game dan semua data terkait?');">Hapus</a>
        </div>
        <script>/* noop to keep php closing tag below inside loop */</script>
        <style>
        </style>
      </div>
    <?php endforeach; ?>
  </div>
  <script>
    // Hover / focus enhancement for action buttons
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('mouseenter', () => { const act = card.querySelector('.actions'); if(act){ act.style.opacity='1'; act.style.transform='translateY(0)'; } card.style.background='var(--panel-hover)'; card.style.transform='translateY(-2px)'; });
      card.addEventListener('mouseleave', () => { const act = card.querySelector('.actions'); if(act){ act.style.opacity='0'; act.style.transform='translateY(4px)'; } card.style.background='var(--panel)'; card.style.transform=''; });
      card.querySelector('a').addEventListener('focus', () => { const act = card.querySelector('.actions'); if(act){ act.style.opacity='1'; act.style.transform='translateY(0)'; } });
      card.querySelector('a').addEventListener('blur', () => { const act = card.querySelector('.actions'); if(act){ act.style.opacity='0'; act.style.transform='translateY(4px)'; } });
    });
    // Sorting
  const sortButtons = document.querySelectorAll('.chip-sort');
  const filterButtons = document.querySelectorAll('.chip-filter');
  const resetFilterBtn = document.querySelector('.chip-filter-reset');
  const grid = document.querySelector('.grid');
  const statusLive = document.getElementById('statusLive');
  const countVisibleEl = document.getElementById('countVisible');
  const countTotalEl = document.getElementById('countTotal');
  const searchInput = document.getElementById('searchInput');
  const resetSearchBtn = document.getElementById('resetSearch');
  const totalCards = grid ? grid.querySelectorAll('.card').length : 0;
  if(countTotalEl) countTotalEl.textContent = totalCards;
  let currentSort = localStorage.getItem('gs_sort') || 'name';
  let storedFilters = localStorage.getItem('gs_filters');
  /** Active filters stored as Set */
  let activeFilters = new Set(storedFilters ? storedFilters.split(',').filter(Boolean): []);
  let searchQuery = localStorage.getItem('gs_search') || '';
  if(searchQuery) { searchInput.value = searchQuery; resetSearchBtn.style.display='inline-flex'; }
    const applySort = (type)=>{
      const cards=[...grid.querySelectorAll('.card')];
      cards.sort((a,b)=>{
        if(type==='name') return a.dataset.name.localeCompare(b.dataset.name);
        if(type==='configs') return parseInt(b.dataset.configs)-parseInt(a.dataset.configs) || a.dataset.name.localeCompare(b.dataset.name);
        if(type==='bench') return parseInt(b.dataset.bench)-parseInt(a.dataset.bench) || a.dataset.name.localeCompare(b.dataset.name);
        return 0;
      });
      cards.forEach(c=>grid.appendChild(c));
    };
    function cardPassFilters(card){
      if(activeFilters.size===0) return true;
      const cfg = parseInt(card.dataset.configs); const bm = parseInt(card.dataset.bench);
      // each filter acts as AND across the set
      for(const f of activeFilters){
        if(f==='has-bench' && !(bm>0)) return false;
        if(f==='no-bench' && !(bm===0)) return false;
        if(f==='has-config' && !(cfg>0)) return false;
        if(f==='no-config' && !(cfg===0)) return false;
      }
      // mutually exclusive conflict auto-resolve (if both present remove the older one -> handled on toggle)
      return true;
    }
    function highlight(text, q){
      if(!q) return text;
      const re = new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','ig');
      return text.replace(re,'<mark style="background:#334155; color:inherit; padding:0 2px; border-radius:3px;">$1</mark>');
    }
    function applyAll(){
      let visible=0; const q = searchQuery.trim().toLowerCase();
      grid.querySelectorAll('.card').forEach(card=>{
        const name = card.dataset.name;
        let show = cardPassFilters(card);
        if(show && q){ show = name.includes(q); }
        card.style.display = show? '' : 'none';
        const titleEl = card.querySelector('h3 span:first-child');
        if(titleEl){
          const raw = titleEl.getAttribute('data-raw') || titleEl.textContent.trim();
          if(!titleEl.getAttribute('data-raw')) titleEl.setAttribute('data-raw', raw);
          titleEl.innerHTML = highlight(raw, q);
        }
        if(show) visible++;
      });
      if(countVisibleEl) countVisibleEl.textContent = visible;
      if(statusLive){
        const filtersReadable = activeFilters.size? [...activeFilters].map(f=>{
          if(f==='has-bench') return 'ada benchmark';
          if(f==='no-bench') return 'belum benchmark';
          if(f==='has-config') return 'ada config';
          if(f==='no-config') return 'belum config';
          return f;
        }).join(', ') : 'tanpa filter';
        statusLive.textContent = 'Filter: '+filtersReadable+' | Pencarian: ' + (q || 'kosong') + ' | Hasil: ' + visible + ' dari ' + totalCards;
      }
    }
    // Init state from localStorage
    function initState(){
      sortButtons.forEach(b=>b.setAttribute('aria-pressed', b.dataset.sort===currentSort ? 'true':'false'));
      filterButtons.forEach(b=>b.setAttribute('aria-pressed', activeFilters.has(b.dataset.filter)?'true':'false'));
      applySort(currentSort);
      applyAll();
      const sortStatus=document.getElementById('sortStatus');
      if(sortStatus){
        let label='Nama A-Z';
        if(currentSort==='configs') label='Konfigurasi terbanyak';
        if(currentSort==='bench') label='Benchmark terbanyak';
        sortStatus.textContent='Urutan: '+label;
      }
    }
    initState();
    sortButtons.forEach(btn=>btn.addEventListener('click',()=>{
      currentSort = btn.dataset.sort;
      localStorage.setItem('gs_sort', currentSort);
      sortButtons.forEach(b=>b.setAttribute('aria-pressed','false'));
      btn.setAttribute('aria-pressed','true');
      applySort(currentSort);
      const status = document.getElementById('sortStatus');
      if(status){
        let label = 'Nama A-Z';
        if(currentSort==='configs') label='Konfigurasi terbanyak';
        if(currentSort==='bench') label='Benchmark terbanyak';
        status.textContent = 'Urutan: ' + label;
      }
    }));
    filterButtons.forEach(btn=>btn.addEventListener('click',()=>{
      const f = btn.dataset.filter;
      if(activeFilters.has(f)) activeFilters.delete(f); else {
        // resolve mutually exclusive pairs
        if(f==='has-bench' && activeFilters.has('no-bench')) activeFilters.delete('no-bench');
        if(f==='no-bench' && activeFilters.has('has-bench')) activeFilters.delete('has-bench');
        if(f==='has-config' && activeFilters.has('no-config')) activeFilters.delete('no-config');
        if(f==='no-config' && activeFilters.has('has-config')) activeFilters.delete('has-config');
        activeFilters.add(f);
      }
      filterButtons.forEach(b=>b.setAttribute('aria-pressed', activeFilters.has(b.dataset.filter)?'true':'false'));
      localStorage.setItem('gs_filters', [...activeFilters].join(','));
      applyAll();
    }));
    resetFilterBtn?.addEventListener('click', ()=>{
      activeFilters.clear();
      filterButtons.forEach(b=>b.setAttribute('aria-pressed','false'));
      localStorage.removeItem('gs_filters');
      applyAll();
      resetFilterBtn.setAttribute('aria-pressed','true');
      setTimeout(()=>resetFilterBtn.setAttribute('aria-pressed','false'),300);
    });
    // Reactive search
    let searchTimer=null;
    searchInput.addEventListener('input', ()=>{
      searchQuery = searchInput.value;
      if(searchQuery) resetSearchBtn.style.display='inline-flex'; else resetSearchBtn.style.display='none';
      localStorage.setItem('gs_search', searchQuery);
      clearTimeout(searchTimer);
      searchTimer=setTimeout(applyAll, 180);
    });
    searchInput.addEventListener('keydown', e=>{
      if(e.key==='Escape'){ searchInput.value=''; searchQuery=''; localStorage.removeItem('gs_search'); resetSearchBtn.style.display='none'; applyAll(); }
    });
    resetSearchBtn.addEventListener('click', ()=>{ searchInput.value=''; searchQuery=''; localStorage.removeItem('gs_search'); resetSearchBtn.style.display='none'; applyAll(); });
    if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){
      document.querySelectorAll('.card').forEach(c=>{ c.style.transition='none'; });
    }
  </script>
<?php endif; ?>
  </section>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
