<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../index.php?pesan=belum_login");
    exit();
}

/** @var mysqli $conn */
include '../koneksi.php';

$judul    = $_POST['judul_pengumuman'];
$kategori = $_POST['kategori_pengumuman'];
$tanggal  = $_POST['tanggal_publikasi'];
$isi      = $_POST['isi_pengumuman'];
$status   = $_POST['status_publikasi'];

if ($status == 'Published' && $tanggal > date('Y-m-d')) {
    $status = 'Draft';
}
$nama_gambar = "";
$target_tipe = $_POST['target_tipe'];
$id_cabang = null;

$hari_ini = date('Y-m-d');

if ($target_tipe == 'cabang') {
    $id_cabang = $_POST['id_cabang'];
}

if ($_FILES['gambar_pendukung']['name'] != '') {
    $nama_gambar = $_FILES['gambar_pendukung']['name'];
    $lokasi_sementara = $_FILES['gambar_pendukung']['tmp_name'];
    $folder_tujuan = "../uploads/" . $nama_gambar;

    move_uploaded_file($lokasi_sementara, $folder_tujuan);
}

$query_tambah = "INSERT INTO pengumuman (judul_pengumuman, isi_pengumuman, tanggal_publikasi, status_publikasi, kategori_pengumuman, gambar_pendukung, target_tipe, id_cabang) 
                 VALUES ('$judul', '$isi', '$tanggal', '$status', '$kategori', '$nama_gambar', '$target_tipe', " . ($id_cabang ? "'$id_cabang'" : "NULL") . ")";

$eksekusi = mysqli_query($conn, $query_tambah);

if ($eksekusi) {
    if ($tanggal <= $hari_ini) {
        header("Location: pengumuman_admin.php?pesan=sukses_publish");
    } else {
        header("Location: pengumuman_admin.php?pesan=sukses_jadwal&tgl=" . $tanggal);
    }
    exit(); 
} else {
    echo "Gagal menambahkan pengumuman: " . mysqli_error($conn);
}
?>