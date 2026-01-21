<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$id_motor = isset($_POST['id_motor']) ? $_POST['id_motor'] : '';
if (empty($id_motor)) {
    header("Location: user.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_motor = '$id_motor'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Unit motor tidak ditemukan!");
}

$nama_db = $data['nama'];
$harga_harian = $data['harga_sewa'];

// --- LOGIKA PENCARIAN FOTO ---
$gambar_tampil = "https://via.placeholder.com/600x400?text=NO+IMAGE";
$nama_variasi = [$nama_db, str_replace(' ', '_', $nama_db), str_replace(' ', '-', $nama_db), strtolower($nama_db), strtolower(str_replace(' ', '_', $nama_db))];

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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Sewa - <?= $nama_db ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-red: #dc3545; }
        body { background-color: #f4f4f4; font-family: 'Segoe UI', sans-serif; font-size: 12px; }
        
        /* HEADER DISAMAKAN PERSIS DENGAN USER.PHP */
        .navbar-custom { 
            background-color: #000 !important; 
            border-bottom: 3px solid var(--main-red); 
            /* Menyesuaikan padding/tinggi agar sama dengan katalog */
            padding: 15px 0; 
        }
        .navbar-brand { 
            font-weight: 800; 
            color: #fff !important; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            font-size: 20px; /* Ukuran font standar navbar katalog */
        }

        .detail-card { 
            background: white; 
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
            margin: 15px auto; 
            max-width: 800px; 
        }
        
        .img-side { width: 100%; height: 100%; object-fit: cover; min-height: 250px; }
        .form-section { padding: 15px 25px; }
        
        .va-container { 
            display: none; 
            background: #fff3f3; 
            border: 1px dashed var(--main-red); 
            padding: 8px; 
            border-radius: 6px; 
            text-align: center; 
            margin: 10px 0; 
        }
        .va-number { font-size: 18px; font-weight: 900; color: #000; display: block; line-height: 1.2; }
        
        .total-box { background: #f8f9fa; padding: 8px; border-radius: 6px; text-align: center; margin-bottom: 12px; border: 1px solid #ddd; }
        .price-text { color: var(--main-red); font-size: 22px; font-weight: 800; display: block; }
        
        .btn-gass { background: var(--main-red); color: white; font-weight: 800; padding: 10px; border: none; border-radius: 6px; width: 100%; text-transform: uppercase; }
        .btn-batal { background: #6c757d; color: white; font-weight: 800; padding: 10px; border: none; border-radius: 6px; width: 100%; text-transform: uppercase; text-decoration: none; text-align: center; display: block; }
        
        .label-custom { font-weight: 700; font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 3px; display: block; }
        hr { margin: 10px 0; }
        .form-control-sm, .form-select-sm { font-size: 12px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom mb-4">
    <div class="container">
        <a class="navbar-brand" href="user.php">🛠️ GASSKEUN RENTAL</a>
    </div>
</nav>

<div class="container">
    <div class="detail-card">
        <div class="row g-0">
            <div class="col-md-5 d-none d-md-block">
                <img src="<?= $gambar_tampil ?>" alt="<?= $nama_db ?>" class="img-side">
            </div>

            <div class="col-md-7">
                <div class="form-section">
                    <h5 class="fw-bold mb-0 text-dark"><?= strtoupper($nama_db) ?></h5>
                    <p class="text-muted mb-3 small">Lengkapi rincian sewa kendaraan.</p>
                    
                    <form action="simpan_transaksi.php" method="POST">
                        <input type="hidden" name="id_motor" value="<?= $id_motor ?>">
                        <input type="hidden" id="harga_per_hari" value="<?= $harga_harian ?>">

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="label-custom">Mulai Sewa</label>
                                <input type="date" name="tgl_sewa" class="form-control form-control-sm" required value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-6">
                                <label class="label-custom">Durasi (Hari)</label>
                                <input type="number" name="lama_sewa" id="lama_sewa" class="form-control form-control-sm" min="1" value="1" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="label-custom">Jaminan</label>
                            <select name="jaminan" class="form-select form-select-sm" required>
                                <option value="KK & KTP">KK & KTP Asli</option>
                                <option value="STNK & SIM C">STNK & SIM C Asli</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="label-custom">Metode Pembayaran</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="metode_bayar" id="bayar_cash" value="Cash" checked onclick="toggleVA(false)">
                                <label class="btn btn-sm btn-outline-dark w-50 fw-bold" for="bayar_cash">💵 CASH</label>

                                <input type="radio" class="btn-check" name="metode_bayar" id="bayar_va" value="BCA VA" onclick="toggleVA(true)">
                                <label class="btn btn-sm btn-outline-dark w-50 fw-bold" for="bayar_va">🏦 BCA VA</label>
                            </div>
                        </div>

                        <div id="info_va" class="va-container">
                            <span class="label-custom" style="margin:0;">TRANSFER KE NOMOR VA BCA</span>
                            <span class="va-number">880120220304</span>
                            <span class="fw-bold text-muted" style="font-size: 9px;">A/N GASSKEUN RENTAL</span>
                        </div>

                        <hr>

                        <div class="total-box">
                            <span class="label-custom m-0">ESTIMASI TOTAL</span>
                            <span class="price-text" id="total_bayar">Rp <?= number_format($harga_harian, 0, ',', '.') ?></span>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <a href="user.php" class="btn-batal shadow-sm"> Batal</a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn-gass shadow-sm"> Gas Sewa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputHari = document.getElementById('lama_sewa');
    const hargaHarian = document.getElementById('harga_per_hari').value;
    const displayTotal = document.getElementById('total_bayar');
    const infoVA = document.getElementById('info_va');

    inputHari.addEventListener('input', function() {
        const total = this.value * hargaHarian;
        displayTotal.innerText = total > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(total) : 'Rp 0';
    });

    function toggleVA(show) {
        infoVA.style.display = show ? 'block' : 'none';
    }
</script>

</body>
</html>