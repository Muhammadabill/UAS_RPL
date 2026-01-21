<?php
include 'koneksi.php';
session_start();

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Logika Hapus Pelanggan
if (isset($_GET['hapus'])) {
    $username_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Pastikan nama tabel 'users' sesuai dengan database kamu
    $del = mysqli_query($conn, "DELETE FROM users WHERE username = '$username_hapus' AND role = 'user'");
    
    if ($del) {
        header("Location: data_pelanggan.php?pesan=dihapus");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Pelanggan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #000; border-bottom: 3px solid #dc3545; }
        .card { background-color: #1e1e1e; border: none; border-radius: 15px; color: white; }
        .table { color: white; border-color: #444; }
        .table thead { background-color: #dc3545; color: white; }
        .text-info-custom { color: #0dcaf0; font-weight: bold; }
        .text-whatsapp { color: #25d366; font-weight: bold; } /* Warna Hijau WA */
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin.php">🛠️ GASSKEUN RENTAL</a>
        <a href="admin.php" class="btn btn-sm btn-outline-light">KEMBALI</a>
    </div>
</nav>

<div class="container">
    <div class="card p-4 shadow">
        <h3 class="fw-bold mb-4 text-danger text-uppercase">👥 Daftar Pelanggan</h3>

        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-success bg-success text-white border-0">Data berhasil dihapus!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th>Username</th>
                        <th>WhatsApp / No. HP</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    
                    // Mengambil username, role, dan no_hp
                    $query_text = "SELECT username, role, no_hp FROM users WHERE role = 'user'";
                    $query = mysqli_query($conn, $query_text);

                    if (!$query) {
                        echo "<tr><td colspan='5' class='text-center text-warning p-4'>
                                <b>Error:</b> " . mysqli_error($conn) . " <br>
                                <small>Pastikan tabel 'users' memiliki kolom 'no_hp'</small>
                              </td></tr>";
                    } else {
                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='5' class='text-center text-muted p-4'>Belum ada pelanggan terdaftar.</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-info-custom"><?= $row['username']; ?></td>
                        
                        <td class="text-whatsapp">
                            <?= (!empty($row['no_hp'])) ? $row['no_hp'] : '<span class="text-muted small">Tidak ada data</span>'; ?>
                        </td>

                        <td><span class="badge bg-secondary px-3"><?= strtoupper($row['role']); ?></span></td>
                        <td>
                            <a href="data_pelanggan.php?hapus=<?= $row['username']; ?>" 
                               class="btn btn-sm btn-danger px-3" 
                               onclick="return confirm('Yakin ingin menghapus user <?= $row['username']; ?>?')">
                               Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                            }
                        }
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>