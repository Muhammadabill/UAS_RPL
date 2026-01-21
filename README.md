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
Gasskeun-Rental/
├── admin/               # Modul manajerial admin (Monitoring, Laporan, Data Pelanggan)
├── assets/              # Aset statis (CSS Frameworks, Image Assets)
├── database/            # Skema basis data .sql untuk standarisasi struktur tabel
├── index.php            # Entry point aplikasi (Autentikasi & Login)
├── user.php             # Dashboard interaktif untuk pelanggan
├── koneksi.php          # Konfigurasi gerbang komunikasi PHP dan MySQL
└── README.md            # Dokumentasi teknis sistem
