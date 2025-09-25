<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php $isEdit = isset($game); ?>
<div class="form-layout">
  <section class="panel-form" aria-labelledby="headingFormGame">
    <header class="panel-head">
      <h2 id="headingFormGame" class="panel-title"><?= $isEdit? 'Edit Game':'Tambah Game'; ?></h2>
      <?php if ($isEdit): ?>
        <a href="index.php?action=show&id=<?= $game['id']; ?>" class="btn outline small">Lihat</a>
      <?php endif; ?>
    </header>
    <p class="panel-intro">Isi data dasar game. Cover opsional; jika kosong akan tampil placeholder. <kbd>Ctrl</kbd>+<kbd>Enter</kbd> untuk simpan cepat.</p>
    <?php if (!empty($_SESSION['error'])): ?><div class="error" role="alert"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>
    <form method="post" action="index.php?action=<?= $isEdit? 'update':'store'; ?>" id="gameForm" novalidate>
      <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $game['id']; ?>" /><?php endif; ?>
      <fieldset class="form-grid" style="--cols:2;">
        <legend class="sr-only">Data Game</legend>
        <div class="field full">
          <label for="fName">Nama Game <span class="req" aria-hidden="true">*</span></label>
          <input id="fName" minlength="3" type="text" name="name" value="<?= $isEdit? htmlspecialchars($game['name']):''; ?>" required autofocus aria-required="true" aria-describedby="hintName" />
          <div class="hint" id="hintName">Minimal 3 karakter. Hindari duplikasi.</div>
          <div class="field-error" id="errName" aria-live="polite"></div>
        </div>
        <div class="field full">
          <label for="fCover">URL Cover <span class="optional">(opsional)</span></label>
          <input id="fCover" type="url" name="cover_image_url" placeholder="https://..." value="<?= $isEdit? htmlspecialchars($game['cover_image_url']):''; ?>" aria-describedby="hintCover" />
          <div class="hint" id="hintCover">Format URL valid ke gambar (.jpg, .png, .webp).</div>
          <div class="field-error" id="errCover" aria-live="polite"></div>
        </div>
      </fieldset>
      <div class="form-actions">
        <button type="submit" class="btn primary" id="submitBtn" data-default-text="<?= $isEdit? 'Perbarui':'Simpan'; ?>"><?= $isEdit? 'Perbarui':'Simpan'; ?></button>
        <a href="index.php" class="btn outline">Batal</a>
        <?php if ($isEdit): ?>
          <a class="btn danger" href="index.php?action=delete&id=<?= $game['id']; ?>" onclick="return confirm('Hapus game ini?');">Hapus</a>
        <?php endif; ?>
      </div>
    </form>
  </section>
  <aside class="panel-side" aria-label="Preview">
    <div class="preview-box" id="previewBox">
      <div class="preview-img-wrap">
        <img id="coverPreview" alt="Preview cover" />
        <div class="preview-empty" id="previewEmpty">Tidak ada cover
          <span>Masukkan URL untuk melihat pratinjau.</span>
        </div>
      </div>
      <div class="preview-meta" id="previewMeta">
        <strong id="pName"><?= $isEdit? htmlspecialchars($game['name']):'Nama game akan tampil di sini'; ?></strong>
        <small class="p-status" id="pStatus">Status: <span id="pStatusValue"><?= $isEdit? 'Loaded' : 'Menunggu input'; ?></span></small>
      </div>
    </div>
  </aside>
</div>
<script>
// Form enhancements: shortcut, validation, preview, loading state
(function(){
  const form = document.getElementById('gameForm');
  const nameInput = document.getElementById('fName');
  const coverInput = document.getElementById('fCover');
  const errName = document.getElementById('errName');
  const errCover = document.getElementById('errCover');
  const previewImg = document.getElementById('coverPreview');
  const previewEmpty = document.getElementById('previewEmpty');
  const pName = document.getElementById('pName');
  const pStatusValue = document.getElementById('pStatusValue');
  const submitBtn = document.getElementById('submitBtn');
  const defaultBtnText = submitBtn?.getAttribute('data-default-text') || submitBtn?.textContent;

  form?.addEventListener('keydown', function(e){ if((e.ctrlKey||e.metaKey) && e.key==='Enter'){ e.preventDefault(); form.requestSubmit(); } });

  function validateName(){
    if(!nameInput) return true;
    const v = nameInput.value.trim();
    let msg = '';
    if(v.length===0){ msg = 'Nama wajib diisi.'; }
    else if(v.length < 3){ msg = 'Minimal 3 karakter.'; }
    errName.textContent = msg; 
    nameInput.setAttribute('aria-invalid', msg? 'true':'false');
    return !msg;
  }
  nameInput?.addEventListener('input', validateName);

  function updatePreview(){
    if(!coverInput) return;
    const url = coverInput.value.trim();
    if(!url){
      previewImg.src = '';
      previewImg.style.display='none';
      previewEmpty.style.display='flex';
      pStatusValue.textContent='Menunggu input';
      errCover.textContent='';
      return;
    }
    // rudimentary check
    const ok = /^(https?:)\/\/.+\.(png|jpe?g|webp|gif|bmp|svg)$/i.test(url.split('?')[0]);
    if(!ok){
      errCover.textContent='URL tidak terlihat seperti gambar valid.';
      previewImg.src='';
      previewImg.style.display='none';
      previewEmpty.style.display='flex';
      pStatusValue.textContent='URL tidak valid';
      return; 
    }
    errCover.textContent='';
    previewImg.style.display='none';
    previewEmpty.style.display='flex';
    pStatusValue.textContent='Memuat...';
    const img = new Image();
    img.onload = function(){
      previewImg.src = url; 
      previewImg.style.display='block';
      previewEmpty.style.display='none';
      pStatusValue.textContent='Berhasil dimuat';
    };
    img.onerror = function(){
      errCover.textContent='Gagal memuat gambar.';
      previewImg.src='';
      previewImg.style.display='none';
      previewEmpty.style.display='flex';
      pStatusValue.textContent='Gagal memuat';
    };
    img.src = url;
  }
  coverInput?.addEventListener('input', function(){ updatePreview(); });
  if(coverInput && coverInput.value) updatePreview();

  nameInput?.addEventListener('input', ()=>{ pName.textContent = nameInput.value.trim()||'Nama game akan tampil di sini'; });

  form?.addEventListener('submit', function(e){
    if(!validateName()){ e.preventDefault(); nameInput.focus(); return; }
    // loading state
    if(submitBtn){
      submitBtn.disabled = true; 
      submitBtn.classList.add('loading');
      submitBtn.innerHTML = '<span class="spinner" aria-hidden="true"></span> Menyimpan...';
    }
  });
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
