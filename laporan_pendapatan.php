<?php
include 'koneksi.php';
session_start();

// Cek Login Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Ambil total ringkasan dari database
$query_ringkasan = mysqli_query($conn, "SELECT 
    SUM(total) as total_sewa, 
    SUM(denda) as total_denda,
    COUNT(id_transaksi) as total_transaksi
    FROM transaksi WHERE status = 'Selesai'");
$ringkasan = mysqli_fetch_assoc($query_ringkasan);

$pendapatan_total = ($ringkasan['total_sewa'] ?? 0) + ($ringkasan['total_denda'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .navbar-admin { background-color: #000; border-bottom: 2px solid #dc3545; }
        .card-stat { 
            background: #1e1e1e; 
            border-radius: 12px; 
            padding: 25px; 
            border-width: 2px !important;
            transition: transform 0.3s ease;
        }
        .card-stat:hover { transform: translateY(-5px); }
        .table-dark { background-color: #1e1e1e !important; }
        .fw-900 { font-weight: 900; }
        @media print { .no-print { display: none; } body { background-color: white; color: black; } }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-admin mb-4 no-print">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin.php">🛠️ ADMIN GASSKEUN</a>
        <a href="admin.php" class="btn btn-sm btn-outline-light">KEMBALI</a>
    </div>
</nav>

<div class="container">
    <h2 class="fw-bold text-center text-danger mb-4 text-uppercase">💰 Laporan Pendapatan Rental</h2>

    <div class="row g-3 mb-4 no-print">
        <div class="col-md-4">
            <div class="card-stat text-center border-primary shadow">
                <small class="text-primary fw-bold text-uppercase" style="letter-spacing: 1px;">Total Sewa</small>
                <h3 class="text-primary fw-900 mt-2" style="text-shadow: 0 0 10px rgba(13, 110, 253, 0.4);">
                    Rp <?= number_format($ringkasan['total_sewa'] ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-stat text-center border-warning shadow">
                <small class="text-warning fw-bold text-uppercase" style="letter-spacing: 1px;">Total Denda</small>
                <h3 class="text-warning fw-900 mt-2" style="text-shadow: 0 0 10px rgba(255, 193, 7, 0.4);">
                    Rp <?= number_format($ringkasan['total_denda'] ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-stat text-center border-success shadow">
                <small class="text-success fw-bold text-uppercase" style="letter-spacing: 1px;">Total Pendapatan Bersih</small>
                <h3 class="text-success fw-900 mt-2" style="text-shadow: 0 0 10px rgba(25, 135, 84, 0.4);">
                    Rp <?= number_format($pendapatan_total, 0, ',', '.') ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-dark mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0 text-white-50 small">RINCIAN TRANSAKSI SELESAI</h5>
                <button onclick="window.print()" class="btn btn-sm btn-danger no-print fw-bold px-3">🖨️ CETAK LAPORAN</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle text-center">
                    <thead>
                        <tr class="text-secondary small border-bottom border-secondary">
                            <th>INVOICE</th>
                            <th>PELANGGAN</th>
                            <th>MOTOR</th>
                            <th>TGL SELESAI</th>
                            <th>BIAYA SEWA</th>
                            <th>DENDA</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_history = "SELECT t.*, k.nama as motor_nama 
                                         FROM transaksi t 
                                         JOIN kendaraan k ON t.id_motor = k.id_motor 
                                         WHERE t.status = 'Selesai' 
                                         ORDER BY t.tgl_dikembalikan DESC";
                        $res = mysqli_query($conn, $query_history);

                        if (mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                                $total_inv = $row['total'] + $row['denda'];
                        ?>
                        <tr>
                            <td class="small fw-bold text-danger">#<?= $row['no_invoice'] ?></td>
                            <td><?= strtoupper($row['username']) ?></td>
                            <td><?= $row['motor_nama'] ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_dikembalikan'])) ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td class="text-warning">Rp <?= number_format($row['denda'], 0, ',', '.') ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($total_inv, 0, ',', '.') ?></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-4 text-muted small'>Belum ada transaksi selesai.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4 no-print pb-5">
        <a href="admin.php" class="text-decoration-none text-muted small">← Kembali ke Inventory Control</a>
    </div>
</div>

</body>
</html>