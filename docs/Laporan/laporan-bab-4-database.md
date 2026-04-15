# LAPORAN DESAIN DATABASE — TEFA CANNING SIP

**Sistem Informasi Transaksi dan Monitoring Pre-Order Sarden Kaleng Berbasis Batch**
**pada Teaching Factory Politeknik Negeri Jember**

---

## DAFTAR ISI

- **BAB I — Entity Relationship Diagram (ERD)**
  - 1.1 Diagram ERD
  - 1.2 Penjelasan Entitas
  - 1.3 Penjelasan Relasi dan Kardinalitas

- **BAB II — Desain Tabel SQL**
  - 2.1 Tabel Customers
  - 2.2 Tabel Batches
  - 2.3 Tabel Products
  - 2.4 Tabel Orders
  - 2.5 Tabel Order_Product (Pivot)
  - 2.6 Tipe Data Khusus

- **BAB III — Relasi Tabel**
  - 3.1 Daftar Foreign Key
  - 3.2 Integritas Referensial dan Cascade
  - 3.3 Soft Delete

- **BAB IV — Normalisasi**
  - 4.1 First Normal Form (1NF)
  - 4.2 Second Normal Form (2NF)
  - 4.3 Third Normal Form (3NF)
  - 4.4 Justifikasi Denormalisasi
  - 4.5 Ringkasan

---

# BAB I — ENTITY RELATIONSHIP DIAGRAM (ERD)

## 1.1 Diagram ERD

*(Sisipkan screenshot ERD di sini — render dari docs/erd-core.mmd)*

## 1.2 Penjelasan Entitas

Database core bisnis TEFA Canning SIP terdiri dari 5 entitas:

| No | Entitas | Deskripsi |
|----|---------|-----------|
| 1 | **Customers** | Data master pelanggan yang melakukan pemesanan |
| 2 | **Batches** | Data batch pre-order yang terikat pada event kampus |
| 3 | **Products** | Katalog produk sarden kaleng beserta harga dan stok |
| 4 | **Orders** | Header pesanan yang menghubungkan pelanggan dan batch |
| 5 | **Order_Product** | Tabel pivot (perantara) item pesanan dan produk |

## 1.3 Penjelasan Relasi dan Kardinalitas

| Relasi | Entitas A | Entitas B | Kardinalitas | Penjelasan |
|--------|-----------|-----------|-------------|------------|
| Memiliki | Customers | Orders | 1:N | Satu pelanggan dapat memiliki banyak pesanan |
| Berisi | Batches | Orders | 1:N | Satu batch berisi banyak pesanan |
| Terdiri dari | Orders | Order_Product | 1:N | Satu pesanan memiliki banyak item |
| Dipesan dalam | Products | Order_Product | 1:N | Satu produk muncul di banyak item pesanan |

Relasi antara **Orders** dan **Products** adalah **Many-to-Many (N:M)**. Relasi ini dipecah menjadi dua relasi 1:N menggunakan tabel perantara **Order_Product**.

---

# BAB II — DESAIN TABEL SQL

## 2.1 Tabel `customers`

Menyimpan data master pelanggan.

| Kolom | Tipe Data | Constraint | Keterangan |
|-------|-----------|------------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik pelanggan |
| `name` | VARCHAR(255) | NOT NULL | Nama lengkap pelanggan |
| `phone` | VARCHAR(255) | NULLABLE | Nomor telepon (WhatsApp) |
| `email` | VARCHAR(255) | UNIQUE, NULLABLE | Email untuk login |
| `password` | VARCHAR(255) | NULLABLE | Password ter-hash |
| `address` | TEXT | NULLABLE | Alamat pengiriman |
| `organization` | VARCHAR(255) | NULLABLE | Institusi/organisasi pelanggan |
| `remember_token` | VARCHAR(100) | NULLABLE | Token sesi "remember me" |
| `created_at` | TIMESTAMP | NOT NULL | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | NOT NULL | Waktu pembaruan data |
| `deleted_at` | TIMESTAMP | NULLABLE | Penanda soft delete |

## 2.2 Tabel `batches`

Menyimpan data batch pre-order yang terhubung dengan event kampus.

| Kolom | Tipe Data | Constraint | Keterangan |
|-------|-----------|------------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik batch |
| `name` | VARCHAR(255) | NOT NULL | Nama batch |
| `event_name` | VARCHAR(255) | NOT NULL | Nama event kampus terkait |
| `event_date` | DATE | NULLABLE | Tanggal pelaksanaan event |
| `status` | ENUM('open','processing','ready','closed') | NOT NULL, DEFAULT 'open' | Status siklus batch |
| `description` | TEXT | NULLABLE | Keterangan batch |
| `created_at` | TIMESTAMP | NOT NULL | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | NOT NULL | Waktu pembaruan data |
| `deleted_at` | TIMESTAMP | NULLABLE | Penanda soft delete |

**Siklus Status Batch:**
1. `open` — Batch dibuka, pelanggan dapat memesan
2. `processing` — Batch ditutup, produksi dimulai, pesanan terkunci
3. `ready` — Produksi selesai, barang siap diambil
4. `closed` — Semua barang telah diambil, batch diarsipkan

## 2.3 Tabel `products`

Menyimpan data katalog produk sarden kaleng.

| Kolom | Tipe Data | Constraint | Keterangan |
|-------|-----------|------------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik produk |
| `name` | VARCHAR(255) | NOT NULL | Nama produk |
| `sku` | VARCHAR(255) | UNIQUE, NOT NULL | Stock Keeping Unit |
| `description` | TEXT | NULLABLE | Deskripsi produk |
| `price` | DECIMAL(15,2) | NOT NULL | Harga per unit |
| `stock` | INT | NOT NULL, DEFAULT 0 | Stok tersedia |
| `unit` | VARCHAR(255) | NOT NULL, DEFAULT 'pcs' | Satuan ukuran |
| `image` | VARCHAR(255) | NULLABLE | Path gambar produk |
| `is_active` | TINYINT(1) | NOT NULL, DEFAULT 1 | Status tampil di katalog |
| `created_at` | TIMESTAMP | NOT NULL | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | NOT NULL | Waktu pembaruan data |
| `deleted_at` | TIMESTAMP | NULLABLE | Penanda soft delete |

## 2.4 Tabel `orders`

Menyimpan data header pesanan yang menghubungkan pelanggan, batch, dan item pesanan.

| Kolom | Tipe Data | Constraint | Keterangan |
|-------|-----------|------------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik pesanan |
| `customer_id` | BIGINT UNSIGNED | FK → customers.id, NOT NULL | Pelanggan yang memesan |
| `batch_id` | BIGINT UNSIGNED | FK → batches.id, NOT NULL | Batch tempat pesanan dibuat |
| `order_number` | VARCHAR(255) | UNIQUE, NOT NULL | Nomor pesanan (ORD-XXXXXXXX) |
| `pickup_code` | VARCHAR(255) | UNIQUE, NOT NULL | Kode pengambilan 8 karakter |
| `status` | ENUM('pending','processing','ready','picked_up') | NOT NULL, DEFAULT 'pending' | Status pesanan |
| `total_amount` | DECIMAL(15,2) | NOT NULL, DEFAULT 0.00 | Total harga pesanan |
| `profit` | DECIMAL(15,2) | NOT NULL, DEFAULT 0.00 | Keuntungan (20% dari total) |
| `picked_up_at` | TIMESTAMP | NULLABLE | Waktu barang diambil pelanggan |
| `notes` | TEXT | NULLABLE | Catatan dari pelanggan |
| `created_at` | TIMESTAMP | NOT NULL | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | NOT NULL | Waktu pembaruan data |
| `deleted_at` | TIMESTAMP | NULLABLE | Penanda soft delete |

**Siklus Status Pesanan:**
1. `pending` — Pesanan baru dibuat, menunggu diproses
2. `processing` — Batch ditutup, pesanan sedang diproduksi
3. `ready` — Produksi selesai, siap diambil pelanggan
4. `picked_up` — Barang telah diambil pelanggan

## 2.5 Tabel `order_product` (Pivot)

Tabel perantara yang memecah relasi Many-to-Many antara `orders` dan `products`.

| Kolom | Tipe Data | Constraint | Keterangan |
|-------|-----------|------------|------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ID unik |
| `order_id` | BIGINT UNSIGNED | FK → orders.id, ON DELETE CASCADE | Pesanan terkait |
| `product_id` | BIGINT UNSIGNED | FK → products.id, ON DELETE CASCADE | Produk terkait |
| `quantity` | INT | NOT NULL, DEFAULT 1 | Jumlah produk yang dipesan |
| `unit_price` | DECIMAL(15,2) | NOT NULL | Snapshot harga saat pesanan dibuat |
| `subtotal` | DECIMAL(15,2) | NOT NULL | quantity × unit_price |
| `created_at` | TIMESTAMP | NOT NULL | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | NOT NULL | Waktu pembaruan data |

## 2.6 Tipe Data Khusus

| Tipe Data | Digunakan Pada | Alasan Pemilihan |
|-----------|---------------|-----------------|
| `DECIMAL(15,2)` | price, total_amount, profit, unit_price, subtotal | Presisi 2 digit desimal untuk nilai uang, menghindari floating-point error |
| `ENUM` | orders.status, batches.status | Membatasi nilai hanya pada opsi yang telah ditentukan |
| `TEXT` | address, description, notes | Menyimpan teks panjang tanpa batas karakter |
| `TIMESTAMP` | created_at, updated_at, deleted_at, picked_up_at | Standar Laravel untuk pencatatan waktu |
| `DATE` | batches.event_date | Menyimpan tanggal tanpa komponen waktu |
| `TINYINT(1)` | products.is_active | Representasi boolean pada MySQL/MariaDB |

---

# BAB III — RELASI TABEL

## 3.1 Daftar Foreign Key

| No | Foreign Key | Tabel Asal | Tabel Tujuan | Kardinalitas | ON DELETE |
|----|------------|-----------|-------------|-------------|-----------|
| 1 | `customer_id` | orders | customers | N:1 | CASCADE |
| 2 | `batch_id` | orders | batches | N:1 | CASCADE |
| 3 | `order_id` | order_product | orders | N:1 | CASCADE |
| 4 | `product_id` | order_product | products | N:1 | CASCADE |

## 3.2 Integritas Referensial dan Cascade

Seluruh foreign key menggunakan aturan **ON DELETE CASCADE**, artinya penghapusan data induk akan otomatis menghapus data anak yang merujuk padanya.

Contoh rantai cascade:
```
Menghapus Customer → Menghapus semua Orders milik customer tersebut
                                         ↓
                              Menghapus semua Order_Product dari order tersebut

Menghapus Batch → Menghapus semua Orders dalam batch tersebut
                                    ↓
                         Menghapus semua Order_Product dari order tersebut

Menghapus Product → Menghapus semua Order_Product yang berisi produk tersebut

Menghapus Order → Menghapus semua Order_Product dari pesanan tersebut
```

## 3.3 Soft Delete

Meskipun CASCADE aktif, keempat tabel bisnis utama (customers, batches, products, orders) menggunakan mekanisme **Soft Delete** melalui kolom `deleted_at`:

- **Cara kerja:** Data yang dihapus tidak benar-benar dihapus dari database, melainkan kolom `deleted_at` diisi dengan timestamp penghapusan.
- **Query otomatis:** Laravel Eloquent secara otomatis mengecualikan data yang telah di-soft delete dari hasil query (SELECT).
- **Cascade tidak terpicu:** Karena baris data masih ada di tabel (hanya ditandai), foreign key cascade tidak akan terpicu saat soft delete dilakukan.
- **Force delete:** Penghapusan permanen hanya bisa dilakukan oleh super admin, dan barulah cascade delete berlaku.

---

# BAB IV — NORMALISASI

Proses normalisasi dilakukan untuk mengeliminasi redundansi data dan mencegah anomali (insertion, update, deletion anomaly) pada database.

## 4.1 First Normal Form (1NF)

**Syarat:** Setiap kolom bernilai atomik, tidak ada repeating group, setiap baris unik (memiliki primary key).

**Pemenuhan:**

Seluruh tabel telah memenuhi 1NF:

- Setiap kolom menyimpan **satu nilai atomik** saja — tidak ada kolom yang berisi array atau daftar nilai.
- Setiap tabel memiliki **Primary Key** (`id`) yang menjamin keunikan setiap baris.
- Tidak terdapat repeating group.

**Contoh:** Informasi produk pada pesanan tidak disimpan dalam satu kolom seperti `"Sarden Tomat x2, Sarden Cabai x3"`. Sebaliknya, dipecah ke tabel `order_product` di mana setiap baris hanya merepresentasikan **satu jenis produk** saja.

## 4.2 Second Normal Form (2NF)

**Syarat:** Memenuhi 1NF dan tidak ada partial dependency (setiap kolom non-PK bergantung pada keseluruhan primary key).

**Pemenuhan:**

Semua tabel menggunakan **single-column primary key** (`id` AUTO_INCREMENT), sehingga partial dependency **secara otomatis tidak mungkin terjadi**. Partial dependency hanya bisa terjadi pada composite primary key (PK yang terdiri dari lebih satu kolom).

Pada tabel `order_product`, kolom `quantity`, `unit_price`, dan `subtotal` bergantung pada keseluruhan baris (kombinasi order dan product), bukan hanya pada sebagian PK. Karena tabel ini juga memiliki PK `id` sendiri, maka 2NF terpenuhi.

## 4.3 Third Normal Form (3NF)

**Syarat:** Memenuhi 2NF dan tidak ada transitive dependency (kolom non-PK tidak bergantung pada kolom non-PK lainnya).

**Pemenuhan:**

Sebagian besar tabel memenuhi 3NF. Terdapat **dua kolom yang merupakan calculated field**:

1. **`orders.profit`** — Nilainya diturunkan dari `total_amount × 20%`. Secara teori, kolom ini bergantung pada `total_amount` (transitive dependency).

2. **`order_product.subtotal`** — Nilainya diturunkan dari `quantity × unit_price`. Kolom ini bergantung pada kedua kolom non-PK tersebut.

Secara strict normalisasi, kedua kolom ini melanggar 3NF.

## 4.4 Justifikasi Denormalisasi

Kedua kolom di atas sengaja disimpan (**controlled denormalization**) dengan alasan:

1. **Performa Query** — Tidak perlu menghitung ulang profit dan subtotal setiap kali menjalankan query agregasi (SUM, laporan keuangan, dashboard). Perhitungan dilakukan sekali saat pesanan dibuat.

2. **Akurasi Historis** — `unit_price` pada `order_product` merupakan **snapshot** harga saat pesanan dibuat. Jika harga produk berubah, nilai pesanan sebelumnya tetap akurat. Hal yang sama berlaku untuk `profit` dan `subtotal` yang sudah dikalkulasi saat itu.

3. **Konsistensi Data** — Nilai-nilai ini dihitung sekali saat pembuatan pesanan dan **tidak berubah setelahnya**, sehingga tidak menimbulkan inkonsistensi.

Tanpa denormalisasi, setiap query laporan keuangan harus melakukan JOIN dan kalkulasi ulang dari seluruh tabel `order_product`, yang akan memperlambat performa seiring bertambahnya volume data.

## 4.5 Ringkasan

| Bentuk Normal | Status | Keterangan |
|---------------|--------|------------|
| 1NF | Terpenuhi | Semua kolom atomik, setiap tabel memiliki PK |
| 2NF | Terpenuhi | Semua PK single-column, tidak ada partial dependency |
| 3NF | Terpenuhi* | Denormalisasi terkontrol pada `profit` dan `subtotal` |

*\*Dengan justifikasi denormalisasi yang telah dijelaskan pada subbab 4.4.*
