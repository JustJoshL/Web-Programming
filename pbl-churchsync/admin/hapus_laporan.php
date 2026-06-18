<?php
session_start();
include '../koneksi.php';
/** @var mysqli $conn */

// Cek keamanan (biar cuma admin yang bisa hapus)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location: ../login.php?pesan=belum_login");
    exit();
}



// Tangkap ID Jadwal dari URL
if (isset($_GET['id_jadwal'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_GET['id_jadwal']);

    // Eksekusi kueri hapus ke tabel pendataan berdasarkan id_jadwal
    $query_hapus = "DELETE FROM pendataan WHERE id_jadwal = '$id_jadwal'";

    if (mysqli_query($conn, $query_hapus)) {
        // Kalau berhasil, tendang balik ke halaman Jadwal Ibadah bawa pesan sukses
        header("Location: jadwal_admin_up.php?pesan=sukses_hapus_laporan");
        exit();
    } else {
        echo "Gagal menghapus laporan: " . mysqli_error($conn);
    }
} else {
    // Kalau gada ID di URL, balikin aja
    header("Location: jadwal_admin_up.php");
    exit();
}
?>