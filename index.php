<?php
include 'koneksi.php';
session_start();

// 1. LOGIKA REGISTRASI
if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $pass = $_POST['password']; 

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
    } else {
        $query = "INSERT INTO users (username, password, nama_lengkap, no_hp, role) VALUES ('$user', '$pass', '$nama', '$no_hp', 'user')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.');</script>";
        }
    }
}

// 2. LOGIKA LOGIN (Perbaikan di sini)
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']); // Tambahkan escape string

    // Ambil data berdasarkan username
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if (mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        
        // Cek kecocokan password
        if ($pass === $row['password']) {
            // Set semua Session yang dibutuhkan
            $_SESSION['id_user']  = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role'];

            // Redirect berdasarkan role
            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            echo "<script>alert('Password Salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gasskeun Rental - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-dark-custom { background-color: #121212; }
        .login-card { width: 100%; max-width: 400px; border-radius: 20px; border: none; }
        .switch-link { cursor: pointer; color: #dc3545; font-weight: bold; }
        .hidden { display: none; }
        .page-title { font-size: 1.2rem; font-weight: 800; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .form-control-sm-custom { padding: 0.5rem; font-size: 0.9rem; }
    </style>
</head>
<body class="bg-dark-custom">

<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow-lg login-card">
        <div class="text-center mb-3">
            <h2 class="fw-bold text-danger mb-0">GASSKEUN</h2>
            <p class="text-muted small">Rental Motor Tercepat</p>
        </div>

        <div class="text-center"><h3 id="head-title" class="page-title">LOGIN</h3></div>

        <div id="form-login">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control bg-light form-control-sm-custom" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control bg-light form-control-sm-custom" required>
                </div>
                <button type="submit" name="login" class="btn btn-danger w-100 fw-bold">MASUK</button>
            </form>
            <div class="text-center mt-3">
                <small>Belum Punya Akun? <span class="switch-link" onclick="tampilDaftar()">Daftar</span></small>
            </div>
        </div>

        <div id="form-daftar" class="hidden">
            <form action="" method="POST">
                <div class="mb-2">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control bg-light form-control-sm-custom" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="form-label small fw-bold">WhatsApp</label>
                        <input type="number" name="no_hp" class="form-control bg-light form-control-sm-custom" required>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control bg-light form-control-sm-custom" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control bg-light form-control-sm-custom" required>
                </div>
                <button type="submit" name="register" class="btn btn-danger w-100 fw-bold">DAFTAR SEKARANG</button>
            </form>
            <div class="text-center mt-3">
                <small>Sudah Punya Akun? <span class="switch-link" onclick="tampilLogin()">Login</span></small>
            </div>
        </div>
    </div>
</div>

<script>
    function tampilDaftar() {
        document.getElementById('form-login').classList.add('hidden');
        document.getElementById('form-daftar').classList.remove('hidden');
        document.getElementById('head-title').innerText = "DAFTAR AKUN";
    }
    function tampilLogin() {
        document.getElementById('form-daftar').classList.add('hidden');
        document.getElementById('form-login').classList.remove('hidden');
        document.getElementById('head-title').innerText = "LOGIN";
    }
</script>
</body>
</html>