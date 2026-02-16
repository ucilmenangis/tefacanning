<div align="center">
  <img src="public/images/politeknik_logo_red.png" alt="TEFA Canning SIP" width="80">
  <h1>TEFA Canning SIP</h1>
  <p><strong>Teaching Factory Sardine Canning Transaction & Monitoring System</strong></p>
  <p>Sistem transaksi dan monitoring pre-order sarden kaleng berbasis batch untuk Teaching Factory di Politeknik Negeri Jember</p>

  <br/>

  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-10.50-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/FilamentPHP-3.x-FDAE4B?style=for-the-badge&logo=filament&logoColor=white" alt="Filament">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/MariaDB-10.x-003545?style=for-the-badge&logo=mariadb&logoColor=white" alt="MariaDB">

  <br/><br/>

  <img src="public/images/3_logo_in_1.png" alt="Partner Logos" width="280">
</div>

---

## 📋 Deskripsi

**TEFA Canning SIP** adalah sistem informasi berbasis web yang dibangun untuk mendigitalisasi proses transaksi dan monitoring di Teaching Factory (TEFA) pengalengan ikan sarden, Politeknik Negeri Jember. Sistem ini mengadopsi model **Pre-Order Berbasis Batch** yang terintegrasi dengan event kampus, memastikan volume produksi sesuai demand aktual dan mematuhi regulasi sertifikasi SNI.

### 🎯 Tujuan Utama
- Digitalisasi manajemen pesanan dan data pelanggan
- Monitoring volume penjualan melalui dashboard visual
- Automasi pelaporan keuangan (Omzet, Profit, Modal)
- Role-based access control untuk efisiensi operasional
- Notifikasi otomatis via WhatsApp (Fonnte API)

---

## ⚡ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | PHP 8.3, Laravel 10.50, Eloquent ORM |
| **Admin & Customer Panel** | FilamentPHP v3, Filament Shield (RBAC) |
| **Frontend** | Tailwind CSS, DaisyUI, Blade Components |
| **Database** | MariaDB with Soft Deletes |
| **Charts** | ApexCharts (Dashboard Visualizations) |
| **PDF** | DomPDF (Order Reports) |
| **Notifications** | Fonnte API (WhatsApp) |
| **Auth** | Dual Guard System (Admin + Customer) |
| **Audit** | Spatie Activity Log |
| **Export** | Laravel Excel (Maatwebsite) |

---

## ✨ Fitur Utama

### 🏪 Landing Page
- Katalog produk 3 varian sarden (Saus Tomat, Asin, Saus Cabai)
- Berita batch aktif dari database
- Google Maps widget & SNI disclaimer
- Responsive red-themed design

### 👤 Customer Panel (`/customer`)
- Registrasi & login dengan guard terpisah
- Dashboard: Welcome card, statistik pesanan, batch terbaru, produk tersedia
- Pre-Order: Pilih batch → tambah produk → auto-calculate
- Riwayat Pesanan: Status badge, kode pickup, unduh PDF
- Edit/Hapus pesanan (hanya status pending)
- Edit Profil (dikunci saat pesanan diproses)

### 🔧 Admin Panel (`/admin`)
- Manajemen Batch (linked ke event kampus)
- CRUD Pesanan dengan validasi pickup & workflow status
- Manajemen Produk (3 produk inti dilindungi dari penghapusan)
- Database Pelanggan persisten
- Manajemen User (Super Admin only)
- Dashboard finansial dengan charts
- Audit Log untuk semua aksi

### 📄 Laporan PDF
- Layout profesional A4 dengan branding TEFA
- Logo Polije di header, 3 logo partner di footer
- Tabel produk dengan breakdown subtotal per produk
- Kode pengambilan & informasi batch

### 📱 WhatsApp Notifications
- Konfirmasi pesanan otomatis
- Notifikasi batch siap ambil ke semua pelanggan

---

## 👥 Roles & Akses

| Fitur | Super Admin | Teknisi | Customer |
|-------|:-----------:|:-------:|:--------:|
| Manajemen User | ✅ | ❌ | ❌ |
| Edit Harga Produk | ✅ | ❌ | ❌ |
| Laporan Keuangan | ✅ | ❌ | ❌ |
| Audit Log | ✅ | ❌ | ❌ |
| Update Status Batch | ✅ | ✅ | ❌ |
| Validasi Pickup | ✅ | ✅ | ❌ |
| Buat Pesanan | ✅ | ✅ | ✅ |
| Lihat Pesanan Sendiri | ✅ | ✅ | ✅ |
| Unduh PDF Laporan | ✅ | ✅ | ✅ |

---

## 🚀 Instalasi

### Prasyarat

- PHP ≥ 8.2
- Composer
- MariaDB / MySQL
- Node.js & NPM

### Setup

```bash
# 1. Clone repository
git clone https://github.com/ucilmenangis/tefacanning.git
cd tefacanning

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_DATABASE=tefa_canning_db
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & seed database
php artisan migrate --seed

# 6. Buat akun admin
php artisan make:filament-user

# 7. Build assets & jalankan
npm run build
php artisan serve
```

### Konfigurasi Tambahan

```env
# Fonnte API (WhatsApp Notifications)
FONNTE_TOKEN=your_fonnte_api_token
FONNTE_DEVICE=your_device_number
```

---

## 🔗 Akses Aplikasi

| Halaman | URL |
|---------|-----|
| Landing Page | `http://localhost:8000` |
| Admin Panel | `http://localhost:8000/admin` |
| Customer Panel | `http://localhost:8000/customer` |
| Order PDF | `http://localhost:8000/order/{id}/pdf` |

---

## 📁 Struktur Proyek

```
app/
├── Filament/
│   ├── Customer/
│   │   ├── Pages/           # Dashboard, PreOrder, OrderHistory, EditOrder, EditProfile
│   │   │   └── Auth/        # Custom Registration
│   │   └── Widgets/         # Welcome, OrderSummary, LatestBatch, AvailableProducts
│   ├── Resources/           # Batch, Customer, Order, Product, User (Admin)
│   └── Widgets/             # DashboardStats, RecentOrders (Admin)
├── Http/
│   ├── Controllers/         # OrderPdfController
│   └── Middleware/          # CustomerPanelMiddleware
├── Models/                  # Eloquent Models (SoftDeletes + ActivityLog)
├── Providers/Filament/      # AdminPanelProvider, CustomerPanelProvider
└── Services/                # FonnteService (WhatsApp API)

resources/views/
├── components/landing/      # Blade Components (layout, navbar, footer, product-card)
├── filament/customer/       # Customer Panel Views
├── pdf/                     # Order Report Template
└── welcome.blade.php        # Landing Page
```

---

## 🎨 Brand Identity

| | Warna | Kode |
|-|-------|------|
| 🔴 | Primary | `#DC2626` |
| 🔴 | Accent | `#EF4444` |
| 🔴 | Dark | `#991B1B` |

Font: **Inter** (Google Fonts via Bunny CDN)

---

## 🛡️ Keamanan

- Dual authentication guard (admin & customer)
- Role-based access control via Filament Shield
- Edit harga dibatasi Super Admin (Laravel Policy)
- Soft deletes pada semua model utama
- Audit trail via Spatie Activity Log
- API token disimpan di environment variables
- Pickup code menggunakan cryptographically secure random
- 3 produk inti dilindungi dari penghapusan

---

## 📝 Lisensi

Dikembangkan untuk **Teaching Factory Pengalengan Ikan** — Politeknik Negeri Jember.

---

<div align="center">
  <sub>Built with ❤️ by TEFA Canning Team — Politeknik Negeri Jember</sub>
</div>
