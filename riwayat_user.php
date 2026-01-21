<?php
include 'koneksi.php';
session_start();

// Pastikan hanya user yang sudah login bisa akses
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Sewa Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-red: #dc3545; }
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #1e1e1e; border: none; border-radius: 15px; }
        
        /* Navbar Styling agar sama dengan user.php */
        .navbar-custom { background-color: #000; border-bottom: 3px solid var(--main-red); padding: 15px 0; }
        .navbar-brand { font-weight: 800; color: white !important; text-transform: uppercase; }
        
        .table-dark { background-color: #1e1e1e !important; }
        .text-gold { color: #ffd700; }
        .badge-aktif { background-color: #fd7e14; color: #000; font-weight: bold; }
        .btn-outline-light:hover { color: black; }

        /* Style khusus untuk teks catatan agar mencolok */
        .note-highlight { color: #ffd700; font-weight: 500; letter-spacing: 0.5px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom mb-4">
    <div class="container">
        <a class="navbar-brand" href="user.php">🛠️ GASSKEUN RENTAL</a>
        <a href="user.php" class="btn btn-sm btn-outline-light fw-bold px-3">KEMBALI</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-danger text-uppercase m-0">📜 Riwayat Sewa Saya</h3>
            <span class="text-muted">User: <?= strtoupper($username) ?></span>
        </div>
        
        <div class="table-responsive border border-secondary rounded-3">
            <table class="table table-dark table-hover text-center align-middle mb-0">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Unit Motor</th>
                        <th>Tgl Sewa</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query mengambil data transaksi milik user yang sedang login
                    $query = "SELECT t.*, k.nama as motor_nama 
                              FROM transaksi t 
                              JOIN kendaraan k ON t.id_motor = k.id_motor 
                              WHERE t.username = '$username'
                              ORDER BY t.id_transaksi DESC";
                    
                    $res = mysqli_query($conn, $query);

                    if (!$res || mysqli_num_rows($res) == 0) {
                        echo "<tr><td colspan='7' class='py-5 text-muted'>Belum ada riwayat transaksi. <br><a href='user.php' class='text-danger'>Sewa motor sekarang?</a></td></tr>";
                    } else {
                        while($d = mysqli_fetch_assoc($res)) {
                            $is_selesai = ($d['status'] == 'Selesai');
                            $status_label = $is_selesai ? 'BERHASIL' : 'SEDANG DISEWA';
                            $status_class = $is_selesai ? 'bg-success' : 'badge-aktif';
                            
                            // Logika tampilan tanggal kembali dan denda
                            $tgl_kembali = (!empty($d['tgl_dikembalikan']) && $d['tgl_dikembalikan'] != '0000-00-00') 
                                           ? date('d/m/Y', strtotime($d['tgl_dikembalikan'])) 
                                           : '-';
                            
                            $denda_nilai = (int)$d['denda'];
                            $total_plus_denda = (int)$d['total'] + $denda_nilai;
                    ?>
                    <tr>
                        <td class="text-gold fw-bold">#<?= $d['no_invoice'] ?></td>
                        <td><?= $d['motor_nama'] ?></td>
                        <td><?= date('d/m/Y', strtotime($d['tgl_sewa'])) ?></td>
                        
                        <td class="text-info"><?= $tgl_kembali ?></td>
                        
                        <td class="<?= ($denda_nilai > 0) ? 'text-danger fw-bold' : 'text-muted' ?>">
                            Rp <?= number_format($denda_nilai, 0, ',', '.') ?>
                        </td>
                        
                        <td class="fw-bold">
                            Rp <?= number_format($total_plus_denda, 0, ',', '.') ?>
                        </td>
                        
                        <td>
                            <span class="badge <?= $status_class ?> p-2 px-3">
                                <?= $status_label ?>
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
        
        <div class="mt-4 p-3 bg-dark rounded border border-warning">
            <small class="note-highlight">
                * <b>CATATAN:</b> Jika motor belum dikembalikan lebih dari batas waktu sewa, denda sebesar <span class="text-danger fw-bold">Rp 50.000/hari</span> akan ditambahkan secara otomatis saat motor dikembalikan.
            </small>
        </div>
    </div>
</div>

</body>
</html>