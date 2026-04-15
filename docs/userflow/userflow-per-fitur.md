# User Flow Per Fitur — TEFA Canning SIP

> Dokumen ini berisi diagram user flow (Mermaid) untuk setiap fitur pada BAB II laporan.
> Setiap diagram berdiri sendiri, memiliki arah top-down (TD), dan tidak ada garis yang bersilangan.

---

## 2.3.1 User Flow — Registrasi Akun

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start"])
    START --> A1["Buka Landing Page<br/>(tefacanning.my.id)"]
    A1 --> A2["Klik 'Pre-Order Sekarang'<br/>atau 'Register'"]
    A2 --> A3["Sistem Menampilkan<br/>Formulir Registrasi"]
    A3 --> A4["Isi Formulir:<br/>• Nama Lengkap<br/>• Email<br/>• No. Telepon<br/>• Organisasi (opsional)<br/>• Alamat<br/>• Password<br/>• Konfirmasi Password"]
    A4 --> A5["Klik Tombol 'Register'"]
    A5 --> V1{"Validasi<br/>Data?"}
    V1 -->|"❌ Gagal"| ERR1["Tampilkan Pesan Error:<br/>• Email sudah terdaftar<br/>• Format telepon salah<br/>• Password < 8 karakter<br/>• Konfirmasi tidak cocok"]
    ERR1 --> A4
    V1 -->|"✅ Berhasil"| A6["Akun Pelanggan Dibuat<br/>di Database"]
    A6 --> A7["Auto-Login dengan<br/>Guard 'customer'"]
    A7 --> A8["Redirect ke<br/>Dashboard Customer"]
    A8 --> DONE(["🔴 End"])

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style V1 fill:#f59e0b,color:#000
    style ERR1 fill:#ef4444,color:#fff
    style A6 fill:#3b82f6,color:#fff
```

---

## 2.3.2 User Flow — Login Pelanggan

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start"])
    START --> B1["Buka Landing Page"]
    B1 --> B2["Klik Tombol 'Login'<br/>di Navbar"]
    B2 --> B3["Sistem Menampilkan<br/>Halaman Login Customer<br/>(/customer/login)"]
    B3 --> B4["Isi Email & Password"]
    B4 --> B5["Klik Tombol 'Login'"]
    B5 --> V1{"Verifikasi<br/>Kredensial?"}
    V1 -->|"❌ Gagal"| ERR1["Tampilkan Error:<br/>'Email atau password salah'"]
    ERR1 --> B4
    V1 -->|"✅ Berhasil"| B6["Autentikasi dengan<br/>Guard 'customer'"]
    B6 --> B7["Redirect ke<br/>Dashboard Customer<br/>(/customer)"]
    B7 --> DONE(["🔴 End"])

    B3 --> B8["Belum punya akun?<br/>Klik 'Register'"]
    B8 --> B9["Redirect ke<br/>Halaman Registrasi"]

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style V1 fill:#f59e0b,color:#000
    style ERR1 fill:#ef4444,color:#fff
    style B6 fill:#3b82f6,color:#fff
```

---

## 2.3.3 User Flow — Dashboard Pelanggan

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start<br/>(Sudah Login)"])
    START --> C1["Sistem Menampilkan<br/>Dashboard Customer<br/>(/customer)"]

    C1 --> W1["📋 Widget 1: Selamat Datang<br/>Nama, Email, Organisasi,<br/>Tanggal Bergabung"]
    C1 --> W2["📊 Widget 2: Ringkasan Pesanan<br/>Total Pesanan, Total Belanja,<br/>Dikonfirmasi, Menunggu"]
    C1 --> W3["📅 Widget 3: Batch Terbaru<br/>Nama Batch, Event, Tanggal"]
    C1 --> W4["🛒 Widget 4: Produk Tersedia<br/>Daftar Produk Aktif + Harga"]

    W1 --> NAV{"Navigasi<br/>Menu?"}
    W2 --> NAV
    W3 --> NAV
    W4 --> NAV

    NAV -->|"Pre-Order"| N1["Buka Halaman<br/>Pre-Order"]
    NAV -->|"Riwayat Pesanan"| N2["Buka Halaman<br/>Riwayat Pesanan"]
    NAV -->|"Profil Saya"| N3["Buka Halaman<br/>Edit Profil"]

    W3 --> CTA["Klik Tombol<br/>'Pre-Order Sekarang'<br/>pada Widget Batch"]
    CTA --> N1

    N1 --> DONE(["🔴 End"])
    N2 --> DONE
    N3 --> DONE

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style NAV fill:#f59e0b,color:#000
    style W1 fill:#6366f1,color:#fff
    style W2 fill:#6366f1,color:#fff
    style W3 fill:#6366f1,color:#fff
    style W4 fill:#6366f1,color:#fff
```

---

## 2.3.4 User Flow — Pre-Order Sarden

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start"])
    START --> D1["Buka Halaman Pre-Order<br/>dari Menu Sidebar"]

    D1 --> D2["SECTION 1: Informasi Batch<br/>Pilih Batch Produksi<br/>dari Dropdown"]
    D2 --> D2a{"Batch<br/>Tersedia?"}
    D2a -->|"Tidak"| D2b["Tidak Ada Batch Open<br/>Tidak Bisa Pre-Order"]
    D2b --> DONE_FAIL(["🔴 End"])
    D2a -->|"Ya"| D3["Sistem Tampilkan<br/>Detail Batch:<br/>Nama, Event, Tanggal"]

    D3 --> D4["SECTION 2: Pilih Produk<br/>Klik '+ Tambah Produk'"]
    D4 --> D5["Pilih Produk dari Dropdown<br/>(Nama + Harga/unit)"]
    D5 --> D6["Isi Jumlah Kaleng<br/>(Min: 100, Max: 3000)"]
    D6 --> D7["Subtotal Dihitung<br/>Otomatis oleh Sistem"]
    D7 --> D7a{"Tambah<br/>Produk Lagi?"}
    D7a -->|"Ya (Max 10)"| D4
    D7a -->|"Tidak"| D8["SECTION 3: Catatan<br/>Tambahan (Opsional)"]

    D8 --> D9["Klik 'Kirim Pre-Order'"]
    D9 --> V1{"Validasi<br/>Server-Side?"}
    V1 -->|"❌ Batch Ditutup"| ERR1["Error: Batch Tidak<br/>Tersedia Lagi"]
    V1 -->|"❌ Produk Kosong"| ERR2["Error: Tambahkan<br/>Minimal 1 Produk"]
    ERR1 --> D2
    ERR2 --> D4
    V1 -->|"✅ Valid"| D10["Kalkulasi Harga dari DB<br/>(Server-Side Price Lookup)"]

    D10 --> D11["Buat Record Order:<br/>• No: ORD-XXXXXXXX<br/>• Kode Pickup: XXXXXX<br/>• Status: Pending<br/>• Total dari DB Price"]
    D11 --> D12["Simpan Item ke<br/>Tabel Pivot order_product"]
    D12 --> D13["Kirim Notifikasi WhatsApp<br/>ke Superadmin via Fonnte"]
    D13 --> D14["Tampilkan Notifikasi:<br/>'Pre-Order Berhasil! 🎉'<br/>+ No. Pesanan + Kode Pickup"]
    D14 --> D15["Form Direset<br/>(Siap Order Baru)"]
    D15 --> DONE(["🔴 End"])

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style DONE_FAIL fill:#dc2626,color:#fff
    style V1 fill:#f59e0b,color:#000
    style D2a fill:#f59e0b,color:#000
    style D7a fill:#f59e0b,color:#000
    style ERR1 fill:#ef4444,color:#fff
    style ERR2 fill:#ef4444,color:#fff
    style D10 fill:#3b82f6,color:#fff
    style D11 fill:#3b82f6,color:#fff
    style D13 fill:#22c55e,color:#fff
```

---

## 2.3.5 User Flow — Riwayat Pesanan

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start"])
    START --> E1["Buka Halaman<br/>Riwayat Pesanan<br/>dari Menu Sidebar"]
    E1 --> E2["Sistem Menampilkan<br/>Tabel Pesanan Milik Customer<br/>(Urut Terbaru)"]
    E2 --> E2a{"Ada<br/>Pesanan?"}
    E2a -->|"Tidak"| E2b["Tampilkan Empty State:<br/>'Belum ada pesanan'"]
    E2b --> DONE_EMPTY(["🔴 End"])
    E2a -->|"Ya"| E3["Tabel Menampilkan:<br/>No. Pesanan, Batch, Produk,<br/>Total, Status, Tanggal"]

    E3 --> E4{"Pilih<br/>Aksi?"}

    E4 -->|"📝 Edit"| E5{"Status<br/>Pending?"}
    E5 -->|"Ya"| E6["Redirect ke<br/>Halaman Edit Pesanan"]
    E5 -->|"Tidak"| E5a["Tombol Edit<br/>Tidak Muncul"]

    E4 -->|"📄 PDF"| E7["Buka PDF Laporan<br/>di Tab Baru"]

    E4 -->|"🗑️ Hapus"| E8{"Status<br/>Pending?"}
    E8 -->|"Ya"| E9["Tampilkan Modal<br/>Konfirmasi Hapus"]
    E9 --> E9a{"Konfirmasi?"}
    E9a -->|"Ya, Hapus"| E10["Pesanan Dihapus<br/>(Soft Delete)"]
    E9a -->|"Batal"| E3
    E8 -->|"Tidak"| E8a["Tombol Hapus<br/>Tidak Muncul"]

    E4 -->|"🔍 Lihat Kode Pickup"| E11{"Status<br/>Ready?"}
    E11 -->|"Ya"| E12["Kode Pickup Ditampilkan<br/>di Kolom 'Kode Ambil'"]
    E11 -->|"Tidak"| E12a["Kolom Kode Pickup<br/>Tersembunyi"]

    E10 --> E3
    E6 --> DONE(["🔴 End"])
    E7 --> DONE
    E5a --> E3
    E8a --> E3
    E12 --> E3
    E12a --> E3

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style DONE_EMPTY fill:#dc2626,color:#fff
    style E4 fill:#f59e0b,color:#000
    style E5 fill:#f59e0b,color:#000
    style E8 fill:#f59e0b,color:#000
    style E9a fill:#f59e0b,color:#000
    style E11 fill:#f59e0b,color:#000
    style E10 fill:#ef4444,color:#fff
    style E2a fill:#f59e0b,color:#000
```

---

## 2.3.6 User Flow — Halaman Master Data (Pelanggan & Produk)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start<br/>(Admin Login)"])
    START --> F1["Buka Menu Sidebar<br/>Grup 'Master Data'"]

    F1 --> F2["Kelola Pelanggan"]
    F1 --> F3["Kelola Produk"]

    %% ── PELANGGAN ──
    F2 --> F2a["Tabel Pelanggan:<br/>Nama, Email, HP,<br/>Organisasi, Alamat, Jumlah Order"]
    F2a --> F2b{"Pilih Aksi?"}
    F2b -->|"➕ Buat Baru"| F2c["Isi Form:<br/>Nama, Email, HP,<br/>Organisasi, Alamat"]
    F2b -->|"✏️ Edit"| F2d["Edit Data Pelanggan"]
    F2b -->|"🗑️ Hapus"| F2e["Soft Delete Pelanggan"]
    F2b -->|"👁️ Lihat"| F2f["Detail Pelanggan +<br/>Tabel Riwayat Pesanan"]
    F2c --> F2a
    F2d --> F2a
    F2e --> F2a
    F2f --> F2a

    %% ── PRODUK ──
    F3 --> F3a["Tabel Produk:<br/>Nama, SKU, Harga,<br/>Satuan, Status"]
    F3a --> F3b{"Pilih Aksi?"}
    F3b -->|"➕ Buat Baru"| F3c["Isi Form Produk"]
    F3b -->|"✏️ Edit"| F3d{"Superadmin?"}
    F3d -->|"Ya"| F3e["Edit Semua Data<br/>Termasuk Harga"]
    F3d -->|"Tidak (Teknisi)"| F3f["Edit Data Produk<br/>TANPA Kolom Harga"]
    F3b -->|"🗑️ Hapus"| F3g{"Produk<br/>Inti?"}
    F3g -->|"Ya (SST/ASN/SSC)"| F3h["❌ Tombol Hapus<br/>Tersembunyi"]
    F3g -->|"Tidak"| F3i["Soft Delete Produk"]
    F3c --> F3a
    F3e --> F3a
    F3f --> F3a
    F3h --> F3a
    F3i --> F3a

    F2a --> DONE(["🔴 End"])
    F3a --> DONE

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style F2b fill:#f59e0b,color:#000
    style F3b fill:#f59e0b,color:#000
    style F3d fill:#f59e0b,color:#000
    style F3g fill:#f59e0b,color:#000
    style F3h fill:#ef4444,color:#fff
    style F3e fill:#8b5cf6,color:#fff
    style F3f fill:#6b7280,color:#fff
```

---

## 2.3.7 User Flow — Halaman Manajemen Produksi (Kelola Batch)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start<br/>(Admin Login)"])
    START --> G1["Buka Menu Sidebar<br/>'Manajemen Produksi'<br/>→ Kelola Batch"]
    G1 --> G2["Tabel Batch:<br/>Nama, Event, Tanggal,<br/>Status, Jumlah Pesanan"]

    G2 --> G3{"Pilih Aksi?"}
    G3 -->|"➕ Buat Baru"| G4["Isi Form Batch:<br/>• Nama Batch<br/>• Status (default: Open)<br/>• Nama Event<br/>• Tanggal Event<br/>• Deskripsi"]
    G4 --> G4a["Simpan Batch Baru"]
    G4a --> G2

    G3 -->|"✏️ Edit"| G5["Edit Data Batch"]
    G5 --> G5a{"Ubah Status<br/>ke Ready?"}
    G5a -->|"Tidak"| G5b["Simpan Perubahan<br/>Biasa"]
    G5b --> G2
    G5a -->|"Ya"| G6["Simpan Status = Ready"]
    G6 --> G7["🔔 Trigger Otomatis:<br/>Kirim WhatsApp ke SEMUA<br/>Customer dalam Batch"]
    G7 --> G8["Fonnte API Mengirim<br/>Pesan 'Pesanan Siap Diambil'<br/>ke Setiap Pelanggan"]
    G8 --> G2

    G3 -->|"🗑️ Hapus"| G9["Soft Delete Batch"]
    G9 --> G2

    G3 -->|"👁️ Lihat"| G10["Detail Batch +<br/>Statistik Pesanan"]
    G10 --> G2

    %% Siklus Status Batch
    G2 --> CYCLE["📌 Siklus Status Batch:<br/>🟢 Open → 🟡 Processing<br/>→ 🔵 Ready → 🔴 Closed"]
    CYCLE --> DONE(["🔴 End"])

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style G3 fill:#f59e0b,color:#000
    style G5a fill:#f59e0b,color:#000
    style G7 fill:#22c55e,color:#fff
    style G8 fill:#22c55e,color:#fff
    style CYCLE fill:#6366f1,color:#fff
```

---

## 2.3.8 User Flow — Halaman Audit Log

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start<br/>(Admin Login)"])
    START --> H1["Buka Menu Sidebar<br/>'Audit & Log'<br/>→ Activity Log"]

    H1 --> H2{"Superadmin?"}
    H2 -->|"❌ Tidak (Teknisi)"| H3["Akses Ditolak<br/>oleh Filament Shield"]
    H3 --> DONE_DENY(["🔴 End"])

    H2 -->|"✅ Ya"| H4["Tabel Audit Log:<br/>Waktu, Aktor, Aksi,<br/>Target, Deskripsi"]

    H4 --> H5{"Filter<br/>Data?"}
    H5 -->|"Filter Aksi"| H5a["Created / Updated /<br/>Deleted"]
    H5 -->|"Filter Model"| H5b["Batch / Order /<br/>Product / Customer"]
    H5 -->|"Tanpa Filter"| H4
    H5a --> H4
    H5b --> H4

    H4 --> H6{"Lihat<br/>Detail?"}
    H6 -->|"Ya"| H7["Halaman Detail Log:<br/>• Timestamp Lengkap<br/>• Nama Aktor<br/>• Model & ID Target<br/>• Old Values vs New Values"]
    H6 -->|"Tidak"| H4
    H7 --> H4

    H4 --> INFO["ℹ️ VIEW ONLY<br/>Tidak Ada CRUD<br/>Data Dicatat Otomatis<br/>oleh Spatie Activity Log"]
    INFO --> DONE(["🔴 End"])

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style DONE_DENY fill:#dc2626,color:#fff
    style H2 fill:#f59e0b,color:#000
    style H5 fill:#f59e0b,color:#000
    style H6 fill:#f59e0b,color:#000
    style H3 fill:#ef4444,color:#fff
    style INFO fill:#6366f1,color:#fff
    style H7 fill:#3b82f6,color:#fff
```

---

## 2.3.9 User Flow — Halaman Pengaturan (Manajemen Pengguna)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#dc2626', 'primaryTextColor': '#fff', 'lineColor': '#94a3b8', 'fontFamily': 'Inter'}}}%%

flowchart TD
    START(["🟢 Start<br/>(Admin Login)"])
    START --> I1["Buka Menu Sidebar<br/>'Pengaturan'<br/>→ Manajemen Pengguna"]

    I1 --> I2{"Superadmin?"}
    I2 -->|"❌ Tidak (Teknisi)"| I3["Akses Ditolak<br/>oleh Filament Shield"]
    I3 --> DONE_DENY(["🔴 End"])

    I2 -->|"✅ Ya"| I4["Tabel Pengguna:<br/>Nama, Email, HP,<br/>Role, Tanggal Dibuat"]

    I4 --> I5{"Pilih Aksi?"}

    I5 -->|"➕ Buat Baru"| I6["Isi Form Pengguna:<br/>• Nama Lengkap<br/>• Email<br/>• No. Telepon<br/>• Password<br/>• Pilih Role"]
    I6 --> I6a["Simpan Pengguna Baru"]
    I6a --> I4

    I5 -->|"✏️ Edit"| I7["Edit Data Pengguna"]
    I7 --> I4

    I5 -->|"🗑️ Hapus"| I8{"Target =<br/>Superadmin?"}
    I8 -->|"Ya"| I9["❌ Tidak Bisa Hapus<br/>Akun Superadmin"]
    I8 -->|"Tidak"| I10{"Target =<br/>Diri Sendiri?"}
    I10 -->|"Ya"| I11["❌ Tidak Bisa Hapus<br/>Akun Sendiri"]
    I10 -->|"Tidak"| I12["Hapus Akun<br/>Pengguna (Soft Delete)"]

    I9 --> I4
    I11 --> I4
    I12 --> I4

    I4 --> DONE(["🔴 End"])

    style START fill:#22c55e,color:#fff
    style DONE fill:#dc2626,color:#fff
    style DONE_DENY fill:#dc2626,color:#fff
    style I2 fill:#f59e0b,color:#000
    style I5 fill:#f59e0b,color:#000
    style I8 fill:#f59e0b,color:#000
    style I10 fill:#f59e0b,color:#000
    style I3 fill:#ef4444,color:#fff
    style I9 fill:#ef4444,color:#fff
    style I11 fill:#ef4444,color:#fff
```

---

## Panduan Warna Diagram

| Warna | Makna |
|-------|-------|
| 🟢 Hijau (`#22c55e`) | Start node / Notifikasi WhatsApp berhasil |
| 🔴 Merah (`#dc2626`) | End node / Error / Penolakan akses |
| 🟡 Kuning (`#f59e0b`) | Decision diamond (percabangan) |
| 🔵 Biru (`#3b82f6`) | Proses server-side / Detail |
| 🟣 Ungu (`#6366f1`) | Widget / Info box / Superadmin-only |
| ⚫ Abu-abu (`#6b7280`) | Akses terbatas (Teknisi) |

## Catatan Desain

1. **Setiap diagram berdiri sendiri** — Tidak ada dependency antar diagram.
2. **Arah selalu Top-Down (TD)** — Semua alur mengalir dari atas ke bawah.
3. **Decision diamond selalu binary** — Setiap keputusan hanya memiliki 2 cabang (Ya/Tidak).
4. **Error path kembali ke step sebelumnya** — Tidak pernah dead-end tanpa recovery.
5. **Warna konsisten** — Merah untuk error/end, kuning untuk keputusan, hijau untuk start/success.
6. **Label garis jelas** — Setiap garis dari decision memiliki label "Ya"/"Tidak" atau nama aksi.
