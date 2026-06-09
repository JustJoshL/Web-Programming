<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

if (isset($_GET['id_jadwal'])) {
    $id_jadwal = $_GET['id_jadwal'];

    // Hapus pelayan yang nempel di laporan ini dulu
    mysqli_query($conn, "DELETE FROM penugasan_pelayan WHERE id_jadwal = '$id_jadwal'");
    
    // Baru hapus laporannya dari tabel pendataan
    if (mysqli_query($conn, "DELETE FROM pendataan WHERE id_jadwal = '$id_jadwal'")) {
        header("location: jadwal_admin_up.php?pesan=sukses_hapus_laporan");
    } else {
        echo "Gagal hapus laporan: " . mysqli_error($conn);
    }
}
?>