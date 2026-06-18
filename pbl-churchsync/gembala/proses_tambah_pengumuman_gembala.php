<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php");
    exit();
}

include '../koneksi.php';

/** @var mysqli $conn */

$judul    = $_POST['judul_pengumuman'];
$kategori = $_POST['kategori_pengumuman'];
$tanggal  = $_POST['tanggal_publikasi'];
$isi      = $_POST['isi_pengumuman'];
$status = $_POST['status_publikasi'];
if ($status == 'Published' && $tanggal > date('Y-m-d')) {
    $status = 'Draft';
}

$nama_gambar = "";

$id_cabang = $_SESSION['id_cabang'];

if ($_FILES['gambar_pendukung']['name'] != '') {

    $nama_gambar = time() . '_' . $_FILES['gambar_pendukung']['name'];

    $tmp = $_FILES['gambar_pendukung']['tmp_name'];

    move_uploaded_file(
        $tmp,
        "../uploads/" . $nama_gambar
    );
}

mysqli_query($conn, "
    INSERT INTO pengumuman (
        judul_pengumuman,
        isi_pengumuman,
        tanggal_publikasi,
        status_publikasi,
        kategori_pengumuman,
        gambar_pendukung,
        target_tipe,
        id_cabang
    )
    VALUES (
        '$judul',
        '$isi',
        '$tanggal',
        '$status',
        '$kategori',
        '$nama_gambar',
        'cabang',
        '$id_cabang'
    )
");

header("location: pengumuman_gembala.php?pesan=sukses");
exit();