<?php
include 'koneksi.php';
session_start();

// Pastikan hanya admin yang bisa akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Logika jika Admin klik tombol "Selesai" (Motor Kembali)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'selesai') {
    $id_t = $_GET['id'];
    // 1. Update status transaksi jadi Selesai
    mysqli_query($conn, "UPDATE transaksi SET status='Selesai' WHERE id_transaksi='$id_t'");
    // 2. Jika kamu punya kolom status di tabel kendaraan, update jadi 'Tersedia'
    // mysqli_query($conn, "UPDATE kendaraan SET status='Tersedia' WHERE id_motor=(SELECT id_motor FROM transaksi WHERE id_transaksi='$id_t')");
    header("Location: admin_monitoring.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Transaksi - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-red: #dc3545; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-admin { background: #000; border-bottom: 3px solid var(--main-red); padding: 15px 0; }
        .card-table { border-radius: 10px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .table thead { background: #000; color: #fff; }
        .badge-sewa { background: #ffc107; color: #000; } /* Sedang Disewa */
        .badge-selesai { background: #198754; color: #fff; } /* Selesai */
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-admin mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🛠️ ADMIN GASSKEUN</a>
        <a href="logout.php" class="btn btn-sm btn-danger fw-bold">KELUAR</a>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">🖥️ MONITORING TRANSAKSI AKTIF</h4>
        <a href="admin_laporan.php" class="btn btn-outline-dark fw-bold">📊 LIHAT LAPORAN</a>
    </div>

    <div class="card card-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0">
                    <thead>
                        <tr>
                            <th class="ps-3">No. Invoice</th>
                            <th>Customer</th>
                            <th>Unit Motor</th>
                            <th>Tgl Sewa</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil data transaksi yang statusnya 'Disewa' atau 'Pending'
                        $query = mysqli_query($conn, "SELECT t.*, k.nama as nama_motor FROM transaksi t 
                                                     JOIN kendaraan k ON t.id_motor = k.id_motor 
                                                     WHERE t.status != 'Selesai' 
                                                     ORDER BY t.id_transaksi DESC");
                        
                        if(mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='7' class='text-center py-4 text-muted'>Tidak ada transaksi aktif saat ini.</td></tr>";
                        }

                        while($row = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr class="align-middle">
                            <td class="ps-3 fw-bold">#<?= $row['no_invoice'] ?></td>
                            <td><?= strtoupper($row['username']) ?></td>
                            <td><?= $row['nama_motor'] ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_sewa'])) ?></td>
                            <td><?= $row['lama_sewa'] ?> Hari</td>
                            <td><span class="badge badge-sewa">DISIAPKAN / DISEWA</span></td>
                            <td class="text-center">
                                <a href="admin_monitoring.php?aksi=selesai&id=<?= $row['id_transaksi'] ?>" 
                                   class="btn btn-sm btn-success fw-bold" 
                                   onclick="return confirm('Konfirmasi motor telah kembali?')">
                                   ✅ MOTOR KEMBALI
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>