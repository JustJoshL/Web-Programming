<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_cabang = $_SESSION['id_cabang'];
$id_pengumuman = mysqli_real_escape_string($conn, $_GET['id']);

$cek = mysqli_query($conn, "SELECT gambar_pendukung FROM pengumuman WHERE id_pengumuman='$id_pengumuman' AND id_cabang='$id_cabang'");

if (mysqli_num_rows($cek) > 0) {
    $data = mysqli_fetch_assoc($cek);
    
    if (!empty($data['gambar_pendukung']) && file_exists('../uploads/' . $data['gambar_pendukung'])) {
        unlink('../uploads/' . $data['gambar_pendukung']);
    }
    
    mysqli_query($conn, "DELETE FROM pengumuman WHERE id_pengumuman='$id_pengumuman' AND id_cabang='$id_cabang'");
    
    header("location:pengumuman_gembala.php?pesan=hapus_sukses");
} else {
    die("Error: Pengumuman tidak ditemukan atau Anda tidak memiliki akses untuk menghapusnya.");
}
?>