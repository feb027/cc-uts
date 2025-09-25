# GameSpec Tracker (Tahap 4 - UX & Visualisasi)

Tahap 4 menambahkan peningkatan UX & analitik:
- Flash message (sukses / error) setelah aksi CRUD.
- Pencarian game (`q` query param) pada halaman daftar.
- Filter tanggal benchmark (from/to) di halaman detail game.
- Grafik line (Chart.js) untuk Avg FPS & 1% Low.
- Refactor router menjadi `Router` class sederhana.

## Struktur Direktori
```
website/
  index.php              # Front controller / router sederhana
  setup.php              # Jalankan untuk migrasi tabel
  database.sql           # Definisi schema
  app/
    Core/ (Autoloader, Database, Helpers)
    Models/Game.php
    Controllers/GameController.php
    Views/
      layouts/{header,footer}.php
      games/{index,form}.php
```

## Cara Menjalankan (di dalam environment Docker yang sudah ada)
1. Pastikan container database & web sudah jalan:
   - `docker compose up -d` (dari root project)
2. Buka container web atau cukup akses via browser setelah migrasi.
3. Jalankan migrasi (sekali saja):
   - Buka `http://localhost:8000/setup.php` di browser
   - Harus muncul pesan `Migrasi selesai. Tabel siap.`
4. Akses aplikasi utama: `http://localhost:8000/` -> halaman daftar game.
5. Tambah game baru dengan tombol `+ Game` atau menu `Tambah Game`.
6. Edit / hapus lewat tombol di setiap kartu.

## Catatan
- Tabel `configurations` dan `benchmarks` sudah dibuat untuk tahap berikutnya, tetapi belum ada UI / model / controller.
- Penghapusan game otomatis menghapus konfigurasi & benchmark terkait karena `ON DELETE CASCADE`.
- Validasi minimal (nama wajib diisi); bisa diperluas nanti.

## Fitur Konfigurasi (Tahap 2)
Di halaman detail game:
- Form inline untuk menambah konfigurasi.
- List konfigurasi yang tersimpan dengan ringkasan (DPI, Sens, Crosshair, catatan).
- Tombol Edit/Hapus pada tiap item.
- Halaman edit terpisah untuk perubahan lebih lengkap.

Alur uji:
1. Buka halaman game (klik kartu game).
2. Isi form konfigurasi dan submit.
3. Pastikan muncul di daftar.
4. Klik Edit, ubah nilai, simpan.
5. Hapus salah satu untuk uji delete.

## Fitur Benchmark (Tahap 3)
Di halaman detail game:
- Form inline tambah benchmark (tanggal, driver, fps, suhu, catatan).
- Daftar benchmark terbaru di atas (urut desc tanggal + created_at).
- Tombol Edit/Hapus.
- Edit view terpisah `bench_edit.php`.

Alur uji:
1. Buka detail game.
2. Tambah beberapa benchmark dengan variasi tanggal & FPS.
3. Edit satu benchmark (ubah driver / FPS), simpan.
4. Hapus satu benchmark, pastikan list ter-update.

## Badge Jumlah
Di halaman utama, setiap kartu game menampilkan:
- Cfg = jumlah konfigurasi.
- Bm = jumlah benchmark.

## Fitur Baru (Tahap 4)
1. Flash Messages
   - Ditangani via helper `flash('tipe','pesan')` dan ditampilkan otomatis di layout header.
   - Tipe saat ini: `success`, `error`.
2. Pencarian Game
   - Form di halaman index dengan input teks `q`.
   - SQL LIKE pencocokan nama.
3. Filter Rentang Tanggal Benchmark
   - Form GET `from` / `to` (date) di halaman show.
   - Mengurangi daftar benchmark & data grafik.
4. Grafik FPS
   - Chart.js CDN, menampilkan dua dataset: Avg FPS & 1% Low.
   - Otomatis skip null (spanGaps=true).
5. Router Refactor
   - File baru `app/Core/Router.php`.
   - Pendaftaran route dengan `$router->get()` / `$router->post()`.

## Next Steps (Opsional Tahap 5)
- Validasi input lebih ketat & sanitasi.
- Pagination untuk daftar game / benchmark.
- Export / import konfigurasi & benchmark (CSV / JSON).
- Autentikasi user (multi user environment).
- Optimasi query (index, eager counts via JOIN).
- Pagination + infinite scroll.

Jika tahap ini sudah sesuai, berikan instruksi "next" atau sebut fitur lanjutan yang ingin ditambahkan.

Jika tahap ini sudah sesuai, berikan instruksi "next" untuk lanjut ke tahap selanjutnya.
