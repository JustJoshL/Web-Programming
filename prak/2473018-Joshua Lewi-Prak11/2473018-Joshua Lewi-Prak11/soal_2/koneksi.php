<?php
$host = "localhost";
$usn = "root";
$pass = "";
$db = "db_sekolah";
$conn = mysqli_connect($host, $usn, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>