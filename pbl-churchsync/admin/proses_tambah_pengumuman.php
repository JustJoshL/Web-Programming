<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

/** @var mysqli $conn */
include '../koneksi.php';

$judul    = $_POST['judul_pengumuman'];
$kategori = $_POST['kategori_pengumuman'];
$tanggal  = $_POST['tanggal_publikasi'];
$isi      = $_POST['isi_pengumuman'];
$status   = $_POST['status_publikasi'];
$nama_gambar = "";
$target_tipe = $_POST['target_tipe'];

$id_cabang = null;

if ($target_tipe == 'cabang') {
    $id_cabang = $_POST['id_cabang'];
}

if ($_FILES['gambar_pendukung']['name'] != '') {
    $nama_gambar = $_FILES['gambar_pendukung']['name'];

    $lokasi_sementara = $_FILES['gambar_pendukung']['tmp_name'];

    $folder_tujuan = "../uploads/" . $nama_gambar;

    move_uploaded_file($lokasi_sementara, $folder_tujuan);
}

$query_tambah = "INSERT INTO pengumuman
(
    judul_pengumuman,
    isi_pengumuman,
    tanggal_publikasi,
    status_publikasi,
    kategori_pengumuman,
    gambar_pendukung,
    target_tipe,
    id_cabang
)
VALUES
(
    '$judul',
    '$isi',
    '$tanggal',
    '$status',
    '$kategori',
    '$nama_gambar',
    '$target_tipe',
    " . ($id_cabang ? "'$id_cabang'" : "NULL") . "
)";
