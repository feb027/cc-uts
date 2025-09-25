 </div>
 <footer class="site-footer" role="contentinfo">
   <div class="footer-inner">
     <div class="f-brand">
       <div class="logo-sm">GS</div>
       <div class="meta">
         <strong>GameSpec Tracker</strong>
         <span class="version">v0.1 • Games CRUD</span>
       </div>
     </div>
     <nav class="f-nav" aria-label="Footer">
       <a href="index.php">Daftar Games</a>
       <a href="index.php?action=create">Tambah Game</a>
       <a href="https://github.com/feb027/cc-uts" target="_blank" rel="noopener" aria-label="GitHub repository">GitHub</a>
       <button type="button" class="to-top" id="toTop" aria-label="Kembali ke atas">↑</button>
     </nav>
     <div class="f-meta">
       <span>&copy; <?= date('Y'); ?> GameSpec Tracker</span>
       <span class="sep" aria-hidden="true">•</span>
       <span><span id="themeLabelFooter"></span> Theme</span>
     </div>
   </div>
 </footer>
 <script>
 (function(){
   const btn = document.getElementById('toTop');
   btn?.addEventListener('click', ()=>{ window.scrollTo({top:0, behavior:'smooth'}); });
   const root = document.documentElement;
   const out = document.getElementById('themeLabelFooter');
   function sync(){ const t = root.getAttribute('data-theme')||'dark'; if(out) out.textContent = t==='light'?'Light':'Dark'; }
   const mo = new MutationObserver(sync); mo.observe(document.documentElement,{attributes:true, attributeFilter:['data-theme']});
   sync();
 })();
 </script>
 </body>
 </html>
