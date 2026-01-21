# 🏍️ Gasskeun Rental Motor

Gasskeun Rental Motor adalah sebuah sistem informasi berbasis web yang dirancang menggunakan prinsip-prinsip Rekayasa Perangkat Lunak (RPL) untuk mendigitalisasi seluruh ekosistem bisnis persewaan sepeda motor. Proyek ini dikembangkan sebagai solusi terhadap kompleksitas pengelolaan data manual, bertujuan untuk menciptakan proses bisnis yang lebih efisien, transparan, dan terukur.

## 🚀 Link Akses Website (Live)
Website ini sudah dideploy dan dapat diakses secara publik melalui:
👉 [http://gasskeun-rental.infinityfreeapp.com](http://gasskeun-rental.infinityfreeapp.com)

---

## 📝 Penjelasan Proyek
**Gasskeun Rental** adalah platform manajemen persewaan motor yang menghubungkan penyedia jasa dengan pelanggan melalui sistem berbasis cloud. Aplikasi ini fokus pada kemudahan transaksi dan pemantauan stok unit secara real-time.

### Fitur Utama:
* **Sistem Login Multi-user**: Membedakan hak akses antara Admin (Pengelola) dan User (Pelanggan).
* **Katalog Motor Real-time**: Menampilkan daftar motor yang tersedia beserta harganya secara dinamis.
* **Manajemen Transaksi**: Proses booking atau sewa yang langsung memotong stok ketersediaan unit.
* **Panel Admin**: Fitur monitoring data pelanggan, manajemen pengembalian motor, dan laporan pendapatan harian/bulanan.
* **Riwayat Sewa**: Pelanggan dapat memantau daftar motor yang sedang atau pernah disewa.
* **Desain Responsif**: Antarmuka yang ramah pengguna baik diakses melalui Desktop maupun Smartphone.

---

## 💻 Spesifikasi Teknologi
* **Bahasa Pemrograman**: PHP 8.x (Native)
* **Database**: MySQL
* **Frontend**: HTML5, CSS3, Bootstrap 5
* **Hosting**: InfinityFree (Apache Server)

---

## 📂 Struktur Folder Proyek
Berikut adalah susunan file utama dalam proyek ini:

```text
Gasskeun-Rental-Motor/
├── assets/
│   ├── css/
│   │   └── style.css            # Pengaturan UI/Layout
│   └── img/                     # Galeri foto unit motor
├── database/
│   └── gasskeun_rental.sql      # Backup database MySQL
├── admin/                       # Modul fungsionalitas admin
│   ├── admin.php
│   ├── data_pelanggan.php
│   ├── kembali_motor.php
│   └── laporan_pendapatan.php
├── index.php                    # Halaman utama & Login
├── koneksi.php                  # Konfigurasi database hosting
├── user.php                     # Katalog motor pelanggan
├── proses_sewa.php              # Logika transaksi
└── logout.php                   # Fitur keluar sistem
