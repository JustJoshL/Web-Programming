<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../index.php");
    exit();
}

include '../koneksi.php';

/** @var mysqli $conn */

$judul    = $_POST['judul_pengumuman'];
$kategori = $_POST['kategori_pengumuman'];
$tanggal  = $_POST['tanggal_publikasi'];
$isi      = $_POST['isi_pengumuman'];
$status = $_POST['status_publikasi'];

$hari_ini = date('Y-m-d');
if ($tanggal <= $hari_ini) {
    $status = 'Published';
} else {
    $status = 'Draft'; 
}

$nama_gambar = "";
$id_cabang = $_SESSION['id_cabang'];
$hari_ini = date('Y-m-d');

if ($_FILES['gambar_pendukung']['name'] != '') {
    $nama_gambar = time() . '_' . $_FILES['gambar_pendukung']['name'];
    $tmp = $_FILES['gambar_pendukung']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/" . $nama_gambar);
}

$eksekusi = mysqli_query($conn, "
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

if ($eksekusi) {
    if ($tanggal <= $hari_ini) {
        header("Location: pengumuman_gembala.php?pesan=sukses_publish");
    } else {
        header("Location: pengumuman_gembala.php?pesan=sukses_jadwal&tgl=" . $tanggal);
    }
    exit();
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>