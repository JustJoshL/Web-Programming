<?php
session_start();
include '../koneksi.php';
/** @var mysqli $conn */

$id = $_POST['id_jadwal'];
$kategori = $_POST['kategori_ibadah'];
$id_cabang = $_POST['id_cabang'];
$tanggal = $_POST['tanggal'];
$waktu = $_POST['waktu'];

$waktu_pelaksanaan = $tanggal . " " . $waktu . ":00"; 

$query = "UPDATE jadwal_ibadah SET 
            kategori_ibadah = '$kategori', 
            waktu_pelaksanaan = '$waktu_pelaksanaan', 
            id_cabang = '$id_cabang' 
          WHERE id_jadwal = '$id'";

if (mysqli_query($conn, $query)) {
    header("location: jadwal_admin_up.php?pesan=sukses_edit");
} else {
    echo "Gagal ngedit bung: " . mysqli_error($conn);
}
?>