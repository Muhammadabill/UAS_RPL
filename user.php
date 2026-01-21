<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
session_start();

// Cek apakah yang login benar-benar user
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Ambil filter brand dari URL (default: SEMUA UNIT)
$filter = isset($_GET['brand']) ? $_GET['brand'] : 'SEMUA UNIT';

// Query Dasar: Menampilkan semua unit
$query_sql = "SELECT * FROM kendaraan WHERE 1=1";

// Jika filter diaktifkan, tambahkan kondisi WHERE
if ($filter != 'SEMUA UNIT') {
    $safe_filter = mysqli_real_escape_string($conn, $filter);
    $query_sql .= " AND nama LIKE '%$safe_filter%'";
}

$query_sql .= " ORDER BY nama ASC";

// Jalankan Query
$res = mysqli_query($conn, $query_sql);

if (!$res) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Motor Gasskeun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-red: #dc3545; --main-dark: #121212; }
        body { background-color: #f4f4f4; font-family: 'Segoe UI', sans-serif; }
        
        /* Navbar Styling */
        .navbar-custom { background-color: #000; border-bottom: 3px solid var(--main-red); padding: 15px 0; }
        .navbar-brand { font-weight: 800; color: white !important; text-transform: uppercase; }
        
        /* Sidebar Styling */
        .sidebar { background: white; border-radius: 8px; border: 1px solid #ddd; overflow: hidden; }
        .sidebar-header { background: #f8f9fa; padding: 15px; font-weight: 800; border-bottom: 1px solid #ddd; }
        .list-group-item { border: none; padding: 12px 20px; font-weight: bold; color: #444; text-decoration: none !important; transition: 0.2s; }
        .list-group-item:hover { background: #fff5f5; color: var(--main-red); }
        .list-group-item.active { background: transparent !important; color: var(--main-red) !important; border-left: 4px solid var(--main-red); }
        
        /* Product Card Styling */
        .card-product { border: none; border-radius: 12px; background: white; padding: 15px; height: 100%; display: flex; flex-direction: column; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); position: relative; }
        .card-product:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .img-container { width: 100%; height: 180px; background: #eee; border-radius: 8px; overflow: hidden; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; position: relative; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        
        .badge-stok { position: absolute; bottom: 8px; right: 8px; font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        
        .product-title { font-size: 15px; font-weight: 800; color: #222; text-transform: uppercase; min-height: 45px; }
        .product-price { color: var(--main-red); font-size: 18px; font-weight: 800; margin-bottom: 15px; }
        
        /* Button Styling */
        .btn-beli { background-color: var(--main-red); color: white; font-weight: bold; border-radius: 6px; width: 100%; border: none; padding: 10px; text-transform: uppercase; margin-top: auto; transition: 0.3s; }
        .btn-beli:hover { background-color: #a71d2a; }
        .btn-habis { background-color: #6c757d; color: white; font-weight: bold; border-radius: 6px; width: 100%; border: none; padding: 10px; text-transform: uppercase; margin-top: auto; cursor: not-allowed; }
        .btn-riwayat { border: 1px solid white; color: white; font-weight: bold; transition: 0.3s; }
        .btn-riwayat:hover { background: white; color: black; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
    <div class="container">
        <a class="navbar-brand" href="user.php">🛠️ GASSKEUN RENTAL</a>
        <div class="d-flex align-items-center gap-2">
            <span class="text-white small d-none d-md-inline me-2">Halo, <b><?= strtoupper($_SESSION['username']) ?></b></span>
            
            <a href="riwayat_user.php" class="btn btn-sm btn-riwayat px-3">RIWAYAT SAYA</a>
            
            <a href="logout.php" class="btn btn-sm btn-danger fw-bold px-3">KELUAR</a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar shadow-sm mb-4">
                <div class="sidebar-header">GARASI UNIT</div>
                <div class="list-group">
                    <a href="user.php?brand=SEMUA UNIT" class="list-group-item <?= ($filter == 'SEMUA UNIT') ? 'active' : '' ?>">SEMUA PRODUK</a>
                    <a href="user.php?brand=HONDA" class="list-group-item <?= ($filter == 'HONDA') ? 'active' : '' ?>">HONDA</a>
                    <a href="user.php?brand=YAMAHA" class="list-group-item <?= ($filter == 'YAMAHA') ? 'active' : '' ?>">YAMAHA</a>
                    <a href="user.php?brand=VESPA" class="list-group-item <?= ($filter == 'VESPA') ? 'active' : '' ?>">VESPA</a>
                    <a href="user.php?brand=KAWASAKI" class="list-group-item <?= ($filter == 'KAWASAKI') ? 'active' : '' ?>">KAWASAKI</a>
                    <a href="user.php?brand=RE" class="list-group-item <?= ($filter == 'RE') ? 'active' : '' ?>">ROYAL ENFIELD</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0">UNIT: <?= strtoupper($filter) ?></h4>
                <span class="badge bg-dark text-white p-2">Total: <?= mysqli_num_rows($res) ?> Unit</span>
            </div>

            <div class="row">
                <?php
                while($row = mysqli_fetch_assoc($res)) { 
                    $nama_db = $row['nama'];
                    $stok_sekarang = $row['stok'];
                    $gambar_tampil = "https://via.placeholder.com/400x300?text=NO+IMAGE";
                    
                    // Logika Pencarian Foto Berdasarkan Nama
                    $nama_variasi = [$nama_db, str_replace(' ', '_', $nama_db), str_replace(' ', '-', $nama_db), strtolower($nama_db), strtolower(str_replace(' ', '_', $nama_db)), strtolower(str_replace(' ', '-', $nama_db))];
                    $found = false;
                    foreach ($nama_variasi as $n) {
                        $extensions = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
                        foreach ($extensions as $ext) {
                            $path_cek = "img/" . $n . $ext;
                            if (file_exists($path_cek)) {
                                $gambar_tampil = $path_cek;
                                $found = true;
                                break;
                            }
                        }
                        if ($found) break;
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card-product shadow-sm">
                            <div class="img-container">
                                <img src="<?= $gambar_tampil ?>" alt="<?= $nama_db ?>" <?= ($stok_sekarang <= 0) ? 'style="filter: grayscale(1); opacity: 0.6;"' : '' ?>>
                                
                                <?php if($stok_sekarang > 0): ?>
                                    <span class="badge-stok bg-success text-white">Stok: <?= $stok_sekarang ?></span>
                                <?php else: ?>
                                    <span class="badge-stok bg-danger text-white">Habis</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-title"><?= $nama_db ?></div>
                            <div class="product-price">Rp <?= number_format($row['harga_sewa'] ?? 0, 0, ',', '.') ?> <small class="text-muted" style="font-size: 12px;">/hari</small></div>
                            
                            <?php if($stok_sekarang > 0): ?>
                                <form action="proses_sewa.php" method="POST">
                                    <input type="hidden" name="id_motor" value="<?= $row['id_motor'] ?>">
                                    <button type="submit" class="btn-beli">GAS SEWA</button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn-habis" disabled>STOK HABIS</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>