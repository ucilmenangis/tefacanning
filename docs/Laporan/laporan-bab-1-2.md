# LAPORAN PROYEK AKHIR

## Sistem Informasi Transaksi dan Monitoring Pre-Order Sarden Kaleng Berbasis Batch pada Teaching Factory Politeknik Negeri Jember

**Mata Kuliah:** Workshop Proyek Perangkat Lunak (Semester 2)  
**Program Studi:** Teknik Informatika  
**Institusi:** Politeknik Negeri Jember  
**Tahun Akademik:** 2025/2026

---

# DAFTAR ISI

- **BAB I — PENDAHULUAN**
  - 1.1 Latar Belakang
  - 1.2 Rumusan Masalah
  - 1.3 Tujuan
  - 1.4 Manfaat
    - 1.4.1 Manfaat bagi Unit TEFA Canning
    - 1.4.2 Manfaat bagi Pelanggan
    - 1.4.3 Manfaat Akademis
  - 1.5 Batasan Masalah

- **BAB II — PENGEMBANGAN ALUR SISTEM TRANSAKSI PADA TEFA CANNING**
  - 2.1 User Flow
  - 2.2 Gambaran Umum Alur Sistem
  - 2.3 Alur Pengguna Publik (Landing Page)
    - 2.3.1 Registrasi Akun *(+ User Flow Diagram)*
    - 2.3.2 Login Pelanggan *(+ User Flow Diagram)*
    - 2.3.3 Dashboard Pelanggan *(+ User Flow Diagram)*
    - 2.3.4 Pre-Order Sarden *(+ User Flow Diagram)*
    - 2.3.5 Riwayat Pesanan *(+ User Flow Diagram)*
    - 2.3.6 Halaman Master Data (Pelanggan & Produk) *(+ User Flow Diagram)*
    - 2.3.7 Halaman Manajemen Produksi (Kelola Batch) *(+ User Flow Diagram)*
    - 2.3.8 Halaman Audit Log *(+ User Flow Diagram)*
    - 2.3.9 Halaman Pengaturan (Manajemen Pengguna) *(+ User Flow Diagram)*
  - 2.4 Alur Administrator (Admin Panel)
    - 2.4.1 Login Administrator
    - 2.4.2 Dashboard Admin
    - 2.4.3 Halaman Transaksi (Kelola Pesanan)
    - 2.4.4 Halaman Master Data (Pelanggan & Produk)
    - 2.4.5 Halaman Manajemen Produksi (Kelola Batch)
    - 2.4.6 Halaman Audit Log
    - 2.4.7 Halaman Pengaturan (Manajemen Pengguna)
  - 2.5 Alur Notifikasi WhatsApp (Fonnte API)
    - 2.5.1 Notifikasi Pesanan Baru ke Superadmin
    - 2.5.2 Notifikasi Konfirmasi Pesanan ke Customer
    - 2.5.3 Notifikasi Pesanan Siap Diambil
  - 2.6 Alur Validasi Pickup (Pengambilan Barang)
  - 2.7 Siklus Hidup Batch Produksi

- **BAB III — DESAIN ANTARMUKA (FIGMA)**
  - 3.1 Halaman Publik (Landing Page)
    - 3.1.1 Tampilan Hero Section
    - 3.1.2 Tampilan Katalog Produk
    - 3.1.3 Tampilan Informasi Batch
    - 3.1.4 Tampilan Disclaimer SNI
    - 3.1.5 Tampilan Tentang Kami
    - 3.1.6 Tampilan Footer
  - 3.2 Panel Customer
    - 3.2.1 Tampilan Halaman Registrasi
    - 3.2.2 Tampilan Halaman Login Customer
    - 3.2.3 Tampilan Dashboard Customer
    - 3.2.4 Tampilan Halaman Pre-Order
    - 3.2.5 Tampilan Halaman Riwayat Pesanan
    - 3.2.6 Tampilan Halaman Edit Pesanan
    - 3.2.7 Tampilan Halaman Edit Profil
  - 3.3 Panel Admin
    - 3.3.1 Tampilan Halaman Login Admin
    - 3.3.2 Tampilan Dashboard Admin (Super Admin)
    - 3.3.3 Tampilan Dashboard Admin (Teknisi)
    - 3.3.4 Tampilan Halaman Kelola Pesanan
    - 3.3.5 Tampilan Halaman Kelola Pelanggan
    - 3.3.6 Tampilan Halaman Kelola Produk
    - 3.3.7 Tampilan Halaman Kelola Batch
    - 3.3.8 Tampilan Halaman Audit Log
    - 3.3.9 Tampilan Halaman Manajemen Pengguna
  - 3.4 Laporan PDF
    - 3.4.1 Tampilan Laporan Pesanan (PDF)
  - 3.5 Notifikasi WhatsApp
    - 3.5.1 Contoh Pesan WhatsApp Otomatis

- **BAB IV — *(lanjutan / ongoing)***

---

# BAB I — PENDAHULUAN

## 1.1 Latar Belakang

Teaching Factory (TEFA) merupakan konsep pembelajaran berbasis produksi dan bisnis yang mengintegrasikan proses belajar mengajar dengan kegiatan produksi nyata di lingkungan kampus. Politeknik Negeri Jember sebagai institusi pendidikan vokasi telah mengimplementasikan konsep Teaching Factory pada unit usaha pengalengan ikan sarden yang berlokasi di lingkungan kampus. Unit TEFA Canning ini memproduksi produk sarden kaleng dengan tiga varian utama yaitu Sarden Saus Tomat, Sarden Asam Manis, dan Sarden Saus Cabai yang dijual kepada masyarakat umum maupun komunitas internal kampus.

Proses transaksi pada unit TEFA Canning saat ini masih dilakukan secara manual, mulai dari pencatatan pesanan, pengelolaan data pelanggan, hingga pembuatan laporan keuangan. Sistem manual tersebut menimbulkan berbagai permasalahan operasional, antara lain: (1) pencatatan pesanan yang rawan kesalahan dan duplikasi data; (2) tidak adanya monitoring real-time terhadap volume penjualan dan status produksi; (3) kesulitan dalam melacak riwayat transaksi pelanggan; (4) pembuatan laporan keuangan yang memakan waktu dan berpotensi tidak akurat; serta (5) komunikasi status pesanan kepada pelanggan yang tidak terstruktur.

Selain permasalahan operasional, unit TEFA Canning juga menghadapi kendala terkait regulasi Standar Nasional Indonesia (SNI) untuk produk pangan kaleng. Produksi tidak dapat dilakukan secara kontinu seperti pabrik konvensional, melainkan harus disesuaikan dengan jadwal pembelajaran dan event kampus. Hal ini memerlukan model bisnis khusus di mana volume produksi harus direncanakan berdasarkan jumlah pesanan aktual, bukan estimasi pasar.

Berdasarkan permasalahan tersebut, diperlukan sebuah sistem informasi berbasis web yang mampu mendigitalisasi seluruh proses transaksi dan monitoring pada unit TEFA Canning. Sistem ini mengadopsi model **Pre-Order Berbasis Batch** yang memungkinkan pelanggan melakukan pemesanan dalam periode batch tertentu yang terhubung dengan event kampus. Model ini memastikan volume produksi sesuai dengan permintaan aktual sehingga meminimalkan pemborosan bahan baku dan memenuhi persyaratan sertifikasi SNI.

Sistem yang dikembangkan diberi nama **TEFA Canning SIP** (*Transaction & Monitoring System*) yang dibangun menggunakan framework Laravel 10 dan FilamentPHP v3 sebagai *admin panel builder*. Sistem ini dirancang dengan arsitektur *multi-panel* yang memisahkan antarmuka untuk administrator, teknisi produksi, dan pelanggan, serta dilengkapi dengan fitur notifikasi otomatis melalui WhatsApp, pembuatan laporan PDF, dan *audit trail* untuk transparansi operasional.

## 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam proyek ini adalah sebagai berikut:

1. Bagaimana merancang dan mengimplementasikan sistem informasi transaksi berbasis web yang mampu mengelola proses pre-order sarden kaleng dengan model batch pada Teaching Factory Politeknik Negeri Jember?
2. Bagaimana mengimplementasikan sistem multi-panel dengan hak akses berbeda untuk super admin, teknisi, dan pelanggan menggunakan FilamentPHP?
3. Bagaimana mengintegrasikan notifikasi otomatis melalui WhatsApp API (Fonnte) untuk meningkatkan komunikasi antara unit TEFA Canning dengan pelanggan?
4. Bagaimana membangun dashboard monitoring yang mampu menyajikan data penjualan, status produksi, dan laporan keuangan secara real-time?
5. Bagaimana menerapkan *audit trail* pada sistem untuk memastikan transparansi dan akuntabilitas setiap tindakan administratif?

## 1.3 Tujuan

Tujuan dari proyek ini adalah:

1. Mengembangkan sistem informasi transaksi dan monitoring berbasis web untuk unit Teaching Factory pengalengan ikan sarden di Politeknik Negeri Jember.
2. Mengimplementasikan sistem multi-panel (Admin Panel, Customer Panel, dan Landing Page publik) dengan *role-based access control* menggunakan FilamentPHP dan Filament Shield.
3. Mengintegrasikan fitur notifikasi WhatsApp otomatis menggunakan Fonnte API untuk konfirmasi pesanan, notifikasi pengambilan, dan pemberitahuan pesanan baru kepada admin.
4. Menyediakan dashboard monitoring dengan visualisasi data menggunakan ApexCharts untuk mendukung pengambilan keputusan operasional dan keuangan.
5. Menerapkan *audit trail* menggunakan Spatie Activity Log untuk pencatatan otomatis seluruh aktivitas administratif pada sistem.

## 1.4 Manfaat

### 1.4.1 Manfaat bagi Unit TEFA Canning

- **Efisiensi Operasional:** Mengurangi waktu pencatatan pesanan manual dan meminimalkan kesalahan data.
- **Monitoring Real-Time:** Menyediakan dashboard yang menampilkan data penjualan, status batch produksi, dan statistik pelanggan secara langsung.
- **Perencanaan Produksi:** Model pre-order berbasis batch memungkinkan perencanaan volume produksi yang akurat berdasarkan pesanan aktual.
- **Laporan Otomatis:** Pembuatan laporan keuangan (omzet, profit, modal) dan laporan pesanan dalam format PDF secara otomatis.
- **Transparansi:** Audit trail mencatat setiap perubahan data untuk keperluan akuntabilitas.

### 1.4.2 Manfaat bagi Pelanggan

- **Kemudahan Pemesanan:** Portal pelanggan khusus untuk melakukan pre-order, melihat riwayat pesanan, dan mengelola profil.
- **Notifikasi Otomatis:** Informasi status pesanan dikirimkan secara otomatis melalui WhatsApp.
- **Transparansi Harga:** Harga produk dan detail pesanan tercatat secara digital dan dapat diakses kapan saja.
- **Dokumen Pesanan:** Kemampuan mengunduh bukti pesanan dalam format PDF.

### 1.4.3 Manfaat Akademis

- Sebagai implementasi nyata dari mata kuliah Workshop Proyek Perangkat Lunak Semester 2.
- Memberikan pengalaman langsung dalam pengembangan sistem informasi menggunakan teknologi modern (Laravel, FilamentPHP, REST API).
- Menghasilkan portofolio proyek yang dapat menjadi referensi pembelajaran bagi mahasiswa lain.

## 1.5 Batasan Masalah

Agar pengembangan sistem tetap terarah dan sesuai dengan cakupan proyek, ditetapkan batasan masalah sebagai berikut:

1. Sistem dikembangkan khusus untuk unit Teaching Factory pengalengan ikan sarden di Politeknik Negeri Jember.
2. Model bisnis yang digunakan adalah **Pre-Order Berbasis Batch**, di mana pelanggan hanya dapat memesan saat batch berstatus *Open*.
3. Pembayaran dilakukan secara manual di luar sistem (transfer bank atau tunai) — sistem tidak mengintegrasikan *payment gateway*.
4. Notifikasi WhatsApp menggunakan layanan pihak ketiga Fonnte API dan bergantung pada ketersediaan layanan tersebut.
5. Sistem didesain untuk akses melalui *web browser* dan bersifat *responsive* tetapi bukan aplikasi *mobile native*.
6. Produk yang dikelola terbatas pada tiga varian sarden kaleng: Sarden Saus Tomat (SST), Sarden Asam Manis (ASN), dan Sarden Saus Cabai (SSC).
7. Hosting menggunakan layanan *shared hosting* (Rumahweb) dengan batasan sumber daya tertentu (tanpa akses SSH, tanpa Node.js runtime).
8. Fitur *QR Code scanning* untuk validasi pengambilan barang masih dalam tahap perencanaan untuk pengembangan selanjutnya.

---

# BAB II — PENGEMBANGAN ALUR SISTEM TRANSAKSI PADA TEFA CANNING

## 2.1 Gambaran Umum Alur Sistem

Sistem TEFA Canning SIP dibangun dengan arsitektur *multi-panel* yang membagi akses pengguna ke dalam tiga area utama berdasarkan peran masing-masing. Pembagian ini bertujuan untuk memisahkan fungsi operasional, memberikan pengalaman pengguna yang sesuai konteks, dan menjaga keamanan data melalui mekanisme *role-based access control*.

Ketiga area utama tersebut adalah:

1. **Halaman Publik (Landing Page)** — Diakses melalui URL utama (`/`). Halaman ini bersifat terbuka dan dapat dilihat oleh siapa saja tanpa perlu login. Fungsi utamanya adalah menampilkan katalog produk sarden kaleng, informasi batch produksi yang sedang dibuka, disclaimer SNI, serta informasi umum tentang unit TEFA Canning Politeknik Negeri Jember.

2. **Panel Customer** — Diakses melalui URL `/customer`. Panel ini dikhususkan untuk pelanggan yang telah melakukan registrasi dan login. Melalui panel ini, pelanggan dapat melakukan pre-order sarden, melihat riwayat pesanan, mengunduh laporan pesanan dalam format PDF, serta mengelola data profil pribadi.

3. **Panel Admin** — Diakses melalui URL `/admin`. Panel ini diperuntukkan bagi pengelola sistem yang terdiri dari dua peran: **Super Admin** dengan akses penuh ke seluruh fitur sistem termasuk data keuangan dan manajemen pengguna, serta **Teknisi** yang memiliki akses terbatas pada fungsi operasional produksi tanpa akses ke data keuangan.

Alur umum sistem dimulai ketika pengguna mengakses halaman landing page, kemudian memilih untuk login atau registrasi sesuai perannya. Setelah berhasil masuk ke dalam sistem, pengguna diarahkan ke dashboard masing-masing panel yang menyediakan navigasi menuju berbagai fitur sesuai hak aksesnya. Seluruh aktivitas penting dalam sistem seperti pembuatan pesanan, perubahan status batch, dan pengelolaan data tercatat secara otomatis dalam *audit trail* untuk keperluan transparansi dan akuntabilitas.

Berikut adalah penjabaran detail dari setiap alur sistem berdasarkan peran pengguna.

## 2.2 Alur Pengguna Publik (Landing Page)

Halaman landing page merupakan titik masuk utama bagi seluruh pengunjung sistem. Halaman ini diakses melalui URL utama dan tidak memerlukan autentikasi. Landing page dirancang sebagai halaman informasi yang memperkenalkan produk dan layanan TEFA Canning kepada calon pelanggan.

**Langkah-langkah alur pengguna publik:**

1. **Mengakses halaman utama** — Pengguna membuka URL `tefacanning.my.id` melalui web browser. Sistem menampilkan halaman landing page dengan tampilan *hero section* yang memuat tagline "Canning SIP — Sehat, Lezat & Bergizi" beserta statistik ringkas berupa jumlah varian produk, berat per kaleng (425gr), dan keterangan "100% Tanpa Pengawet".

2. **Melihat katalog produk** — Pengguna melakukan *scroll* ke bagian katalog atau mengklik tombol "Lihat Produk" pada hero section. Sistem menampilkan tiga kartu produk yang berisi nama produk, deskripsi singkat, gambar produk, label kategori (*Terlaris*, *Fleksibel*, *Pedas*), dan tag keterangan (*Halal*, *Sterilisasi Komersial*, *Tanpa Pengawet*).

3. **Melihat informasi batch** — Pengguna melanjutkan ke bagian "Batch Produksi" yang menampilkan daftar batch yang sedang berstatus *Open* secara dinamis dari database. Setiap kartu batch menampilkan nama batch, nama event kampus terkait, tanggal event, deskripsi, dan jumlah pesanan yang sudah masuk. Jika tidak ada batch yang sedang dibuka, sistem menampilkan pesan "Belum Ada Batch Terbuka".

4. **Membaca disclaimer SNI** — Pengguna melewati bagian disclaimer yang menginformasikan bahwa produk TEFA Canning diproduksi dalam lingkungan pembelajaran Teaching Factory dan telah melalui proses quality control standar serta sterilisasi komersial.

5. **Membaca informasi "Tentang Kami"** — Bagian ini menjelaskan bahwa TEFA Canning adalah unit produksi pembelajaran Politeknik Negeri Jember, lengkap dengan empat poin keunggulan: sterilisasi komersial sesuai standar industri, tanpa bahan pengawet, menggunakan ikan lemuru segar, dan sistem pre-order berbasis batch. Bagian ini juga menampilkan logo kemitraan tiga institusi.

6. **Memilih untuk login atau registrasi** — Pengguna yang tertarik dapat mengklik tombol "Pre-Order Sekarang" yang mengarah ke halaman registrasi customer (`/customer/register`), atau mengklik tombol "Login" pada navbar untuk masuk ke panel sesuai perannya (customer atau admin).

## 2.3 Alur Pelanggan (Customer Panel)

### 2.3.1 Registrasi Akun

Registrasi akun merupakan langkah pertama bagi pelanggan baru yang ingin menggunakan layanan pre-order TEFA Canning. Halaman registrasi diakses melalui URL `/customer/register` dan menyediakan formulir pendaftaran dengan tujuh *field* inputan.

**Langkah-langkah registrasi:**

1. **Mengakses halaman registrasi** — Pelanggan baru mengklik tombol "Pre-Order Sekarang" pada landing page atau link "Register" pada halaman login customer. Sistem menampilkan formulir registrasi.

2. **Mengisi data pribadi** — Pelanggan mengisi formulir yang terdiri dari:
   - **Nama Lengkap** — Nama pelanggan (wajib diisi).
   - **Email** — Alamat email yang akan digunakan untuk login (wajib, unik).
   - **No. Telepon** — Nomor telepon aktif dengan format `08xxxxxxxxxx` (wajib).
   - **Organisasi / Instansi** — Nama organisasi atau instansi pelanggan (opsional).
   - **Alamat** — Alamat lengkap pelanggan (wajib, maksimal 500 karakter).
   - **Password** — Kata sandi untuk login (wajib, minimal 8 karakter).
   - **Konfirmasi Password** — Pengulangan kata sandi untuk validasi (wajib, harus sama dengan password).

3. **Mengirim formulir** — Pelanggan mengklik tombol "Register". Sistem melakukan validasi data: memastikan email belum terdaftar, format telepon valid, dan password memenuhi persyaratan minimal. Jika validasi berhasil, akun pelanggan dibuat dalam database dan pelanggan langsung diarahkan ke dashboard customer.

<!-- #image: Tampilan halaman registrasi customer -->

**User Flow Diagram — Registrasi Akun:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.1 User Flow — Registrasi Akun](userflow-per-fitur.md#231-user-flow--registrasi-akun)

### 2.3.2 Login Pelanggan

Pelanggan yang sudah memiliki akun dapat langsung login melalui halaman `/customer/login`.

**Langkah-langkah login:**

1. **Mengakses halaman login** — Pelanggan mengklik tombol "Login" pada navbar landing page. Sistem menampilkan formulir login customer.

2. **Memasukkan kredensial** — Pelanggan mengisi email dan password yang telah didaftarkan sebelumnya.

3. **Verifikasi dan redirect** — Sistem memverifikasi kredensial menggunakan *guard* `customer` yang terpisah dari guard admin. Jika berhasil, pelanggan diarahkan ke dashboard customer (`/customer`). Jika gagal, sistem menampilkan pesan kesalahan.

<!-- #image: Tampilan halaman login customer -->

**User Flow Diagram — Login Pelanggan:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.2 User Flow — Login Pelanggan](userflow-per-fitur.md#232-user-flow--login-pelanggan)

### 2.3.3 Dashboard Pelanggan

Dashboard pelanggan merupakan halaman utama setelah login yang menyajikan ringkasan informasi dalam bentuk empat *widget* interaktif. Dashboard ini diakses melalui URL `/customer` dan berfungsi sebagai pusat navigasi bagi pelanggan.

**Komponen dashboard pelanggan:**

1. **Widget Selamat Datang (Welcome Widget)** — Menampilkan kartu profil pelanggan yang berisi nama, email, organisasi, dan tanggal bergabung. Widget ini memberikan sapaan personal kepada pelanggan dan menyediakan akses cepat ke halaman edit profil.

2. **Widget Ringkasan Pesanan (Order Summary Widget)** — Menampilkan empat statistik utama dalam satu query terkonsolidasi: total pesanan yang pernah dibuat, total belanja kumulatif, jumlah pesanan yang telah dikonfirmasi (processing/ready/picked_up), dan jumlah pesanan yang masih menunggu konfirmasi (pending). Informasi ini membantu pelanggan memantau aktivitas transaksinya secara keseluruhan.

3. **Widget Batch Terbaru (Latest Batch Widget)** — Menampilkan informasi batch produksi terbaru yang berstatus *Open*, meliputi nama batch, nama event kampus, tanggal event, dan deskripsi. Widget ini dilengkapi dengan tombol *call-to-action* "Pre-Order Sekarang" yang mengarahkan pelanggan langsung ke halaman pre-order. Jika tidak ada batch yang sedang dibuka, widget menampilkan pesan "Belum ada batch terbuka saat ini".

4. **Widget Produk Tersedia (Available Products Widget)** — Menampilkan daftar produk sarden kaleng yang berstatus aktif beserta harga per unit. Widget ini membantu pelanggan mengetahui produk apa saja yang tersedia dan berapa harganya sebelum memulai proses pre-order.

<!-- #image: Tampilan dashboard customer dengan 4 widget -->

**User Flow Diagram — Dashboard Pelanggan:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.3 User Flow — Dashboard Pelanggan](userflow-per-fitur.md#233-user-flow--dashboard-pelanggan)

### 2.3.4 Pre-Order Sarden

Halaman pre-order merupakan fitur utama panel customer yang memungkinkan pelanggan membuat pesanan sarden kaleng. Halaman ini diakses melalui menu navigasi "Pre-Order" dan menampilkan formulir pemesanan yang terbagi ke dalam tiga section.

**Langkah-langkah pre-order:**

1. **Memilih batch produksi** — Pelanggan membuka halaman pre-order. Pada *Section* "Informasi Batch", pelanggan memilih batch produksi yang sedang berstatus *Open* dari dropdown. Hanya batch dengan status *Open* yang ditampilkan. Setelah memilih, sistem menampilkan detail batch berupa nama, event terkait, tanggal event, dan deskripsi dalam kotak informasi berwarna merah muda.

2. **Memilih produk dan jumlah** — Pada *Section* "Pilih Produk", pelanggan menambahkan produk yang ingin dipesan menggunakan komponen *Repeater*. Setiap item pesanan terdiri dari: dropdown pilihan produk (menampilkan nama dan harga per unit), input jumlah dalam satuan kaleng (minimal 100, maksimal 3000, kelipatan 50), dan subtotal yang dihitung otomatis oleh sistem. Pelanggan dapat menambahkan hingga 10 produk berbeda dalam satu pesanan melalui tombol "+ Tambah Produk".

3. **Menambahkan catatan (opsional)** — Pada *Section* "Catatan Tambahan" yang bersifat *collapsed* (tersembunyi secara default), pelanggan dapat menuliskan catatan atau permintaan khusus terkait pesanannya (maksimal 500 karakter).

4. **Mengirim pesanan** — Pelanggan mengklik tombol "Kirim Pre-Order". Sistem melakukan beberapa proses secara berurutan:
   - **Validasi batch** — Memastikan batch yang dipilih masih berstatus *Open* pada saat pengiriman (mencegah race condition jika batch ditutup saat pelanggan mengisi formulir).
   - **Validasi item** — Memastikan minimal satu produk ditambahkan ke dalam pesanan.
   - **Kalkulasi harga dari database** — Sistem mengambil harga produk langsung dari database (*server-side price lookup*), bukan dari nilai yang ditampilkan di formulir. Langkah ini menjamin integritas harga dan mencegah manipulasi harga dari sisi klien.
   - **Pembuatan pesanan** — Sistem membuat record pesanan baru dengan nomor pesanan unik (format: `ORD-XXXXXXXX`), kode pickup unik (6 karakter alfanumerik), status awal `pending`, dan total yang dikalkulasi dari harga database.
   - **Pencatatan item** — Setiap item pesanan dicatat dalam tabel pivot `order_product` dengan harga satuan yang di-*snapshot* pada saat pemesanan.
   - **Pengiriman notifikasi** — Sistem mengirimkan notifikasi WhatsApp ke superadmin melalui Fonnte API yang berisi informasi pesanan baru. Proses ini dibungkus dalam blok *try-catch* sehingga kegagalan pengiriman notifikasi tidak menggagalkan pembuatan pesanan.

5. **Konfirmasi sukses** — Sistem menampilkan notifikasi hijau "Pre-Order Berhasil! 🎉" yang berisi nomor pesanan, total pembayaran, dan kode pengambilan. Formulir direset untuk memungkinkan pelanggan membuat pesanan baru.

<!-- #image: Tampilan halaman pre-order (formulir terisi) -->

**User Flow Diagram — Pre-Order Sarden:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.4 User Flow — Pre-Order Sarden](userflow-per-fitur.md#234-user-flow--pre-order-sarden)

### 2.3.5 Riwayat Pesanan

Halaman riwayat pesanan menampilkan seluruh pesanan yang pernah dibuat oleh pelanggan yang sedang login. Halaman ini diakses melalui menu navigasi "Riwayat Pesanan" dan menyajikan data dalam bentuk tabel interaktif.

**Informasi yang ditampilkan dalam tabel:**

- **No. Pesanan** — Nomor pesanan unik dengan font monospace, dapat disalin (*copyable*), dan dapat dicari (*searchable*).
- **Batch** — Nama batch produksi terkait dengan ikon kalender.
- **Produk** — Jumlah item produk dalam pesanan (contoh: "3 item").
- **Total** — Total pembayaran dalam format mata uang Rupiah dengan teks tebal berwarna merah.
- **Status** — Badge status pesanan dengan warna dan ikon yang berbeda:
  - 🟡 *Menunggu* (pending) — Pesanan baru, belum dikonfirmasi admin.
  - 🔵 *Diproses* (processing) — Pesanan sedang dalam proses produksi.
  - 🟢 *Siap Ambil* (ready) — Produk sudah siap diambil.
  - ⚪ *Sudah Diambil* (picked_up) — Pesanan telah selesai.
- **Kode Ambil** — Kode pickup yang ditampilkan hanya saat status pesanan adalah *ready* (siap ambil).
- **Tanggal** — Tanggal pembuatan pesanan.

**Aksi yang tersedia:**

- **Edit** — Tombol berwarna kuning untuk mengedit pesanan. Hanya muncul jika status pesanan masih `pending`.
- **PDF** — Tombol untuk mengunduh laporan pesanan dalam format PDF. Tersedia untuk semua status pesanan. Dokumen PDF dibuka di tab browser baru.
- **Hapus** — Tombol untuk menghapus pesanan. Hanya muncul jika status pesanan masih `pending`. Sebelum menghapus, sistem menampilkan modal konfirmasi "Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan."

Tabel diurutkan berdasarkan tanggal pembuatan terbaru (descending) dan mendukung paginasi dengan pilihan 5, 10, atau 25 baris per halaman. Jika belum ada pesanan, tabel menampilkan pesan "Belum ada pesanan — Silakan buat pre-order terlebih dahulu."

<!-- #image: Tampilan halaman riwayat pesanan (tabel dengan data) -->

**User Flow Diagram — Riwayat Pesanan:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.5 User Flow — Riwayat Pesanan](userflow-per-fitur.md#235-user-flow--riwayat-pesanan)

### 2.3.6 Edit Pesanan

Halaman edit pesanan memungkinkan pelanggan mengubah detail pesanan yang masih berstatus `pending`. Halaman ini diakses melalui tombol "Edit" pada tabel riwayat pesanan dan hanya dapat diakses untuk pesanan milik pelanggan yang sedang login dengan status `pending`.

**Langkah-langkah edit pesanan:**

1. **Mengakses halaman edit** — Pelanggan mengklik tombol "Edit" pada pesanan yang masih pending. Sistem memvalidasi bahwa pesanan tersebut milik pelanggan yang login dan masih berstatus `pending`. Jika tidak memenuhi syarat, sistem mengembalikan error 404.

2. **Melihat data pesanan saat ini** — Formulir edit menampilkan data pesanan yang sudah ada. Bagian batch produksi ditampilkan dalam kondisi *disabled* (tidak dapat diubah) karena batch tidak boleh diubah setelah pesanan dibuat. Daftar produk dan jumlah ditampilkan dalam *repeater* yang sudah terisi sesuai data pesanan.

3. **Mengubah produk dan jumlah** — Pelanggan dapat mengubah jenis produk, menambah atau mengurangi jumlah kaleng (tetap dalam batas 100-3000), menambahkan produk baru, atau menghapus produk yang tidak diinginkan. Subtotal dihitung ulang secara otomatis setiap kali terjadi perubahan.

4. **Menyimpan perubahan** — Pelanggan mengklik tombol "Simpan Perubahan". Sistem melakukan validasi ulang bahwa pesanan masih berstatus `pending`, mengkalkulasi ulang total dari harga database (bukan form), memperbarui data pesanan dan item terkait, kemudian menampilkan notifikasi sukses.

<!-- #image: Tampilan halaman edit pesanan -->

### 2.3.7 Edit Profil

Halaman edit profil memungkinkan pelanggan memperbarui data pribadi dan mengubah password. Halaman ini diakses melalui menu navigasi "Profil Saya" atau melalui dropdown menu pengguna di sidebar.

Halaman ini memiliki fitur penguncian khusus: **jika pelanggan memiliki pesanan aktif** (status `processing` atau `ready`), maka seluruh *field* data profil akan dikunci (*disabled*) dan tidak dapat diubah. Hal ini bertujuan untuk mencegah perubahan data pelanggan ketika pesanan sedang dalam proses produksi atau siap diambil, karena data pelanggan mungkin sudah digunakan untuk keperluan produksi dan pengiriman notifikasi.

**Formulir profil terdiri dari dua section:**

**Section 1: Informasi Pribadi**

- **Nama Lengkap** — Terkunci saat ada pesanan aktif.
- **Email** — Terkunci saat ada pesanan aktif. Dilengkapi validasi keunikan (tidak boleh sama dengan email pelanggan lain).
- **No. Telepon** — Terkunci saat ada pesanan aktif.
- **Organisasi / Instansi** — Terkunci saat ada pesanan aktif.
- **Alamat** — Terkunci saat ada pesanan aktif.

Jika ada pesanan aktif, section ini menampilkan peringatan: "⚠️ Anda memiliki pesanan yang sedang diproses. Hubungi admin untuk mengubah data profil."

**Section 2: Ubah Password**

- **Password Saat Ini** — Harus diisi dan benar (divalidasi terhadap password yang tersimpan).
- **Password Baru** — Minimal 8 karakter, harus berbeda dari password saat ini.
- **Konfirmasi Password Baru** — Harus sama dengan password baru.

Fitur ubah password selalu tersedia tanpa memandang status pesanan. Pelanggan dapat mengubah password kapan saja.

<!-- #image: Tampilan halaman edit profil (saat tidak ada pesanan aktif) -->
<!-- #image: Tampilan halaman edit profil (saat ada pesanan aktif — field terkunci) -->

## 2.4 Alur Administrator (Admin Panel)

### 2.4.1 Login Administrator

Panel admin diakses melalui URL `/admin/login` dan menggunakan sistem autentikasi terpisah (*guard* `web`) dari panel customer. Panel ini digunakan oleh dua peran pengguna: **Super Admin** yang memiliki akses penuh ke seluruh fitur, dan **Teknisi** yang memiliki akses terbatas pada fungsi operasional.

**Langkah-langkah login admin:**

1. **Mengakses halaman login admin** — Pengelola sistem mengakses URL `/admin/login`. Sistem menampilkan formulir login dengan branding TEFA Canning (logo Polije merah untuk mode gelap).

2. **Memasukkan kredensial** — Admin memasukkan email dan password.

3. **Verifikasi dan redirect** — Sistem memverifikasi kredensial menggunakan guard `web`. Jika berhasil, admin diarahkan ke dashboard admin. Hak akses yang tersedia di dalam panel ditentukan secara otomatis oleh Filament Shield berdasarkan *role* yang dimiliki pengguna.

<!-- #image: Tampilan halaman login admin -->

### 2.4.2 Dashboard Admin

Dashboard admin merupakan halaman utama setelah login yang menampilkan rangkuman statistik operasional dan keuangan. Tampilan dashboard berbeda tergantung peran pengguna yang login.

**Komponen dashboard — Ditampilkan untuk semua admin:**

1. **Batch Aktif** — Menampilkan nama batch produksi yang sedang berstatus *Open* beserta tanggal event terkait. Jika tidak ada batch aktif, menampilkan "Tidak ada".

2. **Order Batch Ini** — Menampilkan total jumlah pesanan yang masuk pada batch aktif saat ini. Data ini membantu admin memantau volume pesanan yang harus diproduksi.

3. **Siap Diambil** — Menampilkan jumlah pesanan yang berstatus *ready* namun belum diambil oleh pelanggan. Jika ada pesanan yang menunggu pickup, ditampilkan dengan indikator "🔔 Menunggu pickup".

4. **Total Pelanggan** — Menampilkan jumlah total pelanggan yang terdaftar dalam sistem.

**Komponen dashboard — Hanya ditampilkan untuk Super Admin:**

5. **Total Omzet** — Menampilkan total *revenue* dari seluruh pesanan yang sudah berstatus *picked_up* (sudah diambil) dalam format Rupiah. Data ini merupakan akumulasi dari seluruh batch.

6. **Total Profit** — Menampilkan total keuntungan bersih dari seluruh pesanan yang sudah selesai. Seperti halnya omzet, data profit disembunyikan dari teknisi untuk menjaga kerahasiaan informasi keuangan.

Seluruh widget statistik diperbarui secara otomatis setiap 30 detik melalui mekanisme *polling*.

**Navigasi sidebar admin terdiri dari lima grup:**

| Grup Navigasi | Isi | Akses |
|---|---|---|
| **Transaksi** | Kelola Pesanan | Semua admin |
| **Master Data** | Kelola Pelanggan, Kelola Produk | Semua admin |
| **Manajemen Produksi** | Kelola Batch | Semua admin |
| **Audit & Log** | Activity Log | Super Admin only |
| **Pengaturan** | Manajemen Pengguna | Super Admin only |

<!-- #image: Tampilan dashboard admin (login sebagai Super Admin) -->
<!-- #image: Tampilan dashboard admin (login sebagai Teknisi — tanpa data keuangan) -->

### 2.4.3 Halaman Transaksi (Kelola Pesanan)

Halaman kelola pesanan merupakan fitur utama panel admin yang digunakan untuk mengelola seluruh pesanan yang masuk dari pelanggan. Halaman ini berada di bawah grup navigasi "Transaksi" dan menampilkan *badge* pada sidebar yang menunjukkan jumlah pesanan yang berstatus *ready* namun belum diambil.

**Tabel daftar pesanan menampilkan kolom:**

- **No. Pesanan** — Nomor unik pesanan (searchable, copyable).
- **Pelanggan** — Nama pelanggan beserta nomor telepon di bawahnya.
- **Batch** — Nama batch produksi dalam bentuk badge.
- **Status** — Badge status dengan warna dan ikon yang berbeda (Pending, Processing, Ready, Picked Up).
- **Kode Pickup** — Kode pengambilan dalam font monospace (toggleable, copyable).
- **Total** — Total pembayaran dalam Rupiah. **Kolom ini hanya terlihat oleh Super Admin** — teknisi tidak dapat melihat informasi keuangan.
- **Diambil** — Tanggal dan waktu pengambilan. Menampilkan "—" jika belum diambil.
- **Tanggal Order** — Tanggal pembuatan pesanan.

**Aksi yang tersedia:**

- **PDF** — Mengunduh laporan pesanan dalam format PDF (tombol merah).
- **Pickup** — Tombol hijau untuk memvalidasi pengambilan barang. Hanya muncul untuk pesanan berstatus `ready` yang belum diambil. Saat diklik, menampilkan modal konfirmasi "Pastikan pelanggan menunjukkan kode pickup yang benar." Setelah dikonfirmasi, status pesanan berubah menjadi `picked_up` dan waktu pengambilan dicatat.
- **View / Edit / Delete / Restore** — Aksi CRUD standar yang dikelompokkan dalam dropdown.

**Formulir pembuatan/edit pesanan meliputi:**

- Nomor pesanan dan kode pickup (di-generate otomatis oleh sistem).
- Pilihan pelanggan (searchable dropdown dengan opsi membuat pelanggan baru langsung dari form).
- Pilihan batch produksi (hanya batch berstatus *Open*).
- Item pesanan menggunakan *repeater* dengan pilihan produk, jumlah, harga satuan (otomatis dari database), dan subtotal.
- Status pesanan (dropdown: Pending, Processing, Ready, Picked Up).
- Total pesanan dan profit (**profit hanya terlihat oleh Super Admin**).
- Catatan tambahan (collapsed section).

**Filter yang tersedia:**
- Filter berdasarkan status pesanan.
- Filter berdasarkan batch produksi.
- Filter data yang sudah dihapus (*trashed*).

<!-- #image: Tampilan halaman daftar pesanan (tabel) -->
<!-- #image: Tampilan formulir buat/edit pesanan -->
<!-- #image: Tampilan modal konfirmasi pickup -->

### 2.4.4 Halaman Master Data (Pelanggan & Produk)

Halaman master data terdiri dari dua sub-halaman di bawah grup navigasi "Master Data":

**A. Kelola Pelanggan**

Halaman ini menampilkan seluruh data pelanggan yang terdaftar dalam sistem. Admin dapat melakukan operasi CRUD penuh: membuat pelanggan baru, melihat detail, mengedit data, dan menghapus (*soft delete*). Tabel pelanggan menampilkan informasi nama, email, telepon, organisasi, alamat, dan jumlah pesanan yang pernah dibuat.

Halaman detail pelanggan juga menampilkan tabel relasi (*relation table*) yang berisi seluruh pesanan yang pernah dibuat oleh pelanggan tersebut, memberikan gambaran lengkap riwayat transaksi per pelanggan.

<!-- #image: Tampilan halaman daftar pelanggan -->

**B. Kelola Produk**

Halaman ini menampilkan data produk sarden kaleng yang dijual. Setiap produk memiliki informasi nama, kode SKU, deskripsi, harga per unit, satuan, dan status aktif/non-aktif.

Terdapat **aturan proteksi khusus** pada halaman ini:
- **Harga produk hanya dapat diubah oleh Super Admin.** Teknisi dapat melihat data produk tetapi kolom harga tidak dapat diedit. Proteksi ini diimplementasikan melalui Laravel Policy (`ProductPolicy::updatePrice`).
- **Tiga produk inti tidak dapat dihapus** — Produk dengan kode TEFA-SST-001 (Sarden Saus Tomat), TEFA-ASN-001 (Sarden Asam Manis), dan TEFA-SSC-001 (Sarden Saus Cabai) dilindungi dari penghapusan oleh siapa pun, termasuk Super Admin. Proteksi ini diterapkan di level model melalui method `booted()` dan di level UI melalui aksi delete yang disembunyikan.

<!-- #image: Tampilan halaman daftar produk -->
<!-- #image: Tampilan formulir edit produk (login Super Admin — harga dapat diedit) -->

**User Flow Diagram — Master Data (Pelanggan & Produk):**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.6 User Flow — Halaman Master Data](userflow-per-fitur.md#236-user-flow--halaman-master-data-pelanggan--produk)

### 2.4.5 Halaman Manajemen Produksi (Kelola Batch)

Halaman kelola batch merupakan pusat pengelolaan siklus produksi. Setiap batch merepresentasikan satu periode produksi yang terhubung dengan event kampus. Halaman ini berada di bawah grup navigasi "Manajemen Produksi" dan menampilkan *badge* pada sidebar yang menunjukkan jumlah batch berstatus *Open*.

**Tabel daftar batch menampilkan:**

- **Nama Batch** — Contoh: "Batch Januari 2026".
- **Event** — Nama event kampus terkait (contoh: "Dies Natalis Polije").
- **Tanggal** — Tanggal pelaksanaan event.
- **Status** — Badge status dengan warna: 🟢 Open, 🟡 Processing, 🔵 Ready, 🔴 Closed.
- **Pesanan** — Jumlah pesanan yang masuk pada batch tersebut.

**Formulir pembuatan/edit batch meliputi:**

- **Informasi Batch:** Nama batch dan status (dropdown: Open, Processing, Ready, Closed).
- **Detail Event:** Nama event kampus, tanggal event, dan deskripsi.
- **Statistik (hanya pada mode edit):** Total pesanan, tanggal dibuat, dan terakhir diupdate.

**Mekanisme penting:**

Ketika admin mengubah status batch menjadi **Ready for Pickup**, sistem secara otomatis mengirimkan notifikasi WhatsApp kepada seluruh pelanggan yang memiliki pesanan dalam batch tersebut, menginformasikan bahwa produk mereka sudah siap diambil.

<!-- #image: Tampilan halaman daftar batch -->
<!-- #image: Tampilan formulir buat/edit batch -->

**User Flow Diagram — Manajemen Produksi (Kelola Batch):**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.7 User Flow — Manajemen Produksi](userflow-per-fitur.md#237-user-flow--halaman-manajemen-produksi-kelola-batch)

### 2.4.6 Halaman Audit Log

Halaman audit log menampilkan catatan kronologis seluruh aktivitas yang terjadi dalam sistem. Halaman ini berada di bawah grup navigasi "Audit & Log" dan **hanya dapat diakses oleh Super Admin**. Teknisi yang mencoba mengakses halaman ini akan mendapat penolakan akses.

Audit log bersifat **view only** — tidak ada operasi *create*, *edit*, atau *delete* yang dapat dilakukan pada data log. Data dicatat secara otomatis oleh Spatie Activity Log setiap kali terjadi pembuatan, pembaruan, atau penghapusan pada model bisnis (Batch, Order, Product, Customer, User).

**Tabel audit log menampilkan kolom:**

- **Waktu** — Timestamp kapan aktivitas terjadi.
- **Aktor** — Nama pengguna yang melakukan aksi.
- **Aksi** — Jenis aksi: Created (hijau), Updated (kuning), Deleted (merah).
- **Target** — Model dan ID data yang terpengaruh (contoh: "Order #5").
- **Deskripsi** — Ringkasan perubahan.

**Halaman detail log menampilkan:**

- Seluruh informasi di atas ditambah perbandingan nilai *old* (sebelum) dan *new* (sesudah) untuk setiap atribut yang berubah. Ini memungkinkan Super Admin melacak persis perubahan apa yang dilakukan, oleh siapa, dan kapan.

**Filter yang tersedia:**
- Filter berdasarkan jenis aksi (Created, Updated, Deleted).
- Filter berdasarkan model target (Batch, Order, Product, dll).

Data audit log diperbarui secara otomatis setiap 30 detik.

<!-- #image: Tampilan halaman audit log (tabel) -->
<!-- #image: Tampilan detail audit log (perbandingan old/new values) -->

**User Flow Diagram — Audit Log:**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.8 User Flow — Halaman Audit Log](userflow-per-fitur.md#238-user-flow--halaman-audit-log)

### 2.4.7 Halaman Pengaturan (Manajemen Pengguna)

Halaman manajemen pengguna digunakan untuk mengelola akun admin dan teknisi. Halaman ini berada di bawah grup navigasi "Pengaturan" dan **hanya dapat diakses oleh Super Admin**.

**Tabel daftar pengguna menampilkan:**

- **Nama** — Nama lengkap pengguna admin/teknisi.
- **Email** — Alamat email untuk login.
- **No. Telepon** — Nomor telepon (dengan tombol copy).
- **Role** — Peran pengguna (Super Admin atau Teknisi).
- **Tanggal Dibuat** — Kapan akun dibuat.

**Formulir pembuatan/edit pengguna meliputi:**

- Nama lengkap, email, nomor telepon, password, dan pemilihan role.

**Aturan proteksi:**

- Super Admin **tidak dapat menghapus** akun Super Admin lain — mencegah penghapusan tidak sengaja terhadap akun dengan hak akses tertinggi.
- Super Admin **tidak dapat menghapus** akunnya sendiri — mencegah situasi di mana tidak ada akun Super Admin yang tersisa.
- Teknisi **tidak dapat mengakses** halaman ini sama sekali.

<!-- #image: Tampilan halaman manajemen pengguna -->

**User Flow Diagram — Pengaturan (Manajemen Pengguna):**
> 📊 Lihat diagram: [`docs/userflow-per-fitur.md` → 2.3.9 User Flow — Manajemen Pengguna](userflow-per-fitur.md#239-user-flow--halaman-pengaturan-manajemen-pengguna)

## 2.5 Alur Notifikasi WhatsApp (Fonnte API)

Sistem TEFA Canning SIP mengintegrasikan notifikasi otomatis melalui WhatsApp menggunakan layanan Fonnte API. Notifikasi dikirimkan secara terprogram pada tiga momen penting dalam siklus transaksi.

### 2.5.1 Notifikasi Pesanan Baru ke Superadmin

**Trigger:** Pelanggan berhasil membuat pre-order melalui panel customer.

**Alur:**
1. Pelanggan mengirim formulir pre-order.
2. Sistem membuat pesanan dan menyimpan ke database.
3. Sistem memanggil `FonnteService::sendNewOrderToSuperAdmin()`.
4. Pesan WhatsApp dikirimkan ke nomor telepon pemilik/superadmin yang telah dikonfigurasi.
5. Pesan berisi: nomor pesanan, nama pelanggan, daftar produk, total pembayaran, dan ajakan untuk mengecek panel admin.

**Catatan teknis:** Pengiriman notifikasi dibungkus dalam blok *try-catch* sehingga kegagalan pengiriman (misalnya karena gangguan jaringan atau kuota Fonnte habis) tidak menggagalkan proses pembuatan pesanan.

### 2.5.2 Notifikasi Konfirmasi Pesanan ke Customer

**Trigger:** Admin membuat pesanan baru melalui panel admin.

**Alur:**
1. Admin membuat pesanan baru dan menyimpannya.
2. Sistem memanggil `FonnteService::sendOrderConfirmation()`.
3. Pesan WhatsApp dikirimkan ke nomor telepon pelanggan yang tercantum pada pesanan.
4. Pesan berisi: detail pesanan, total pembayaran, kode pickup, dan estimasi tanggal siap (dari batch).

### 2.5.3 Notifikasi Pesanan Siap Diambil

**Trigger:** Admin mengubah status batch menjadi **Ready for Pickup**.

**Alur:**
1. Admin mengedit batch dan mengubah status dari *Processing* menjadi *Ready*.
2. Sistem memanggil `FonnteService::sendReadyForPickup()`.
3. Pesan WhatsApp dikirimkan ke **seluruh pelanggan** yang memiliki pesanan dalam batch tersebut.
4. Pesan berisi: pemberitahuan bahwa produk siap diambil, lokasi pengambilan, jam operasional, dan pengingat untuk membawa kode pickup.

<!-- #image: Contoh pesan WhatsApp notifikasi pesanan baru -->
<!-- #image: Contoh pesan WhatsApp notifikasi siap diambil -->

## 2.6 Alur Validasi Pickup (Pengambilan Barang)

Validasi pickup merupakan proses verifikasi pengambilan barang oleh pelanggan. Proses ini dilakukan oleh admin atau teknisi melalui halaman kelola pesanan di panel admin.

**Langkah-langkah validasi pickup:**

1. **Pelanggan datang ke lokasi pengambilan** — Pelanggan membawa kode pickup yang diperoleh saat membuat pesanan (terlihat di halaman riwayat pesanan saat status *ready*).

2. **Admin/teknisi mencari pesanan** — Admin membuka halaman kelola pesanan dan mencari pesanan berdasarkan kode pickup atau nomor pesanan menggunakan fitur *search* pada tabel.

3. **Admin/teknisi menekan tombol Pickup** — Pada pesanan yang berstatus `ready`, tombol hijau "Pickup" tersedia di kolom aksi. Admin mengklik tombol tersebut.

4. **Konfirmasi pickup** — Sistem menampilkan modal konfirmasi bertuliskan "Pastikan pelanggan menunjukkan kode pickup yang benar." Admin mencocokkan kode pickup yang dibawa pelanggan dengan yang tercantum pada sistem.

5. **Finalisasi pickup** — Admin mengklik "Konfirmasi" pada modal. Sistem memperbarui status pesanan menjadi `picked_up` dan mencatat timestamp pengambilan (`picked_up_at`). Notifikasi hijau "Pickup Berhasil" ditampilkan.

## 2.7 Siklus Hidup Batch Produksi

Batch produksi merupakan unit pengelompokan pesanan yang terhubung dengan event kampus. Setiap batch memiliki empat tahap siklus hidup yang menentukan aksi apa yang dapat dilakukan oleh pelanggan dan admin.

| Tahap | Status | Aksi Pelanggan | Aksi Admin | Notifikasi |
|-------|--------|----------------|------------|------------|
| 1 | **Open** | Dapat membuat pre-order, edit, hapus pesanan pending | Menerima & mengelola pesanan, melihat volume order | — |
| 2 | **Processing** | Pesanan terkunci (tidak dapat diubah), profil terkunci | Memantau produksi, melihat ringkasan produksi | — |
| 3 | **Ready** | Menerima notifikasi WhatsApp, melihat kode pickup | Validasi pengambilan (pickup) | 📱 WhatsApp ke semua customer dalam batch |
| 4 | **Closed** | Pesanan menjadi arsip (read-only) | Batch diarsipkan | — |

**Aturan penting:**
- Pelanggan hanya dapat membuat pesanan pada batch berstatus **Open**.
- Edit dan hapus pesanan hanya dapat dilakukan saat status pesanan **Pending** DAN batch masih **Open**.
- Perubahan profil pelanggan terkunci saat ada pesanan berstatus **Processing** atau **Ready**.
- Notifikasi WhatsApp massal ke pelanggan otomatis terkirim saat batch berubah ke **Ready**.

---

# BAB III — DESAIN ANTARMUKA (FIGMA)

Bab ini menyajikan desain antarmuka (*user interface*) dari setiap halaman yang terdapat pada sistem TEFA Canning SIP. Desain dibuat menggunakan identitas visual brand TEFA Canning dengan palet warna utama merah (#DC2626) sebagai warna *primary*, font Inter, dan pendekatan desain modern minimalis.

## 3.1 Halaman Publik (Landing Page)

Halaman landing page merupakan halaman pertama yang dilihat oleh pengunjung saat mengakses sistem. Halaman ini berfungsi sebagai etalase digital yang memperkenalkan produk sarden kaleng TEFA Canning, menampilkan informasi batch produksi yang sedang dibuka, dan menyediakan akses menuju panel customer.

### 3.1.1 Tampilan Hero Section

Hero section menempati satu layar penuh (*full viewport height*) dan berisi tagline utama "Canning SIP — Sehat, Lezat & Bergizi", deskripsi singkat produk, dua tombol aksi ("Lihat Produk" dan "Pre-Order Sekarang"), serta tiga statistik ringkas. Latar belakang menggunakan gradasi halus dari putih ke merah muda.

<!-- #image: Screenshot hero section landing page (desktop) -->

### 3.1.2 Tampilan Katalog Produk

Section katalog menampilkan tiga kartu produk dalam layout grid 3 kolom. Setiap kartu berisi gambar produk, nama, deskripsi, badge kategori, dan tag keterangan (Halal, Sterilisasi Komersial, Tanpa Pengawet). Kartu menggunakan efek gradasi latar belakang yang berbeda per produk.

<!-- #image: Screenshot section katalog produk (3 kartu) -->

### 3.1.3 Tampilan Informasi Batch

Section batch menampilkan kartu-kartu batch produksi yang sedang berstatus *Open* secara dinamis. Setiap kartu berisi badge "Dibuka" dengan animasi pulse hijau, nama batch, nama event, deskripsi, tanggal, jumlah pesanan, dan tombol "Pre-Order Batch Ini". Jika tidak ada batch terbuka, ditampilkan *empty state* dengan ikon kalender.

<!-- #image: Screenshot section informasi batch (ada batch terbuka) -->
<!-- #image: Screenshot section informasi batch (empty state) -->

### 3.1.4 Tampilan Disclaimer SNI

Section disclaimer menampilkan banner berwarna kuning ambar dengan ikon peringatan dan teks yang menjelaskan bahwa produk diproduksi dalam lingkungan pembelajaran Teaching Factory.

<!-- #image: Screenshot section disclaimer SNI -->

### 3.1.5 Tampilan Tentang Kami

Section "Tentang Kami" menggunakan layout dua kolom: kolom kiri berisi judul, deskripsi, dan empat poin keunggulan dengan ikon centang hijau; kolom kanan berisi logo kemitraan tiga institusi dalam bingkai putih.

<!-- #image: Screenshot section tentang kami -->

### 3.1.6 Tampilan Footer

Footer menggunakan layout 4 kolom yang berisi: informasi brand TEFA Canning, alamat lokasi, navigasi cepat, dan widget Google Maps yang menunjukkan lokasi Politeknik Negeri Jember.

<!-- #image: Screenshot footer landing page -->

## 3.2 Panel Customer

Panel customer merupakan area khusus pelanggan yang memerlukan autentikasi. Panel ini menggunakan FilamentPHP dengan sidebar navigasi yang dapat dilipat (*collapsible*) dan identitas visual brand TEFA Canning.

### 3.2.1 Tampilan Halaman Registrasi

Halaman registrasi menampilkan formulir pendaftaran dengan tujuh *field*: nama lengkap, email, nomor telepon, organisasi, alamat, password, dan konfirmasi password. Setiap field dilengkapi dengan ikon *prefix* untuk meningkatkan *usability*.

<!-- #image: Screenshot halaman registrasi customer -->

### 3.2.2 Tampilan Halaman Login Customer

Halaman login customer menampilkan formulir sederhana dengan dua *field* (email dan password) serta link menuju halaman registrasi bagi pelanggan baru.

<!-- #image: Screenshot halaman login customer -->

### 3.2.3 Tampilan Dashboard Customer

Dashboard menampilkan empat widget dalam layout dua kolom: Welcome Widget (kiri atas), Order Summary Widget (kanan atas), Latest Batch Widget (kiri bawah), dan Available Products Widget (kanan bawah). Sidebar navigasi menampilkan menu Dashboard, Pre-Order, Riwayat Pesanan, dan Profil Saya.

<!-- #image: Screenshot dashboard customer (lengkap 4 widget) -->

### 3.2.4 Tampilan Halaman Pre-Order

Halaman pre-order menampilkan formulir pemesanan dengan tiga section: "Informasi Batch" (dropdown batch + detail info), "Pilih Produk" (repeater dengan grid 3 kolom: produk, jumlah, subtotal), dan "Catatan Tambahan" (collapsed textarea). Tombol "Kirim Pre-Order" berada di bagian bawah formulir.

<!-- #image: Screenshot halaman pre-order (formulir kosong) -->
<!-- #image: Screenshot halaman pre-order (formulir terisi dengan produk) -->
<!-- #image: Screenshot notifikasi sukses pre-order -->

### 3.2.5 Tampilan Halaman Riwayat Pesanan

Halaman riwayat pesanan menampilkan tabel dengan kolom: No. Pesanan, Batch, Produk, Total, Status (badge berwarna), Kode Ambil (muncul saat ready), dan Tanggal. Kolom aksi menampilkan tombol Edit (kuning, pending only), PDF (abu-abu), dan Hapus (merah, pending only).

<!-- #image: Screenshot halaman riwayat pesanan (ada data) -->
<!-- #image: Screenshot halaman riwayat pesanan (empty state) -->

### 3.2.6 Tampilan Halaman Edit Pesanan

Halaman edit pesanan mirip dengan halaman pre-order namun dengan field batch yang dikunci (*disabled*). Data produk dan jumlah sudah terisi sesuai pesanan yang akan diedit. Terdapat keterangan "Batch tidak dapat diubah setelah pesanan dibuat."

<!-- #image: Screenshot halaman edit pesanan -->

### 3.2.7 Tampilan Halaman Edit Profil

Halaman edit profil menampilkan dua section: "Informasi Pribadi" (5 field data diri) dan "Ubah Password" (3 field). Saat ada pesanan aktif, seluruh field informasi pribadi ditampilkan dalam kondisi *disabled* (abu-abu) dengan peringatan berwarna kuning.

<!-- #image: Screenshot halaman edit profil (tidak ada pesanan aktif) -->
<!-- #image: Screenshot halaman edit profil (ada pesanan aktif — field terkunci) -->

## 3.3 Panel Admin

Panel admin merupakan area pengelolaan sistem yang dibangun menggunakan FilamentPHP v3 dengan tema kustom merah, sidebar navigasi dengan lima grup, mode SPA (*Single Page Application*), dan logo Polije merah untuk mode gelap.

### 3.3.1 Tampilan Halaman Login Admin

Halaman login admin menampilkan formulir login dengan branding TEFA Canning dan logo Politeknik Negeri Jember.

<!-- #image: Screenshot halaman login admin -->

### 3.3.2 Tampilan Dashboard Admin (Super Admin)

Dashboard Super Admin menampilkan enam widget statistik: Batch Aktif, Order Batch Ini, Siap Diambil, Total Pelanggan, **Total Omzet** (dengan grafik tren), dan **Total Profit** (dengan grafik tren). Widget omzet dan profit hanya terlihat oleh Super Admin.

<!-- #image: Screenshot dashboard admin (Super Admin — 6 widget) -->

### 3.3.3 Tampilan Dashboard Admin (Teknisi)

Dashboard Teknisi hanya menampilkan empat widget statistik: Batch Aktif, Order Batch Ini, Siap Diambil, dan Total Pelanggan. Widget keuangan (omzet dan profit) **tidak ditampilkan** untuk menjaga kerahasiaan data keuangan.

<!-- #image: Screenshot dashboard admin (Teknisi — 4 widget, tanpa keuangan) -->

### 3.3.4 Tampilan Halaman Kelola Pesanan

Halaman ini menampilkan tabel pesanan dengan fitur pencarian, filter status, filter batch, dan filter data terhapus. Kolom aksi menampilkan tombol PDF (merah), Pickup (hijau, hanya untuk status ready), dan dropdown (View, Edit, Delete, Restore). Badge pada sidebar menunjukkan jumlah pesanan siap diambil.

<!-- #image: Screenshot halaman daftar pesanan (admin) -->
<!-- #image: Screenshot formulir buat pesanan baru (admin) -->
<!-- #image: Screenshot modal validasi pickup -->

### 3.3.5 Tampilan Halaman Kelola Pelanggan

Halaman ini menampilkan tabel pelanggan terdaftar dengan informasi nama, email, telepon, organisasi, dan jumlah pesanan. Formulir CRUD menyediakan field yang sama dengan registrasi customer.

<!-- #image: Screenshot halaman daftar pelanggan (admin) -->

### 3.3.6 Tampilan Halaman Kelola Produk

Halaman ini menampilkan tabel produk dengan informasi nama, kode SKU, harga, satuan, dan status. Kolom harga hanya dapat diedit oleh Super Admin. Tombol hapus disembunyikan untuk tiga produk inti.

<!-- #image: Screenshot halaman daftar produk (admin) -->
<!-- #image: Screenshot formulir edit produk (Super Admin) -->

### 3.3.7 Tampilan Halaman Kelola Batch

Halaman ini menampilkan tabel batch dengan informasi nama, event, tanggal, status (badge berwarna), dan jumlah pesanan. Badge pada sidebar menunjukkan jumlah batch berstatus *Open*. Formulir batch memiliki layout 3 kolom dengan section informasi batch, detail event, dan statistik.

<!-- #image: Screenshot halaman daftar batch (admin) -->
<!-- #image: Screenshot formulir buat/edit batch -->

### 3.3.8 Tampilan Halaman Audit Log

Halaman ini menampilkan tabel audit log dengan kolom Waktu, Aktor, Aksi (badge berwarna), Target, dan Deskripsi. Halaman detail menampilkan perbandingan nilai old/new. Hanya tersedia untuk Super Admin dan bersifat *view only*.

<!-- #image: Screenshot halaman audit log (tabel) -->
<!-- #image: Screenshot halaman detail audit log (old vs new values) -->

### 3.3.9 Tampilan Halaman Manajemen Pengguna

Halaman ini menampilkan tabel pengguna admin/teknisi dengan informasi nama, email, telepon, role, dan tanggal dibuat. Hanya tersedia untuk Super Admin. Aksi delete disembunyikan untuk akun Super Admin lain dan akun sendiri.

<!-- #image: Screenshot halaman manajemen pengguna -->
<!-- #image: Screenshot formulir buat/edit pengguna -->

## 3.4 Laporan PDF

### 3.4.1 Tampilan Laporan Pesanan (PDF)

Laporan pesanan dalam format PDF memiliki layout profesional yang terdiri dari: header dengan logo Politeknik Negeri Jember, judul "Laporan Pesanan", informasi pesanan (nomor, tanggal, status, kode pickup), data pelanggan (nama, email, telepon, organisasi, alamat), tabel produk dengan kolom nama, jumlah, harga satuan, dan subtotal, total pembayaran, dan footer dengan logo kemitraan tiga institusi. Margin halaman: 25mm atas, 20mm sisi.

<!-- #image: Screenshot laporan pesanan PDF (halaman penuh) -->

## 3.5 Notifikasi WhatsApp

### 3.5.1 Contoh Pesan WhatsApp Otomatis

Notifikasi WhatsApp dikirimkan dalam format teks biasa melalui Fonnte API. Berikut contoh pesan yang dikirimkan untuk setiap trigger:

**Pesan ke Superadmin (Pesanan Baru):**
> 🔔 *Pesanan Baru Masuk!*
> No: ORD-ABCD1234
> Pelanggan: [Nama]
> Total: Rp xxx.xxx
> Silakan cek panel admin.

**Pesan ke Customer (Konfirmasi Pesanan):**
> ✅ *Pesanan Anda Dikonfirmasi!*
> No: ORD-ABCD1234
> Total: Rp xxx.xxx
> Kode Pickup: XYZ123
> Estimasi Siap: [Tanggal Event]

**Pesan ke Customer (Siap Diambil):**
> 📦 *Pesanan Anda Siap Diambil!*
> Batch: [Nama Batch]
> Silakan datang ke lokasi pengambilan dengan membawa kode pickup.

<!-- #image: Screenshot pesan WhatsApp di HP (contoh notifikasi) -->

---

# BAB IV — *(Lanjutan / Ongoing)*

> Bab selanjutnya akan membahas implementasi teknis, pengujian, serta kesimpulan dan saran.

---

## Daftar Pustaka (Sementara)

- Bouchet, D. (2020). *DomPDF: HTML to PDF Converter*. https://github.com/dompdf/dompdf
- Chen, Y., & Chen, X. (2019). Pre-order strategies in supply chain management. *Journal of Operations Management*, 65(3), 234–248.
- Chryssolouris, G., Mavrikios, D., & Rentzos, L. (2016). The Teaching Factory: A manufacturing education paradigm. *Procedia CIRP*, 57, 44–48.
- Direktorat Pembinaan SMK. (2017). *Panduan Implementasi Teaching Factory*. Kementerian Pendidikan dan Kebudayaan.
- Fonnte. (2024). *Fonnte API Documentation*. https://fonnte.com/api
- Griffiths, D. (2022). *FilamentPHP Documentation v3*. https://filamentphp.com/docs
- ISO/IEC 27001:2013. *Information technology — Security techniques — Information security management systems*.
- Laudon, K. C., & Laudon, J. P. (2020). *Management Information Systems: Managing the Digital Firm* (16th ed.). Pearson.
- Otwell, T. (2013). *Laravel: The PHP Framework for Web Artisans*. https://laravel.com/docs
- Sandhu, R. S., Coyne, E. J., Feinstein, H. L., & Youman, C. E. (1996). Role-Based Access Control Models. *IEEE Computer*, 29(2), 38–47.
