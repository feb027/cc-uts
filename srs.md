### **Dokumen Kebutuhan Perangkat Lunak (SRS): GameSpec Tracker**

**Versi: 1.0**
**Tanggal: 25 September 2025**

### 1. Pendahuluan

#### 1.1 Tujuan
Tujuan dari dokumen ini adalah untuk mendefinisikan secara jelas kebutuhan fungsional dan non-fungsional untuk aplikasi web "GameSpec Tracker". Aplikasi ini akan berfungsi sebagai platform pribadi untuk mencatat, mengelola, dan melacak konfigurasi pengaturan dalam game serta data performa hardware untuk setiap game yang dimainkan pengguna.

#### 1.2 Ruang Lingkup Proyek
Aplikasi ini akan memungkinkan pengguna untuk melakukan operasi CRUD (Create, Read, Update, Delete) pada tiga entitas utama:
1.  **Game:** Daftar game yang dimiliki dan dimainkan pengguna.
2.  **Konfigurasi:** Pengaturan spesifik untuk setiap game (misalnya, sensitivitas mouse, pengaturan grafis, crosshair).
3.  **Benchmark:** Catatan hasil pengujian performa (misalnya, FPS rata-rata, suhu komponen) untuk setiap game.

Proyek ini bersifat *self-hosted* dan akan diakses melalui browser web.

#### 1.3 Target Pengguna
Aplikasi ini dirancang untuk:
*   Gamer PC, terutama pemain game FPS yang sering mengubah dan mengoptimalkan pengaturan.
*   Penggemar hardware PC yang suka melacak performa sistem mereka setelah melakukan *tuning* atau *upgrade*.
*   Pengguna yang menginginkan kontrol penuh atas datanya tanpa bergantung pada layanan pihak ketiga.

---

### 2. Deskripsi Keseluruhan

#### 2.1 Perspektif Produk
GameSpec Tracker adalah aplikasi web mandiri. Aplikasi ini akan berjalan di server pribadi pengguna (dalam kasus ini, VM Ubuntu di Proxmox) dan diakses melalui jaringan lokal atau secara aman melalui internet via Cloudflare Tunnel.

#### 2.2 Fungsi Utama Produk
*   **Manajemen Game:** Menambah, melihat, mengedit, dan menghapus game dari koleksi pribadi.
*   **Manajemen Konfigurasi:** Membuat beberapa profil konfigurasi untuk satu game, menyimpannya, dan mengeditnya kembali.
*   **Manajemen Benchmark:** Mencatat hasil benchmark dari waktu ke waktu untuk melihat dampak dari perubahan hardware atau driver.
*   **Pencarian dan Penyaringan:** Memudahkan pengguna menemukan game atau konfigurasi tertentu dengan cepat.

---

### 3. Kebutuhan Spesifik

#### 3.1 Kebutuhan Fungsional

**REQ-FUNC-01: Manajemen Game (CRUD)**
*   **Create:** Pengguna dapat menambahkan game baru dengan mengisi nama game dan URL gambar sampul (opsional).
*   **Read:** Sistem menampilkan semua game dalam bentuk galeri (kartu) di halaman utama.
*   **Update:** Pengguna dapat mengubah nama atau gambar sampul game yang sudah ada.
*   **Delete:** Pengguna dapat menghapus game. Menghapus game juga akan menghapus semua data Konfigurasi dan Benchmark yang terkait dengannya (cascade delete).

**REQ-FUNC-02: Manajemen Konfigurasi (CRUD)**
*   **Create:** Di halaman detail sebuah game, pengguna dapat menambahkan profil konfigurasi baru. Data yang disimpan:
    *   Nama Profil (cth: "Settingan Ranked", "Untuk Latihan Aim")
    *   DPI Mouse
    *   Sensitivitas In-Game
    *   Kode Crosshair (untuk game seperti Valorant)
    *   Catatan Pengaturan Grafis (bidang teks bebas untuk detail seperti kualitas tekstur, anti-aliasing, dll).
*   **Read:** Pengguna dapat melihat semua profil konfigurasi yang tersimpan untuk game tersebut.
*   **Update:** Pengguna dapat mengedit detail dari profil konfigurasi yang ada.
*   **Delete:** Pengguna dapat menghapus profil konfigurasi.

**REQ-FUNC-03: Manajemen Benchmark (CRUD)**
*   **Create:** Di halaman detail sebuah game, pengguna dapat menambahkan catatan benchmark baru. Data yang disimpan:
    *   Tanggal Benchmark
    *   Versi Driver GPU
    *   FPS Rata-rata
    *   FPS 1% Low (opsional)
    *   Suhu CPU & GPU (°C)
    *   Catatan Tambahan (misalnya, "Setelah upgrade RAM", "Driver Nvidia versi 555.xx").
*   **Read:** Pengguna dapat melihat riwayat benchmark untuk game tersebut, diurutkan dari yang terbaru.
*   **Update:** Pengguna dapat mengedit entri benchmark yang ada untuk memperbaiki kesalahan.
*   **Delete:** Pengguna dapat menghapus entri benchmark.

#### 3.2 Kebutuhan Antarmuka Pengguna (UI/UX)
*   **Tema:** Aplikasi harus menggunakan tema gelap (*dark mode*) sebagai default.
    *   **Warna Latar Belakang Utama:** Gelap kebiruan (cth: `#1A202C` - Dark Slate Blue).
    *   **Warna Aksen:** Biru langit yang tidak terlalu terang (cth: `#4299E1` - Sky Blue).
    *   **Warna Teks:** Putih keabuan untuk kontras yang nyaman (cth: `#E2E8F0`).
*   **Desain:** Modern, bersih, unik.
*   **Responsif:** Tampilan harus dapat beradaptasi dengan baik di layar desktop maupun perangkat mobile.

#### 3.3 Kebutuhan Non-Fungsional

**REQ-NON-01: Arsitektur Kode (Modularitas)**
*   Kode sumber harus terstruktur secara modular dan tidak bersifat monolit (dalam satu file).
*   Direkomendasikan menggunakan pola desain seperti **MVC (Model-View-Controller)** atau yang serupa, yang memisahkan antara logika bisnis, data, dan presentasi.


**REQ-NON-02: Skalabilitas**
*   Struktur database harus dirancang untuk kemudahan penambahan fitur di masa depan. Relasi antar tabel harus jelas.
*   Kode harus ditulis sedemikian rupa sehingga menambahkan fitur baru (misalnya, sistem akun pengguna, perbandingan benchmark) tidak memerlukan perombakan besar.

**REQ-NON-03: Performa**
*   Aplikasi harus terasa cepat dan responsif. Waktu muat halaman awal harus minimal.
*   *Query* ke database harus dioptimalkan untuk menghindari kelambatan saat data mulai banyak.

---

### Apendiks A: Desain Awal Skema Database

Ini adalah desain skema relasional sederhana untuk memulai.

**Tabel: `games`**
*   `id` (Primary Key, Auto Increment)
*   `name` (VARCHAR, Not Null)
*   `cover_image_url` (VARCHAR, Nullable)
*   `created_at` (TIMESTAMP)
*   `updated_at` (TIMESTAMP)

**Tabel: `configurations`**
*   `id` (Primary Key, Auto Increment)
*   `game_id` (Foreign Key -> `games.id`, On Delete Cascade)
*   `profile_name` (VARCHAR, Not Null)
*   `mouse_dpi` (INTEGER)
*   `in_game_sensitivity` (DECIMAL)
*   `crosshair_code` (VARCHAR, Nullable)
*   `graphics_notes` (TEXT, Nullable)
*   `created_at` (TIMESTAMP)
*   `updated_at` (TIMESTAMP)

**Tabel: `benchmarks`**
*   `id` (Primary Key, Auto Increment)
*   `game_id` (Foreign Key -> `games.id`, On Delete Cascade)
*   `benchmark_date` (DATE)
*   `driver_version` (VARCHAR, Nullable)
*   `avg_fps` (INTEGER)
*   `low_1_percent_fps` (INTEGER, Nullable)
*   `cpu_temp` (INTEGER)
*   `gpu_temp` (INTEGER)
*   `notes` (TEXT, Nullable)
*   `created_at` (TIMESTAMP)
*   `updated_at` (TIMESTAMP)
