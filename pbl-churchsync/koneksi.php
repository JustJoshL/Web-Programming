<?php
$host       = "localhost"; 
$username   = "root"; 
$password   = ""; 
$database   = "db_churchsync"; 

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Yah, koneksi database gagal: " . mysqli_connect_error());
}

?>