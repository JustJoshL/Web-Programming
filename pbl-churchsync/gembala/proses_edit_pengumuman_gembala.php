<?php
session_start();

/** @var mysqli $conn */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../index.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_cabang = $_SESSION['id_cabang'];

$id_pengumuman = mysqli_real_escape_string($conn, $_POST['id_pengumuman']);
$judul = mysqli_real_escape_string($conn, $_POST['judul_pengumuman']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori_pengumuman']);
$tanggal = mysqli_real_escape_string($conn, $_POST['tanggal_publikasi']);
$status = mysqli_real_escape_string($conn, $_POST['status_publikasi']);
$isi = mysqli_real_escape_string($conn, $_POST['isi_pengumuman']);

$hari_ini = date('Y-m-d');
if ($tanggal <= $hari_ini) {
    $status = 'Published';
} else {
    $status = 'Draft'; 
}
$hari_ini = date('Y-m-d');

$cek_milik = mysqli_query($conn, "SELECT gambar_pendukung FROM pengumuman WHERE id_pengumuman='$id_pengumuman' AND id_cabang='$id_cabang'");

if (mysqli_num_rows($cek_milik) == 0) {
    die("Akses Ditolak: Anda tidak berhak mengedit pengumuman cabang lain!");
}

$data_lama = mysqli_fetch_assoc($cek_milik);

if (isset($_FILES['gambar_pendukung']) && $_FILES['gambar_pendukung']['error'] == 0) {
    $nama_file = $_FILES['gambar_pendukung']['name'];
    $tmp_file = $_FILES['gambar_pendukung']['tmp_name'];

    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = time() . '_' . uniqid() . '.' . $ext;
    $path = '../uploads/' . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        if (!empty($data_lama['gambar_pendukung']) && file_exists('../uploads/' . $data_lama['gambar_pendukung'])) {
            unlink('../uploads/' . $data_lama['gambar_pendukung']);
        }

        mysqli_query($conn, "UPDATE pengumuman SET 
            judul_pengumuman='$judul', 
            kategori_pengumuman='$kategori', 
            tanggal_publikasi='$tanggal', 
            status_publikasi='$status', 
            isi_pengumuman='$isi', 
            gambar_pendukung='$nama_baru' 
            WHERE id_pengumuman='$id_pengumuman' AND id_cabang='$id_cabang'");
    }
} else {
    mysqli_query($conn, "UPDATE pengumuman SET 
        judul_pengumuman='$judul', 
        kategori_pengumuman='$kategori', 
        tanggal_publikasi='$tanggal', 
        status_publikasi='$status', 
        isi_pengumuman='$isi' 
        WHERE id_pengumuman='$id_pengumuman' AND id_cabang='$id_cabang'");
}

if ($tanggal <= $hari_ini) {
    header("Location: pengumuman_gembala.php?pesan=sukses_publish");
} else {
    header("Location: pengumuman_gembala.php?pesan=sukses_jadwal&tgl=" . $tanggal);
}
exit();
?>