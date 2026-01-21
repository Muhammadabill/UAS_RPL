# 🏍️ Gasskeun Rental Motor

Gasskeun Rental Motor adalah sebuah sistem informasi berbasis web yang dirancang menggunakan prinsip-prinsip Rekayasa Perangkat Lunak (RPL) untuk mendigitalisasi seluruh ekosistem bisnis persewaan sepeda motor. Proyek ini dikembangkan sebagai solusi terhadap kompleksitas pengelolaan data manual, bertujuan untuk menciptakan proses bisnis yang lebih efisien, transparan, dan terukur.

## 🚀 Live Implementation
Aplikasi telah dideploy dan dapat diakses secara publik melalui:
👉 **[http://gasskeun-rental.infinityfreeapp.com](http://gasskeun-rental.infinityfreeapp.com)**

---

## 🎯 Tujuan Pengembangan Sistem
* **Digitalisasi dan Automasi Bisnis**: Menggantikan pencatatan manual ke dalam sistem basis data relasional untuk mencegah redundansi data dan meminimalisir risiko kehilangan catatan transaksi.
* **Optimalisasi Inventaris Real-Time**: Memberikan visibilitas status unit motor secara akurat (Tersedia, Disewa, atau Maintenance) guna mencegah kesalahan operasional seperti *double booking*.
* **Peningkatan Transparansi Informasi**: Memudahkan pelanggan dalam mendapatkan informasi valid mengenai spesifikasi unit, ketersediaan, dan rincian harga tanpa verifikasi manual.
* **Integritas dan Keamanan Data**: Menjamin keamanan data pengguna melalui sistem autentikasi dan otorisasi yang terenkripsi di sisi server.
* **Analisis Pengambilan Keputusan**: Menyediakan modul pelaporan keuangan otomatis untuk memantau performa bisnis secara akurat.

---

## 🛠️ Fitur Utama Sistem

### 1. Panel Manajemen Admin (Back-End)
* **Dashboard Monitoring**: Tampilan ringkasan status ketersediaan unit motor dalam satu layar utama.
* **Manajemen Inventaris (CRUD)**: Fitur untuk menambah, memperbarui, atau menghapus data unit motor lengkap dengan spesifikasi teknis.
* **Pengelolaan Pelanggan**: Database terpusat untuk memantau data penyewa yang aktif maupun terdaftar.
* **Sistem Pengembalian**: Modul otomatis untuk memproses motor kembali dan validasi durasi penyewaan.
* **Laporan Pendapatan**: Rekapitulasi finansial otomatis untuk memantau profitabilitas bisnis.

### 2. Panel Pelanggan / User (Front-End)
* **Katalog Motor Dinamis**: Menampilkan daftar motor yang tersedia secara *real-time* berdasarkan database.
* **Sistem Booking & Sewa**: Antarmuka pemesanan sederhana dengan validasi otomatis terhadap stok unit.
* **Riwayat Penyewaan**: Catatan digital bagi pengguna untuk melacak transaksi yang pernah dilakukan secara transparan.
* **Autentikasi Pengguna**: Sistem Login dan Register untuk menjamin keamanan identitas penyewa.

---

## 💻 Tech Stack
- **Backend**: PHP 8.x (Native)
- **Database**: MySQL (RDBMS)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Cloud Environment**: Apache Server (InfinityFree Hosting)

---

## 📂 Struktur Arsitektur File
```text
Gasskeun-Rental-Motor/
├── img/                         # Direktori aset gambar unit motor
│   ├── vario.jpg                # Contoh aset gambar
│   ├── beat.jpg
│   └── nmax.jpg
├── database/                    # Direktori penyimpanan skema database
│   └── gasskeun_rental.sql      # Backup database MySQL (untuk evaluasi penguji)
├── admin.php                    # Dashboard utama admin
├── admin_monitoring.php         # Monitoring ketersediaan unit untuk admin
├── data_pelanggan.php           # Manajemen basis data pelanggan
├── index.php                    # Landing page dan sistem autentikasi (Login/Register)
├── kembali_motor.php            # Logika pemrosesan pengembalian unit
├── koneksi.php                  # Konfigurasi gateway database (PHP-MySQL)
├── laporan_pendapatan.php       # Modul rekapitulasi finansial otomatis
├── logout.php                   # Pemutusan sesi pengguna (Destroy session)
├── monitoring.php               # Status ketersediaan unit secara real-time
├── proses_sewa.php              # Server-side logic untuk transaksi penyewaan
├── riwayat_admin.php            # Log transaksi keseluruhan (sisi admin)
├── riwayat_user.php             # Log transaksi personal (sisi pelanggan)
├── simpan_transaksi.php         # Eksekusi penyimpanan data ke database
├── style.css                    # Definisi styling antarmuka (UI Design)
├── user.php                     # Dashboard utama pelanggan (Katalog Motor)
└── README.md                    # Dokumentasi teknis sistem (RPL Standard)
