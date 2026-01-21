<?php
$host = "sql208.infinityfree.com"; 
$user = "if0_40960621";            
$pass = "Salwafauziah23";    
$db   = "if0_40960621_rental";     

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>