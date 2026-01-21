<?php
session_start(); // Memulai sesi yang sedang aktif
session_destroy(); // Menghapus semua data sesi (logout)

// Mengarahkan kembali ke halaman login utama
header("Location: index.php");
exit();
?>