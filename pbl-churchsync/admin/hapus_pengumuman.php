<?php
session_start();
/** @var mysqli $conn */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id = $_GET['id'];

$query_cek_gambar = mysqli_query($conn, "SELECT gambar_pendukung FROM pengumuman WHERE id_pengumuman = '$id'");
$data = mysqli_fetch_assoc($query_cek_gambar);
$nama_gambar = $data['gambar_pendukung'];

if ($nama_gambar != "") {
    $lokasi_file = "../uploads/" . $nama_gambar;
    
    if (file_exists($lokasi_file)) {
        unlink($lokasi_file); // fungsi PHP buat ngehapus/menghancurkan file fisik
    }
}

$query_hapus = "DELETE FROM pengumuman WHERE id_pengumuman = '$id'";

if (mysqli_query($conn, $query_hapus)) {
    header("location: pengumuman_admin.php?pesan=sukses_hapus");
} else {
    echo "Waduh gagal hapus bung: " . mysqli_error($conn);
}
?>