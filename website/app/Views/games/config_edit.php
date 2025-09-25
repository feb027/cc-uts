<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="flash error" role="alert"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
<a href="index.php?action=show&id=<?= (int)$game['id']; ?>" class="btn outline small" style="margin-bottom:1rem;">← Kembali</a>
<div class="form-layout">
  <form method="post" action="index.php?action=config_update" class="panel-form" id="configEditForm" novalidate>
    <div class="panel-head">
      <h2 class="panel-title" style="font-size:1.05rem;">Edit Konfigurasi</h2>
      <span class="panel-intro" style="margin:0;">Perbarui pengaturan profil untuk game <strong><?= htmlspecialchars($game['name']); ?></strong>.</span>
    </div>
    <input type="hidden" name="id" value="<?= (int)$config['id']; ?>" />
    <div class="form-grid" style="--cols:2;">
      <div class="field full">
        <label for="profile_name">Nama Profil <span class="req">*</span></label>
        <input type="text" id="profile_name" name="profile_name" required value="<?= htmlspecialchars($config['profile_name']); ?>" />
        <div class="field-error" id="err_profile_name"></div>
      </div>
      <div class="field">
        <label for="mouse_dpi">DPI</label>
        <input type="number" id="mouse_dpi" name="mouse_dpi" value="<?= htmlspecialchars($config['mouse_dpi']); ?>" />
      </div>
      <div class="field">
        <label for="in_game_sensitivity">Sensitivitas</label>
        <input type="text" id="in_game_sensitivity" name="in_game_sensitivity" value="<?= htmlspecialchars($config['in_game_sensitivity']); ?>" />
      </div>
      <div class="field">
        <label for="crosshair_code">Crosshair Code</label>
        <input type="text" id="crosshair_code" name="crosshair_code" value="<?= htmlspecialchars($config['crosshair_code']); ?>" />
      </div>
      <div class="field full">
        <label for="graphics_notes">Catatan Grafis</label>
        <textarea id="graphics_notes" name="graphics_notes" rows="6" placeholder="Render scale, shadows, FOV, dll..."><?= htmlspecialchars($config['graphics_notes']); ?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button class="btn" type="submit" id="saveBtn">Simpan</button>
      <a href="index.php?action=show&id=<?= (int)$config['game_id']; ?>" class="btn outline">Batal</a>
      <a href="index.php?action=config_delete&id=<?= (int)$config['id']; ?>" class="btn danger" onclick="return confirm('Hapus konfigurasi ini?');">Hapus</a>
    </div>
    <p style="font-size:.55rem; opacity:.6; margin:1rem 0 0;">Shortcut: <strong>Ctrl+Enter</strong> untuk simpan cepat.</p>
  </form>
  <aside class="panel-side">
    <strong style="font-size:.75rem; letter-spacing:.5px;">Tips</strong>
    <ul style="margin:0; padding-left:1rem; font-size:.65rem; line-height:1.4; display:flex; flex-direction:column; gap:.4rem;">
      <li>Isi nama profil yang jelas (misal: Competitive Low, Casual Ultra).</li>
      <li>Simpan catatan grafis agar konsisten antar sesi benchmark.</li>
      <li>Gunakan Crosshair Code untuk kemudahan share.</li>
    </ul>
  </aside>
</div>
<script>
(function(){
  const form = document.getElementById('configEditForm');
  const profile = document.getElementById('profile_name');
  const errProfile = document.getElementById('err_profile_name');
  const saveBtn = document.getElementById('saveBtn');
  function setLoading(on){
    if(on){ saveBtn.classList.add('loading'); saveBtn.disabled=true; saveBtn.innerHTML='<span class="spinner"></span> Menyimpan...'; }
    else { saveBtn.classList.remove('loading'); saveBtn.disabled=false; saveBtn.textContent='Simpan'; }
  }
  form.addEventListener('submit', (e)=>{
    errProfile.textContent='';
    if(!profile.value.trim()){
      e.preventDefault();
      errProfile.textContent='Nama profil wajib diisi';
      profile.focus();
      return;
    }
    setLoading(true);
  });
  document.addEventListener('keydown', (e)=>{
    if((e.ctrlKey||e.metaKey) && e.key==='Enter'){ form.requestSubmit(); }
  });
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
