<?php
session_start();
include '../koneksi.php';
/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location: ../login.php?pesan=belum_login");
    exit();
}

if (isset($_GET['id_jadwal'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_GET['id_jadwal']);

    mysqli_query($conn, "DELETE FROM penugasan_pelayan WHERE id_jadwal = '$id_jadwal'");

    $query_hapus = "DELETE FROM pendataan WHERE id_jadwal = '$id_jadwal'";

    if (mysqli_query($conn, $query_hapus)) {
        header("Location: jadwal_admin_up.php?pesan=sukses_hapus_laporan");
        exit();
    } else {
        echo "Gagal menghapus laporan: " . mysqli_error($conn);
    }
} else {
    header("Location: jadwal_admin_up.php");
    exit();
}
?>