<?php
$host       = "localhost"; 
$username   = "root"; 
$password   = ""; 
$database   = "db_churchsync"; 

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Aduh bung, koneksi database gagal nih: " . mysqli_connect_error());
}

// echo "Koneksi ke db_churchsync Sukses Mantap!"; 
?>