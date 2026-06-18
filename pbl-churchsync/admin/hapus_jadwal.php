<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

if (isset($_GET['id'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_GET['id']);
    
    mysqli_query($conn, "DELETE FROM penugasan_pelayan WHERE id_jadwal = '$id_jadwal'");

    mysqli_query($conn, "DELETE FROM pendataan WHERE id_jadwal = '$id_jadwal'");

    mysqli_query($conn, "DELETE FROM jadwal_ibadah WHERE id_jadwal = '$id_jadwal'");

    header("Location: jadwal_admin_up.php");
    exit();
} else {
    header("Location: jadwal_admin_up.php");
    exit();
}
?>