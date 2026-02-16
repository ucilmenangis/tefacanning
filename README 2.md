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

