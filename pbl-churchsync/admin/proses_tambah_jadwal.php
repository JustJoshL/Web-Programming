<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

$kategori = $_POST['kategori_ibadah'];
$id_cabang = $_POST['id_cabang'];
$tanggal = $_POST['tanggal'];
$waktu = $_POST['waktu'];

$waktu_pelaksanaan = $tanggal . " " . $waktu . ":00"; 

date_default_timezone_set('Asia/Jakarta');
$tanggal_sekarang = date('Y-m-d');

if ($tanggal < $tanggal_sekarang) {
    header("location: jadwal_admin_up.php?pesan=gagal_tanggal_lewat");
    exit();
}

$cek_dobel = mysqli_query($conn, "
    SELECT id_jadwal FROM jadwal_ibadah 
    WHERE kategori_ibadah = '$kategori' 
    AND waktu_pelaksanaan = '$waktu_pelaksanaan' 
    AND id_cabang = '$id_cabang'
");

if (mysqli_num_rows($cek_dobel) > 0) {
    header("location: jadwal_admin_up.php?pesan=gagal_dobel");
    exit();
    
} else {
    $query_jadwal = "INSERT INTO jadwal_ibadah (kategori_ibadah, waktu_pelaksanaan, id_cabang) 
                     VALUES ('$kategori', '$waktu_pelaksanaan', '$id_cabang')";

    if (mysqli_query($conn, $query_jadwal)) {    
        header("location: jadwal_admin_up.php?pesan=sukses_tambah");
        exit(); 
    } else {
        echo "Gagal bung: " . mysqli_error($conn);
    }
}
?>