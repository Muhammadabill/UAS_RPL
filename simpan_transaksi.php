<?php
include 'koneksi.php';
session_start();
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_motor   = $_POST['id_motor'];
    $tgl_sewa   = $_POST['tgl_sewa'];
    $lama_sewa  = $_POST['lama_sewa'];
    $jaminan    = $_POST['jaminan'];
    $metode     = $_POST['metode_bayar'];
    $username   = $_SESSION['username'];

    $q_motor = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_motor = '$id_motor'");
    $d_motor = mysqli_fetch_assoc($q_motor);
    
    $total_bayar = $d_motor['harga_sewa'] * $lama_sewa;
    $no_invoice = "INV-" . strtoupper(substr(md5(time()), 0, 6));
    $tgl_kembali = date('Y-m-d', strtotime("+$lama_sewa days", strtotime($tgl_sewa)));

    $query_simpan = "INSERT INTO transaksi (no_invoice, username, id_motor, tgl_sewa, tgl_kembali, lama_sewa, jaminan, metode_bayar, total, status) 
                     VALUES ('$no_invoice', '$username', '$id_motor', '$tgl_sewa', '$tgl_kembali', '$lama_sewa', '$jaminan', '$metode', '$total_bayar', 'Proses')";

    if (mysqli_query($conn, $query_simpan)) {
        mysqli_query($conn, "UPDATE kendaraan SET stok = stok - 1 WHERE id_motor = '$id_motor'");
    } else {
        die("ERROR: " . mysqli_error($conn));
    }
} else {
    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $no_invoice ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; font-family: 'Courier New', monospace; }
        .receipt { 
            background: #fff; 
            width: 360px; 
            margin: 30px auto; 
            padding: 20px; 
            border: 1px solid #ddd;
            position: relative;
        }
        /* Efek Gerigi Struk */
        .receipt::after {
            content: "";
            position: absolute;
            bottom: -10px; left: 0; right: 0;
            height: 10px;
            background: linear-gradient(-45deg, #fff 5px, transparent 0), linear-gradient(45deg, #fff 5px, transparent 0);
            background-size: 10px 10px;
        }
        .line { border-top: 1px dashed #000; margin: 12px 0; }
        .item-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; }
        
        /* Total Bayar Tanpa Background */
        .total-display { 
            text-align: center; 
            padding: 10px 0; 
            margin: 5px 0;
            font-size: 24px;
            font-weight: 900;
            color: #000;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .important-text { color: #ffc107; font-size: 11px; text-align: center; margin-top: 15px; }
        @media print { .no-print { display: none; } body { background: #fff; } .receipt { margin: 0 auto; border: none; } }
    </style>
</head>
<body>

<div class="receipt">
    <div class="text-center">
        <h5 class="fw-bold mb-0">🛠️ GASSKEUN RENTAL</h5>
        <small class="text-muted">Struk Penyewaan Resmi</small><br>
        <small class="fw-bold">#<?= $no_invoice ?></small>
    </div>

    <div class="line"></div>

    <div class="item-row"><span>Customer:</span> <span><?= strtoupper($username) ?></span></div>
    <div class="item-row"><span>Unit:</span> <span><?= strtoupper($d_motor['nama']) ?></span></div>
    <div class="item-row"><span>Durasi:</span> <span><?= $lama_sewa ?> Hari</span></div>
    <div class="item-row"><span>Jaminan:</span> <span><?= $jaminan ?></span></div>
    
    <div class="line"></div>

    <div class="item-row"><span>Tgl Sewa:</span> <span><?= date('d/m/y', strtotime($tgl_sewa)) ?></span></div>
    <div class="item-row fw-bold text-danger"><span>Kembali:</span> <span><?= date('d/m/y', strtotime($tgl_kembali)) ?></span></div>
    <div class="item-row"><span>Metode:</span> <span><?= $metode ?></span></div>

    <div class="total-display">
        Rp <?= number_format($total_bayar, 0, ',', '.') ?>
    </div>

    <div class="important-text">
        <strong>⚠️ PENTING</strong><br>
        Simpan struk & tunjukkan ke Admin saat pengembalian<br>
        sebagai bukti transaksi yang sah.
    </div>

    <div class="no-print mt-4 d-flex gap-2">
        <a href="user.php" class="btn btn-outline-dark btn-sm w-100 fw-bold">KEMBALI</a>
        <button onclick="window.print()" class="btn btn-danger btn-sm w-100 fw-bold">CETAK</button>
    </div>
</div>

</body>
</html>