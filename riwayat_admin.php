<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
session_start();

// Cek Login Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #1e1e1e; border: none; border-radius: 15px; }
        .navbar-admin { background-color: #000; border-bottom: 2px solid #dc3545; padding: 10px 0; }
        .table-dark { background-color: #1e1e1e !important; }
        .text-gold { color: #ffd700; }
        .text-denda { color: #ff4d4d; font-weight: bold; }
        .total-box { background: #dc3545; color: white; padding: 15px; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-admin mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin.php">🛠️ ADMIN GASSKEUN</a>
        <a href="admin.php" class="btn btn-sm btn-outline-danger px-3">KEMBALI</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="card p-4 shadow-sm">
        <h3 class="fw-bold text-danger text-uppercase text-center mb-4">📜 Laporan Riwayat Transaksi</h3>
        
        <div class="table-responsive border border-secondary rounded-3">
            <table class="table table-dark table-hover text-center align-middle mb-0">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Penyewa</th>
                        <th>Unit Motor</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data, pastikan kolom denda dipanggil
                    $query = "SELECT t.*, k.nama as motor_nama 
                              FROM transaksi t 
                              JOIN kendaraan k ON t.id_motor = k.id_motor 
                              ORDER BY t.id_transaksi DESC";
                    
                    $res = mysqli_query($conn, $query);
                    $grand_total = 0; // Untuk menghitung total pendapatan di akhir

                    if (!$res) {
                        echo "<tr><td colspan='7' class='py-4 text-warning'>Kesalahan Database: " . mysqli_error($conn) . "</td></tr>";
                    } elseif (mysqli_num_rows($res) == 0) {
                        echo "<tr><td colspan='7' class='py-4 text-muted'>Belum ada riwayat transaksi.</td></tr>";
                    } else {
                        while($d = mysqli_fetch_assoc($res)) {
                            $status_selesai = ($d['status'] == 'Selesai');
                            $status_color = $status_selesai ? 'bg-success' : 'bg-warning text-dark';
                            
                            // Logika Tanggal
                            $tgl_kembali = (!empty($d['tgl_dikembalikan']) && $d['tgl_dikembalikan'] != '0000-00-00') 
                                           ? date('d/m/Y', strtotime($d['tgl_dikembalikan'])) 
                                           : "-";

                            // Logika Perhitungan (Pastikan kolom total dan denda ada di DB)
                            $harga_dasar = isset($d['total']) ? (int)$d['total'] : 0;
                            $denda_nilai = isset($d['denda']) ? (int)$d['denda'] : 0;
                            $total_akhir = $harga_dasar + $denda_nilai;
                            
                            // Tambahkan ke grand total hanya jika sudah selesai
                            if($status_selesai) $grand_total += $total_akhir;
                    ?>
                    <tr>
                        <td class="text-gold fw-bold">#<?= $d['no_invoice'] ?></td>
                        <td><?= strtoupper($d['username']) ?></td>
                        <td><?= $d['motor_nama'] ?></td>
                        <td class="text-info"><?= $tgl_kembali ?></td>
                        <td class="<?= ($denda_nilai > 0) ? 'text-denda' : 'text-muted' ?>">
                            Rp <?= number_format($denda_nilai, 0, ',', '.') ?>
                        </td>
                        <td class="fw-bold text-white">
                            Rp <?= number_format($total_akhir, 0, ',', '.') ?>
                        </td>
                        <td>
                            <span class="badge <?= $status_color ?>">
                                <?= $status_selesai ? 'BERHASIL' : 'AKTIF' ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } 
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6 offset-md-6 text-end">
                <div class="total-box">
                    TOTAL PENDAPATAN SELESAI: Rp <?= number_format($grand_total, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <div class="mt-3 small text-muted text-end">
            * Total Bayar sudah termasuk akumulasi denda (jika ada).
        </div>
    </div>
</div>

</body>
</html>