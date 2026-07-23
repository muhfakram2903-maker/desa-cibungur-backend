# 🏛️ Website & Sistem Informasi Desa Cibungur - Backend REST API

Backend resmi **Sistem Informasi & Website Desa Cibungur, Kecamatan Parungponteng**, dibangun dengan **Laravel 12 (PHP 8.3+)** menggunakan prinsip *Clean Architecture* (MVC, Repository Pattern, Service Layer, dan REST API Resource).

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP 8.3](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Sanctum](https://img.shields.io/badge/Sanctum-API_Auth-red?style=for-the-badge)
![Spatie RBAC](https://img.shields.io/badge/Spatie-Permissions-blue?style=for-the-badge)

---

## 🌟 Fitur Utama System

### 1. 🌐 Portal Informasi Publik & REST API V1
- **Berita & Pengumuman:** Sistem penerbitan berita dengan SEO Slug, Kategori, Tag, View Counter, dan Lampiran PDF.
- **Agenda & Galeri Desa:** Jadwal kegiatan masyarakat dan album media (foto/video) dengan Lightbox.
- **Potensi & UMKM Desa:** Katalog usaha lokal lengkap dengan WhatsApp direct contact, lokasi Google Maps, dan link Marketplace.
- **Transparansi APBDes:** Pendapatan, Belanja, dan Realisasi Anggaran Desa berbasis grafik visual.
- **Statistik Demografi:** Agregasi jumlah penduduk, KK, jenis kelamin, agama, dan tingkat pendidikan.

### 2. 📑 Layanan Pengaduan Masyarakat Digital
- Pengajuan laporan oleh warga (Upload Foto & Video).
- Penjanaan **Nomor Tiket Otomatis** (misal: `TKT-20260723-XXXX`).
- Lacak status pengaduan publik tanpa login (`Menunggu`, `Diproses`, `Selesai`, `Ditolak`).
- Timeline respon & dokumentasi hasil pengerjaan dari Admin Desa.

### 3. 👥 Pengelolaan Data Penduduk (Admin)
- Data NIK, KK, Dusun, RW, RT, Pekerjaan, Agama, Pendidikan, Status Kawin.
- Tracking **Riwayat Perubahan Data Penduduk**.
- Import & Export Data via Excel (`maatwebsite/excel`) & Cetak PDF (`barryvdh/laravel-dompdf`).

### 4. 🛡️ Role-Based Access Control (Spatie RBAC)
Sistem memiliki **9 Role Pengguna**:
1. `Super Admin` (Akses Penuh Pengaturan Sistem)
2. `Admin Desa` (Kelola Berita, Pengaduan, Penduduk, UMKM)
3. `Kepala Desa` (Monitoring Dashboard & APBDes)
4. `Sekretaris Desa` (Verifikasi & Laporan)
5. `Kasi Pemerintahan` (Kelola Wilayah & Penduduk)
6. `Operator` (Entry Data)
7. `Ketua RW` (Akses Data Wilayah RW)
8. `Ketua RT` (Akses Data Wilayah RT)
9. `Masyarakat` (Pengajuan Laporan & Profil User)

---

## 🧱 Arsitektur Sistem (Clean Architecture)

```
[ Frontend Client / SPA ] ─── (REST API / JSON) ───► [ Laravel 12 API Route ]
                                                              │
                                                              ▼
                                                    [ Form Request Validation ]
                                                              │
                                                              ▼
                                                      [ Controller V1 ]
                                                              │
                                                              ▼
                                                      [ Service Layer ]
                                                              │
                                                              ▼
                                                    [ Repository Pattern ]
                                                              │
                                                              ▼
                                                    [ Eloquent Models & DB ]
```

---

## ⚙️ Persyaratan Sistem (System Requirements)

- **PHP**: `^8.2` atau `^8.3` (Disarankan PHP 8.3 via XAMPP/Laragon)
- **Database**: MySQL `^8.0` atau MariaDB `^10.4`
- **Composer**: `^2.6`
- **Extensions PHP**: `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `BCMath`, `GD`/`Imagick`.

---

## 🚀 Panduan Instalasi & Setup Local

### 1. Clone Repository & Install Dependencies
```bash
git clone https://github.com/muhfakram2903-maker/desa-cibungur-backend.git
cd desa-cibungur-backend
composer install
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database MySQL di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desa_cibungur
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Migration & Database Seeding
Jalankan migrasi tabel dan isi data awal (*roles, permissions, admin users, dummy data*):
```bash
php artisan migrate --seed
```

### 5. Buat Storage Symlink
Buat link folder publik agar gambar & file ter-serve dengan benar:
```bash
php artisan storage:link
```

### 6. Jalankan Local Server
```bash
php artisan serve
```
Backend berjalan di: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Default (Development Account)

Seluruh akun default memiliki password: **`password`**

| Role | Email Login | Password | Akses |
| :--- | :--- | :---: | :--- |
| **Super Admin** | `superadmin@desa-cibungur.id` | `password` | Akses Penuh Sistem |
| **Admin Desa** | `admin@desa-cibungur.id` | `password` | Management Konten & Penduduk |
| **Kepala Desa** | `kades@desa-cibungur.id` | `password` | Dashboard & Laporan |
| **Operator** | `operator@desa-cibungur.id` | `password` | Entry Data & Pelayanan |

---

## 📡 Dokumentasi Endpoint REST API V1

Base URL API: `http://127.0.0.1:8000/api/v1`

### 🟢 Endpoint Publik (Tanpa Auth)

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/public/berita` | List Berita Desa (Query: `search`, `kategori`, `per_page`) |
| `GET` | `/public/berita/{slug}` | Detail Berita berdasarkan slug |
| `GET` | `/public/agenda` | List Agenda Kegiatan Desa |
| `GET` | `/public/pengumuman` | List Pengumuman Aktif |
| `GET` | `/public/galeri` | Album Galeri Foto & Video |
| `GET` | `/public/galeri/{slug}` | Detail Media dalam Album |
| `GET` | `/public/umkm` | Katalog Produk UMKM Desa |
| `GET` | `/public/umkm/{slug}` | Detail Produk UMKM |
| `GET` | `/public/statistik` | Data Demografi Penduduk & APBDes (Visualisasi Chart) |
| `GET` | `/public/pengaduan/track/{tiket}` | Lacak status tiket pengaduan publik |

### 🔵 Endpoint Autentikasi (`/auth`)

| Method | Endpoint | Deskripsi | Header / Auth |
| :--- | :--- | :--- | :---: |
| `POST` | `/auth/login` | Login user & dapatkan Bearer Token | Public |
| `POST` | `/auth/register` | Pendaftaran akun warga | Public |
| `POST` | `/auth/logout` | Revoke Sanctum Token | `Bearer Token` |
| `GET` | `/auth/me` | Dapatkan data profil user login & roles | `Bearer Token` |
| `POST` | `/auth/change-password` | Ganti password user | `Bearer Token` |

### 🔴 Endpoint Pengaduan & User (`Protected`)

| Method | Endpoint | Deskripsi | Header / Auth |
| :--- | :--- | :--- | :---: |
| `GET` | `/pengaduan` | List riwayat pengaduan milik user | `Bearer Token` |
| `POST` | `/pengaduan` | Submit laporan pengaduan baru (+Foto/Video) | `Bearer Token` |
| `GET` | `/pengaduan/{id}` | Detail laporan pengaduan & respon admin | `Bearer Token` |

---

## 📂 Struktur Folder Utama Project

```
desa-cibungur-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Controller Dashboard Admin (Blade)
│   │   │   ├── Api/V1/            # Controller REST API v1
│   │   │   └── Public/            # Controller Halaman Publik (Blade)
│   │   ├── Requests/              # Form Request Validation
│   │   └── Resources/             # Eloquent API JSON Resources
│   ├── Models/                    # Eloquent Models (33 Models)
│   ├── Repositories/              # Repository Pattern Layer
│   └── Services/                  # Business Logic & Service Layer
├── bootstrap/
│   └── app.php                    # Laravel 11/12 Route Loader & Middleware
├── config/
│   ├── cors.php                   # Konfigurasi CORS Header
│   ├── sanctum.php                # Konfigurasi Token Sanctum
│   └── permission.php             # Spatie Permission Config
├── database/
│   ├── migrations/                # 16 File Migrasi Database
│   └── seeders/                   # Seeder Roles, User, Wilayah, & Content
├── routes/
│   ├── api.php                    # Endpoint REST API v1
│   ├── web.php                    # Route Publik Blade & Admin Load
│   └── admin.php                  # Route Admin Dashboard
└── storage/
    └── app/public/                # Storage File Uploads
```

---

## 🧪 Jalankan Automated Testing

Untuk menguji seluruh endpoint REST API dan koneksi database:
```bash
php artisan test
```

---

## 📄 Lisensi
Sistem Informasi Desa Cibungur dikembangkan untuk **Pemerintah Desa Cibungur, Kecamatan Parungponteng**. Lisensi di bawah [MIT License](LICENSE).
