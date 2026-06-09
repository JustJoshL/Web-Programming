<?php
session_start();
include '../koneksi.php';
/** @var mysqli $conn */

$kategori = $_POST['kategori_ibadah'];
$id_cabang = $_POST['id_cabang'];
$tanggal = $_POST['tanggal'];
$waktu = $_POST['waktu'];

$waktu_pelaksanaan = $tanggal . " " . $waktu . ":00"; 

$query_jadwal = "INSERT INTO jadwal_ibadah (kategori_ibadah, waktu_pelaksanaan, id_cabang) 
                 VALUES ('$kategori', '$waktu_pelaksanaan', '$id_cabang')";

if (mysqli_query($conn, $query_jadwal)) {    
    header("location: jadwal_admin_up.php?pesan=sukses_tambah");
} else {
    echo "Gagal bung: " . mysqli_error($conn);
}
?>