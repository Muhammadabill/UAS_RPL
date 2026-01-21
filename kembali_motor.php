<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // 1. Ambil data transaksi
    $q = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'");
    $tr = mysqli_fetch_assoc($q);

    if (!$tr) {
        die("Data transaksi TIDAK KETEMU di database. Periksa apakah ID $id_transaksi benar.");
    }

    $id_motor = $tr['id_motor'];
    $tgl_sewa = $tr['tgl_sewa'];
    $lama_sewa = (int)$tr['lama_sewa'];
    $tgl_skrg = date('Y-m-d');

    // 2. Hitung Deadline & Denda
    $tgl_deadline = date('Y-m-d', strtotime($tgl_sewa . " + $lama_sewa days"));
    $denda = 0;
    if (strtotime($tgl_skrg) > strtotime($tgl_deadline)) {
        $diff = strtotime($tgl_skrg) - strtotime($tgl_deadline);
        $hari = floor($diff / (60 * 60 * 24));
        $denda = $hari * 50000;
    }

    // 3. PROSES UPDATE (Kuncinya di sini)
    // Pastikan nama kolom status, tgl_dikembalikan, dan denda benar di PHPMyAdmin
    $sql = "UPDATE transaksi SET 
            status = 'Selesai', 
            tgl_dikembalikan = '$tgl_skrg', 
            denda = '$denda' 
            WHERE id_transaksi = '$id_transaksi'";

    if (mysqli_query($conn, $sql)) {
        // Cek apakah ada baris yang benar-benar berubah
        if (mysqli_affected_rows($conn) > 0) {
            // Berhasil update transaksi, sekarang balikin stok motor
            mysqli_query($conn, "UPDATE kendaraan SET stok = stok + 1 WHERE id_motor = '$id_motor'");
            
            echo "<script>
                    alert('BERHASIL! Motor Kembali. Denda: Rp " . number_format($denda, 0, ',', '.') . "');
                    window.location='riwayat_admin.php';
                  </script>";
        } else {
            die("DATABASE TIDAK BERUBAH: Query jalan, tapi tidak ada data yang diupdate. Mungkin statusnya sudah 'Selesai' sebelumnya atau ID salah.");
        }
    } else {
        // Tampilkan error SQL yang sebenarnya
        die("GAGAL UPDATE DATABASE! Pesan Error: " . mysqli_error($conn));
    }
} else {
    die("ID TIDAK DITEMUKAN: Kamu masuk ke halaman ini tanpa membawa ID transaksi.");
}
?>