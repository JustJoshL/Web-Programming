<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../index.php?pesan=belum_login");
    exit();
}

/** @var mysqli $conn */
include '../koneksi.php';

$id       = $_POST['id_pengumuman'];
$judul    = $_POST['judul_pengumuman'];
$kategori = $_POST['kategori_pengumuman'];
$tanggal  = $_POST['tanggal_publikasi'];
$isi      = $_POST['isi_pengumuman'];
$status   = $_POST['status_publikasi'];

if ($status == 'Published' && $tanggal > date('Y-m-d')) {
    $status = 'Draft';
}
$gbr_lama = $_POST['gambar_lama'];
$gbr_baru = $_FILES['gambar_baru']['name'];

$hari_ini = date('Y-m-d');

if ($gbr_baru != "") {
    $lokasi_sementara = $_FILES['gambar_baru']['tmp_name'];
    $folder_tujuan = "../uploads/" . $gbr_baru;

    move_uploaded_file($lokasi_sementara, $folder_tujuan);

    if ($gbr_lama != "" && file_exists("../uploads/" . $gbr_lama)) {
        unlink("../uploads/" . $gbr_lama);
    }

    $query = "UPDATE pengumuman SET 
                judul_pengumuman = '$judul',
                isi_pengumuman = '$isi',
                tanggal_publikasi = '$tanggal',
                kategori_pengumuman = '$kategori',
                status_publikasi = '$status',
                gambar_pendukung = '$gbr_baru'
              WHERE id_pengumuman = '$id'";
} else {
    $query = "UPDATE pengumuman SET 
                judul_pengumuman = '$judul',
                isi_pengumuman = '$isi',
                tanggal_publikasi = '$tanggal',
                kategori_pengumuman = '$kategori',
                status_publikasi = '$status'
              WHERE id_pengumuman = '$id'";
}

if (mysqli_query($conn, $query)) {
    if ($tanggal <= $hari_ini) {
        header("Location: pengumuman_admin.php?pesan=sukses_publish");
    } else {
        header("Location: pengumuman_admin.php?pesan=sukses_jadwal&tgl=" . $tanggal);
    }
    exit(); 
} else {
    echo "Gagal total bung: " . mysqli_error($conn);
}
?>