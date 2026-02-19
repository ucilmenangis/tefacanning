# 📋 README 2 — Catatan Pengembangan Pribadi

> ⚠️ File ini TIDAK di-push ke GitHub (sudah ditambahkan di `.gitignore`)

---

## 🔑 Kredensial & Token

### Database
- **Host:** 127.0.0.1:3306
- **Database:** tefa_canning_db
- **Username:** root
- **Password:** _(kosong)_

### Fonnte API (WhatsApp)
- **Token:** 3EtBjue3SGQNgC7hiS1a
- **Device:** 6281358610650
- **Dashboard:** https://md.fonnte.com
- ⚠️ **JANGAN** share token ini. Siapapun yang punya token bisa kirim WhatsApp dari device kamu.
- Jika token bocor, segera regenerate dari dashboard Fonnte.

### GitHub
- **Repo:** https://github.com/ucilmenangis/tefacanning
- **Branch:** main

### Admin Account
- **URL:** http://localhost:8000/admin
- **Buat akun:** `php artisan make:filament-user`

---

## 📌 Catatan Teknis Penting

### Dual Auth Guard System
- **`web`** → User model (admin panel `/admin`)
- **`customer`** → Customer model (customer panel `/customer`)
- Config di `config/auth.php`
- Middleware: `CustomerPanelMiddleware.php`

### 3 Produk Inti (Protected)
SKU yang dilindungi dari penghapusan:
1. `TEFA-SST-001` — Sarden Saus Tomat
2. `TEFA-ASN-001` — Sarden Asin
3. `TEFA-SSC-001` — Sarden Saus Cabai

Dilindungi di:
- `Product::booted()` → mencegah delete di model level
- `ProductResource` → hidden delete action di UI
- Constant: `Product::PROTECTED_SKUS`

### Navigation Badge Return Type
Filament v3 HARUS return `(string)` dari `getNavigationBadge()`, bukan `int`. Jika return int → TypeError.

### EditOrder Mount Signature
```php
// BENAR — Handle Filament route model binding
public function mount(Order|int $order): void
{
    $orderId = $order instanceof Order ? $order->id : $order;
}
```

### PreOrder Price Integrity
Unit price di-lookup dari database (server-side), TIDAK dari form submission. Mencegah price manipulation.

### Customer Profile Edit Lock
Customer TIDAK bisa edit profil jika ada order dengan status `processing` atau `ready`. Ini untuk menjaga konsistensi data di pesanan yang sedang berjalan.

---

## 🛠️ Command Cheat Sheet

```bash
# Development
php artisan serve                    # Start server
npm run dev                          # Watch frontend
npm run build                        # Build untuk production

# Database
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed     # Reset + seed (HAPUS DATA!)
php artisan db:seed                  # Seed saja

# Filament
php artisan make:filament-user       # Buat admin
php artisan make:filament-resource   # Buat resource baru
php artisan filament:optimize        # Cache Filament components
php artisan filament:optimize-clear  # Clear Filament cache

# Cache & Debug
php artisan optimize:clear           # Clear semua cache
php artisan route:list               # Lihat semua routes
php artisan tinker                   # Interactive PHP shell

# Git
git add . && git commit -m "msg"     # Commit
git push -u origin main              # Push ke GitHub

# Testing
php artisan test                     # Run tests
./vendor/bin/pint                    # Code formatting
```

---

## 📁 File-file Kunci yang Sering Diedit

| File | Fungsi |
|------|--------|
| `app/Providers/Filament/AdminPanelProvider.php` | Konfigurasi panel admin |
| `app/Providers/Filament/CustomerPanelProvider.php` | Konfigurasi panel customer |
| `app/Filament/Customer/Pages/PreOrder.php` | Halaman pre-order customer |
| `app/Filament/Customer/Pages/EditOrder.php` | Edit pesanan pending |
| `app/Filament/Customer/Pages/EditProfile.php` | Edit profil customer |
| `app/Services/FonnteService.php` | WhatsApp notification service |
| `resources/views/pdf/order-report.blade.php` | Template PDF laporan |
| `resources/views/welcome.blade.php` | Landing page |
| `resources/views/components/landing/footer.blade.php` | Footer dengan Google Maps |
| `CLAUDE.md` | Panduan untuk AI assistant |

---

## 🐛 Bug yang Pernah Ditemui & Solusinya

1. **NavigationBadge TypeError** → Return `(string)` bukan `int`
2. **EditOrder mount() TypeError** → Union type `Order|int` dengan instanceof check
3. **PreOrder unit_price manipulation** → Server-side price lookup
4. **Filament brand logo dark mode** → Gunakan `politeknik_logo_red.png`
5. **Google Maps di footer** → Pindah ke kolom ke-4 grid
6. **create_file on existing file** → Harus pakai replace_string_in_file
7. **picked_up_at selalu NULL** → `picked_up_at` tidak ada di `$fillable` Order model + tidak ada auto-set saat status diubah via form edit. Fix: tambah ke `$fillable` + `booted()` event di Order model. SQL fix untuk data lama: `UPDATE orders SET picked_up_at = updated_at WHERE status = 'picked_up' AND picked_up_at IS NULL;`

---

## 🚀 Aturan Deploy ke Hosting (Rumahweb Entry Plan — No SSH)

> ⚠️ Rumahweb entry plan TIDAK punya SSH access. Semua database changes harus dilakukan **manual via phpMyAdmin**.

### Setelah Setiap Perubahan Code, Cek:

| Jenis Perubahan | Aksi yang Diperlukan |
|-----------------|---------------------|
| **Schema change** (kolom baru, tabel baru, alter column) | Export SQL dump dari local → Import di phpMyAdmin hosting, ATAU jalankan `ALTER TABLE` / `CREATE TABLE` manual di phpMyAdmin |
| **Data fix** (UPDATE/INSERT baris) | Jalankan query SQL di phpMyAdmin hosting |
| **Code only** (tidak ada perubahan DB) | Upload file yang berubah saja via File Manager / FTP |

### Workflow Deploy:
1. Push ke GitHub
2. Di hosting: pull via Git (jika ada Git) atau upload manual via File Manager
3. Jika ada migration baru → jalankan SQL equivalent di phpMyAdmin
4. Clear cache: buat file `clear-cache.php` di public/ yang menjalankan `Artisan::call('optimize:clear')`

### Template Perintah untuk Claude/AI:
> "Setelah memberikan solusi, WAJIB analisis apakah perubahan membutuhkan update database di server. Jika ya, berikan SQL mentah yang bisa di-copy-paste ke phpMyAdmin."

---

## 📅 Timeline Pengembangan

- **Sprint 1:** Setup Laravel + Filament, model & migration, basic CRUD
- **Sprint 2:** Dashboard widgets, charts, Fonnte API integration
- **Sprint 3:** Landing page, Blade components, red theme
- **Sprint 4:** Customer panel, registration, pre-order system
- **Sprint 5:** Customer dashboard widgets, order history, edit/delete
- **Sprint 6:** PDF reports, profile editing, code optimization, GitHub deploy

---

## 💡 Ide Pengembangan Selanjutnya

- [ ] QR Code scanning untuk pickup (integrasi mobile)
- [ ] Payment gateway (Midtrans/Xendit)
- [ ] Email notifications sebagai fallback
- [ ] Export laporan ke Excel
- [ ] Multi-warehouse support
- [ ] Customer order tracking realtime
- [ ] Dashboard analytics yang lebih detail
- [ ] Dark mode untuk landing page

---

## 🗂️ File Structure

### Admin Panel (`/admin`)
| File | Purpose |
|------|---------|
| `app/Providers/Filament/AdminPanelProvider.php` | Admin panel configuration, nav groups, colors |
| `app/Filament/Resources/OrderResource.php` | Order management (CRUD, PDF, pickup) |
| `app/Filament/Resources/CustomerResource.php` | Customer management |
| `app/Filament/Resources/ProductResource.php` | Product management |
| `app/Filament/Resources/BatchResource.php` | Batch management |
| `app/Filament/Resources/UserResource.php` | Admin user management (super_admin only) |
| `app/Filament/Resources/ActivityLogResource.php` | Audit trail viewer (super_admin only) |
| `app/Filament/Widgets/DashboardStatsWidget.php` | Dashboard stat cards |
| `app/Filament/Widgets/BatchOrderSummaryWidget.php` | Active batch order table |
| `app/Filament/Widgets/ProductionSummaryWidget.php` | Product quantity summary |
| `app/Filament/Widgets/RecentOrdersWidget.php` | Recent orders table |

### Customer Panel (`/customer`)
| File | Purpose |
|------|---------|
| `app/Providers/Filament/CustomerPanelProvider.php` | Customer panel config, auth guard |
| `app/Filament/Customer/Pages/PreOrder.php` | Pre-order form (batch + product selection) |
| `app/Filament/Customer/Pages/OrderHistory.php` | Customer's order history table |
| `app/Filament/Customer/Pages/EditOrder.php` | Edit pending orders |
| `app/Filament/Customer/Pages/EditProfile.php` | Customer profile editing |
| `app/Filament/Customer/Pages/Dashboard.php` | Customer dashboard |
| `app/Filament/Customer/Pages/Auth/Register.php` | Customer registration |
| `app/Filament/Customer/Widgets/WelcomeWidget.php` | Welcome greeting card |
| `app/Filament/Customer/Widgets/OrderSummaryWidget.php` | Order stat cards |
| `app/Filament/Customer/Widgets/LatestBatchWidget.php` | Current batch info |
| `app/Filament/Customer/Widgets/AvailableProductsWidget.php` | Product catalog |

### Landing Page (`/`)
| File | Purpose |
|------|---------|
| `resources/views/welcome.blade.php` | Landing page (hero, products, batches) |
| `resources/views/components/landing/layout.blade.php` | Landing layout wrapper |
| `resources/views/components/landing/navbar.blade.php` | Navigation bar |
| `resources/views/components/landing/footer.blade.php` | Footer with map |
| `resources/views/components/landing/product-card.blade.php` | Product card component |

### Shared / Services
| File | Purpose |
|------|---------|
| `app/Models/Order.php` | Order model (statuses, relationships, activity log) |
| `app/Models/Customer.php` | Customer model (auth, soft deletes) |
| `app/Models/Product.php` | Product model |
| `app/Models/Batch.php` | Batch model (lifecycle states) |
| `app/Models/User.php` | Admin user model (roles, permissions) |
| `app/Services/FonnteService.php` | WhatsApp notification via Fonnte API |
| `resources/views/orders/order-report.blade.php` | PDF report template |
| `database/seeders/DatabaseSeeder.php` | Master seeder |
| `config/services.php` | Fonnte API config |
