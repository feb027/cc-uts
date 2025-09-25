<?php require __DIR__ . '/../layouts/header.php'; ?>
<a href="index.php?action=show&id=<?= (int)$game['id']; ?>" class="btn outline small" style="margin-bottom:1rem;">← Kembali</a>
<div class="form-layout">
  <form method="post" action="index.php?action=bench_update" class="panel-form" id="benchEditForm" novalidate>
    <div class="panel-head">
      <h2 class="panel-title" style="font-size:1.05rem;">Edit Benchmark</h2>
      <span class="panel-intro" style="margin:0;">Perbarui data benchmark untuk <strong><?= htmlspecialchars($game['name']); ?></strong>. Minimal isi salah satu nilai FPS.</span>
    </div>
    <input type="hidden" name="id" value="<?= (int)$bench['id']; ?>" />
    <div class="form-grid" style="--cols:2;">
      <div class="field">
        <label for="benchmark_date">Tanggal</label>
        <input type="date" id="benchmark_date" name="benchmark_date" value="<?= htmlspecialchars($bench['benchmark_date']); ?>" />
      </div>
      <div class="field">
        <label for="driver_version">Driver</label>
        <input type="text" id="driver_version" name="driver_version" value="<?= htmlspecialchars($bench['driver_version']); ?>" />
      </div>
      <div class="field">
        <label for="avg_fps">Avg FPS</label>
        <input type="number" id="avg_fps" name="avg_fps" value="<?= htmlspecialchars($bench['avg_fps']); ?>" />
        <div class="field-error" id="err_fps"></div>
      </div>
      <div class="field">
        <label for="low_1_percent_fps">1% Low</label>
        <input type="number" id="low_1_percent_fps" name="low_1_percent_fps" value="<?= htmlspecialchars($bench['low_1_percent_fps']); ?>" />
      </div>
      <div class="field">
        <label for="cpu_temp">CPU °C</label>
        <input type="number" id="cpu_temp" name="cpu_temp" value="<?= htmlspecialchars($bench['cpu_temp']); ?>" />
      </div>
      <div class="field">
        <label for="gpu_temp">GPU °C</label>
        <input type="number" id="gpu_temp" name="gpu_temp" value="<?= htmlspecialchars($bench['gpu_temp']); ?>" />
      </div>
      <div class="field full">
        <label for="notes">Catatan</label>
        <textarea id="notes" name="notes" rows="6" placeholder="Observasi, perubahan setting, faktor luar..."><?= htmlspecialchars($bench['notes']); ?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button class="btn" type="submit" id="benchSaveBtn">Simpan</button>
      <a href="index.php?action=show&id=<?= (int)$bench['game_id']; ?>" class="btn outline">Batal</a>
      <a href="index.php?action=bench_delete&id=<?= (int)$bench['id']; ?>" class="btn danger" onclick="return confirm('Hapus benchmark ini?');">Hapus</a>
    </div>
    <p style="font-size:.55rem; opacity:.6; margin:1rem 0 0;">Shortcut: <strong>Ctrl+Enter</strong> untuk simpan cepat.</p>
  </form>
  <aside class="panel-side">
    <strong style="font-size:.75rem; letter-spacing:.5px;">Tips</strong>
    <ul style="margin:0; padding-left:1rem; font-size:.65rem; line-height:1.4; display:flex; flex-direction:column; gap:.4rem;">
      <li>Isi tanggal agar grafik kronologis rapi.</li>
      <li>Cukup isi Avg atau 1% Low jika hanya satu data tersedia.</li>
      <li>Catat suhu untuk analisa stabilitas performa.</li>
    </ul>
  </aside>
</div>
<script>
(function(){
  const form = document.getElementById('benchEditForm');
  const avg = document.getElementById('avg_fps');
  const low1 = document.getElementById('low_1_percent_fps');
  const err = document.getElementById('err_fps');
  const saveBtn = document.getElementById('benchSaveBtn');
  function setLoading(on){ if(on){ saveBtn.classList.add('loading'); saveBtn.disabled=true; saveBtn.innerHTML='<span class="spinner"></span> Menyimpan...'; } else { saveBtn.classList.remove('loading'); saveBtn.disabled=false; saveBtn.textContent='Simpan'; } }
  form.addEventListener('submit', (e)=>{
    err.textContent='';
    const hasAvg = avg.value.trim()!=='';
    const hasLow = low1.value.trim()!=='';
    if(!hasAvg && !hasLow){
      e.preventDefault();
      err.textContent='Isi minimal Avg FPS atau 1% Low';
      avg.focus();
      return;
    }
    setLoading(true);
  });
  document.addEventListener('keydown', (e)=>{ if((e.ctrlKey||e.metaKey) && e.key==='Enter'){ form.requestSubmit(); } });
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
