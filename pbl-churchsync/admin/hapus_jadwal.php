<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

// Cuma Admin yang boleh ngeksekusi file ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

if (isset($_GET['id'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_GET['id']);

    // 🚨 URUTAN EKSEKUSI MATI (CASCADE MANUAL) 🚨
    
    // 1. Bantai dulu data anak buahnya: Pelayan Ibadah
    mysqli_query($conn, "DELETE FROM penugasan_pelayan WHERE id_jadwal = '$id_jadwal'");

    // 2. Bantai laporan utamanya: Pendataan
    mysqli_query($conn, "DELETE FROM pendataan WHERE id_jadwal = '$id_jadwal'");

    // 3. Terakhir, baru bantai bosnya: Jadwal Ibadah
    mysqli_query($conn, "DELETE FROM jadwal_ibadah WHERE id_jadwal = '$id_jadwal'");

    // Tendang balik ke halaman jadwal
    header("Location: jadwal_admin_up.php");
    exit();
} else {
    // Kalau nggak ada ID yang dikirim, balikin aja
    header("Location: jadwal_admin_up.php");
    exit();
}
?>