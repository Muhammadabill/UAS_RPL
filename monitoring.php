<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
session_start();

// --- KUNCI: Sinkronisasi Waktu Dunia Nyata ---
date_default_timezone_set('Asia/Jakarta');

// Cek Login Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Logika ketika tombol "Selesai" diklik
if (isset($_POST['selesaikan_sewa'])) {
    $id_t = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
    $id_m = mysqli_real_escape_string($conn, $_POST['id_motor']);
    $tgl_sekarang = date('Y-m-d'); 

    // Ambil data transaksi lengkap
    $cek_transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_t'");
    $data_t = mysqli_fetch_assoc($cek_transaksi);
    
    if ($data_t) {
        // Logika Backup: Jika tgl_kembali di DB kosong, hitung manual untuk denda
        if (!empty($data_t['tgl_kembali']) && $data_t['tgl_kembali'] != '0000-00-00') {
            $tgl_deadline = $data_t['tgl_kembali'];
        } else {
            $tgl_deadline = date('Y-m-d', strtotime("+".$data_t['lama_sewa']." days", strtotime($data_t['tgl_sewa'])));
        }

        $denda = 0;
        $deadline = new DateTime($tgl_deadline);
        $hari_ini = new DateTime($tgl_sekarang);

        if ($hari_ini > $deadline) {
            $selisih = $hari_ini->diff($deadline);
            $jumlah_hari = $selisih->days;
            $denda = $jumlah_hari * 50000; 
        }

        // Simpan hasil ke database
        $update_t = mysqli_query($conn, "UPDATE transaksi SET 
            status='Selesai', 
            denda='$denda', 
            tgl_dikembalikan='$tgl_sekarang' 
            WHERE id_transaksi='$id_t'");
        
        $update_m = mysqli_query($conn, "UPDATE kendaraan SET stok = stok + 1 WHERE id_motor='$id_m'");
        
        if ($update_t && $update_m) {
            $pesan = ($denda > 0) ? "Unit kembali! Terlambat " . $jumlah_hari . " hari. Denda Rp " . number_format($denda, 0, ',', '.') : "Unit kembali tepat waktu!";
            echo "<script>alert('$pesan'); window.location='monitoring.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Sewa Aktif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #1e1e1e; border: none; border-radius: 15px; }
        .navbar-admin { background-color: #000; border-bottom: 2px solid #dc3545; padding: 10px 0; }
        .table-dark { background-color: #1e1e1e !important; }
        .text-info-custom { color: #0dcaf0; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-admin mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin.php">🛠️ ADMIN GASSKEUN</a>
        <div class="d-flex align-items-center gap-3">
            <div class="text-white-50 small">Waktu Server: <b class="text-white"><?= date('d M Y') ?></b></div>
            <a href="admin.php" class="btn btn-sm btn-outline-danger px-3">KEMBALI</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card p-4 shadow-sm">
        <h3 class="fw-bold text-danger text-uppercase text-center mb-4">📊 Monitoring Sewa Aktif</h3>
        
        <div class="table-responsive border border-secondary rounded-3">
            <table class="table table-dark table-hover text-center align-middle mb-0">
                <thead class="table-danger text-dark">
                    <tr>
                        <th>Penyewa</th>
                        <th>Unit Motor</th>
                        <th>Deadline Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT t.*, k.nama as motor_nama 
                              FROM transaksi t 
                              JOIN kendaraan k ON t.id_motor = k.id_motor 
                              WHERE t.status = 'Proses'
                              ORDER BY t.id_transaksi DESC";
                    
                    $res = mysqli_query($conn, $query);

                    if (mysqli_num_rows($res) > 0) {
                        while($d = mysqli_fetch_assoc($res)) {
                            
                            // --- LOGIKA CERDAS: CEK TANGGAL DEADLINE ---
                            $tgl_db = $d['tgl_kembali'];
                            
                            if (!empty($tgl_db) && $tgl_db != '0000-00-00') {
                                // Jika ada di database, gunakan itu
                                $deadline_ts = strtotime($tgl_db);
                                $tgl_tampil = date('d M Y', $deadline_ts);
                            } else {
                                // Jika kosong di DB, hitung otomatis: tgl_sewa + lama_sewa
                                $deadline_ts = strtotime("+".$d['lama_sewa']." days", strtotime($d['tgl_sewa']));
                                $tgl_tampil = date('d M Y', $deadline_ts);
                            }

                            $hari_ini_ts = strtotime(date('Y-m-d'));
                            $is_late = ($hari_ini_ts > $deadline_ts);
                    ?>
                    <tr>
                        <td><span class="fw-bold text-white"><?= strtoupper($d['username']) ?></span></td>
                        <td><?= $d['motor_nama'] ?></td>
                        <td>
                            <span class="<?= $is_late ? 'text-danger fw-bold' : 'text-info-custom' ?>">
                                <?= $tgl_tampil ?>
                                <?= $is_late ? ' <br><small>(Terlambat!)</small>' : '' ?>
                            </span>
                        </td>
                        <td><span class="badge bg-warning text-dark">Sedang Digunakan</span></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id_transaksi" value="<?= $d['id_transaksi'] ?>">
                                <input type="hidden" name="id_motor" value="<?= $d['id_motor'] ?>">
                                <button type="submit" name="selesaikan_sewa" class="btn btn-sm btn-success fw-bold px-3 shadow">
                                    SELESAIKAN & HITUNG
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-4 text-muted'>Tidak ada sewa aktif saat ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>