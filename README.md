# SARPRAS - Sistem Informasi Pengaduan Sarana Sekolah

Aplikasi web Helpdesk & Ticketing System untuk pelaporan kerusakan sarana dan prasarana sekolah secara terstruktur dan transparan. Dikembangkan menggunakan Framework Laravel dan basis data MySQL/SQLite untuk kebutuhan Proyek Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak.

---

## 🛠️ Teknologi yang Digunakan
- **Framework:** Laravel 11/12 (PHP 8.2+)
- **Database:** SQLite / MySQL
- **Frontend:** Blade Templating, Vanilla CSS, Bootstrap Icons
- **Visualisasi Data:** Chart.js

---

## 📋 Fitur Utama

### 1. Multi-Role & Autentikasi
- Login & Logout Multi-user (Role: Admin/Petugas dan Siswa)
- Registrasi mandiri akun Siswa dengan NISN
- Proteksi Middleware Role Authorization
- Pengaturan Profil dan Ubah Password

### 2. Layanan Pengaduan (Siswa)
- Formulir pembuatan tiket aduan kerusakan sarana
- Pemilihan kategori sarana, lokasi/ruangan, tingkat urgensi
- Unggah foto bukti kerusakan dengan pratinjau instan
- Tracking status tiket dengan indikator tahapan alur penanganan
- Riwayat pengaduan pribadi dan pencarian tiket

### 3. Pengelolaan & Verifikasi (Admin Sarpras)
- Dashboard statistik counter dan grafik distribusi aduan per kategori
- Verifikasi tiket aduan (*Pending -> Diproses -> Selesai / Ditolak*)
- Input catatan teknisi dan unggah foto bukti hasil perbaikan
- CRUD Master Data Kategori Sarana
- CRUD Master Data Lokasi & Ruangan
- CRUD Master Data Pengguna (Admin & Siswa)

### 4. Laporan & Rekapitulasi
- Filter data laporan berdasarkan rentang tanggal, status, kategori, dan lokasi
- Format Cetak Resmi (Print View) lengkap dengan Kop Surat Sekolah dan pengesahan
- Fitur ekspor rekapitulasi data ke format CSV / Excel

---

## 🚀 Panduan Menjalankan Proyek

1. **Buka Terminal / Command Prompt** pada folder proyek:
   ```bash
   cd C:\Users\Asus\Documents\sarpras-app
   ```

2. **Jalankan Database Migration & Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Pastikan Symlink Storage Aktif:**
   ```bash
   php artisan storage:link
   ```

4. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Buka browser pada alamat: `http://127.0.0.1:8000`

---

## 🔑 Akun Uji Coba Default

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin / Petugas** | `admin@gmail.com` | `password123` |
| **Siswa Demo** | `siswa@gmail.com` | `password123` |
