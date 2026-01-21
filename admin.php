<?php
include 'koneksi.php';
session_start();

// Proteksi Login Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

// Data Master Motor
$master_motor = [
    "HONDA" => ["Honda Scoopy", "Honda Stylo", "Honda Vario", "Honda PCX", "Honda ADV"],
    "YAMAHA" => ["Yamaha Aerox", "Yamaha Mio", "Yamaha NMAX", "Yamaha R15", "Yamaha R25"],
    "VESPA" => ["Vespa Primavera", "Vespa Sprint", "Vespa GTS", "Vespa GTV", "Vespa 946"],
    "KAWASAKI" => ["Kawasaki Ninja 250", "Kawasaki Ninja H2", "Kawasaki KX250", "Kawasaki KX450", "Kawasaki Z125"],
    "ROYAL ENFIELD" => ["RE Classic 350", "RE Hunter 350", "RE Shotgun 650", "RE Meteor 650", "RE Himalayan 450"]
];

// --- LOGIKA SIMPAN MOTOR (TAMBAH) ---
if (isset($_POST['simpan_motor'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['pilih_motor']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    mysqli_query($conn, "INSERT INTO kendaraan (nama, harga_sewa, stok) VALUES ('$nama', '$harga', '$stok')");
    header("Location: admin.php");
}

// --- LOGIKA UPDATE MOTOR (EDIT) ---
if (isset($_POST['update_motor'])) {
    $id = $_POST['id_motor'];
    $harga = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    mysqli_query($conn, "UPDATE kendaraan SET harga_sewa='$harga', stok='$stok' WHERE id_motor='$id'");
    header("Location: admin.php");
}

// --- LOGIKA HAPUS MOTOR ---
if (isset($_GET['hapus_motor'])) {
    $id_m = mysqli_real_escape_string($conn, $_GET['hapus_motor']);
    mysqli_query($conn, "DELETE FROM kendaraan WHERE id_motor = $id_m");
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Inventory Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #1e1e1e; border: none; border-radius: 15px; }
        .navbar-admin { background-color: #000; border-bottom: 2px solid #dc3545; padding: 10px 0; }
        .btn-nav { font-weight: bold; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-dark { background-color: #1e1e1e !important; }
        .btn-outline-edit { border: 1px solid #ffc107; color: #ffc107; font-weight: bold; }
        .btn-outline-edit:hover { background-color: #ffc107; color: #000; }
        .modal-content { background-color: #1e1e1e; color: white; border: 1px solid #dc3545; border-radius: 15px; }
        .modal-header, .modal-footer { border: none; }
        .form-control, .form-select { background-color: #2c2c2c; border: 1px solid #444; color: white; }
        .form-control:focus { background-color: #2c2c2c; color: white; border-color: #dc3545; box-shadow: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-admin mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin.php">🛠️ ADMIN GASSKEUN</a>
        <div class="d-flex gap-2">
            <a href="data_pelanggan.php" class="btn btn-sm btn-outline-info btn-nav">PELANGGAN</a>
            <a href="monitoring.php" class="btn btn-sm btn-outline-danger btn-nav">MONITORING</a>
            <a href="laporan_pendapatan.php" class="btn btn-sm btn-outline-warning btn-nav">LAPORAN</a>
            <a href="logout.php" class="btn btn-sm btn-danger btn-nav text-white">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-danger mb-0 text-uppercase">🏍️ Inventory Control</h3>
            <button class="btn btn-primary fw-bold px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalTambah">+ TAMBAH UNIT</button>
        </div>
        
        <div class="table-responsive border border-secondary rounded-3">
            <table class="table table-dark table-hover align-middle text-center mb-0">
                <thead>
                    <tr class="table-secondary text-dark">
                        <th class="ps-3 text-start">Nama Unit Motor</th>
                        <th>Harga Sewa</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $motor = mysqli_query($conn, "SELECT * FROM kendaraan ORDER BY nama ASC");
                    while($m = mysqli_fetch_assoc($motor)) {
                    ?>
                    <tr>
                        <td class="ps-3 fw-bold text-start text-danger"><?= strtoupper($m['nama']) ?></td>
                        <td>Rp <?= number_format($m['harga_sewa'], 0, ',', '.') ?></td>
                        <td><?= $m['stok'] ?> Unit</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-edit px-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $m['id_motor'] ?>"
                                        data-nama="<?= $m['nama'] ?>"
                                        data-harga="<?= $m['harga_sewa'] ?>"
                                        data-stok="<?= $m['stok'] ?>">EDIT</button>
                                <a href="admin.php?hapus_motor=<?= $m['id_motor'] ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Hapus unit ini?')">HAPUS</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header"><h5 class="modal-title fw-bold text-danger"> TAMBAH UNIT</h5></div>
      <form action="admin.php" method="POST">
        <div class="modal-body">
            <label class="small fw-bold text-white-50">PILIH MODEL</label>
            <select name="pilih_motor" class="form-select mb-3" required>
                <?php foreach($master_motor as $brand => $models) { ?>
                    <optgroup label="<?= $brand ?>">
                        <?php foreach($models as $m) { echo "<option value='$m'>$m</option>"; } ?>
                    </optgroup>
                <?php } ?>
            </select>
            <label class="small fw-bold text-white-50">HARGA SEWA / HARI</label>
            <input type="number" name="harga_sewa" class="form-control mb-3" required>
            <label class="small fw-bold text-white-50">STOK</label>
            <input type="number" name="stok" class="form-control" value="1" min="1" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">BATAL</button>
            <button type="submit" name="simpan_motor" class="btn btn-danger px-4">SIMPAN</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header"><h5 class="modal-title fw-bold text-warning">EDIT UNIT</h5></div>
      <form action="admin.php" method="POST">
        <input type="hidden" name="id_motor" id="edit-id">
        <div class="modal-body">
            <label class="small fw-bold text-white-50">NAMA MOTOR (Locked)</label>
            <input type="text" id="edit-nama" class="form-control mb-3" readonly>
            <label class="small fw-bold text-white-50">HARGA SEWA BARU</label>
            <input type="number" name="harga_sewa" id="edit-harga" class="form-control mb-3" required>
            <label class="small fw-bold text-white-50">JUMLAH STOK</label>
            <input type="number" name="stok" id="edit-stok" class="form-control" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">BATAL</button>
            <button type="submit" name="update_motor" class="btn btn-warning px-4 text-dark fw-bold">UPDATE</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script untuk memindahkan data dari Tabel ke Modal Edit secara otomatis
    var modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-nama').value = button.getAttribute('data-nama');
        document.getElementById('edit-harga').value = button.getAttribute('data-harga');
        document.getElementById('edit-stok').value = button.getAttribute('data-stok');
    });
</script>
</body>
</html>