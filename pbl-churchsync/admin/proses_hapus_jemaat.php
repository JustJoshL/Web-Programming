<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */


// 1. Tangkap ID Jemaat yang dikirim dari URL
if (isset($_GET['id'])) {
    $id_jemaat = $_GET['id'];

    // 2. Eksekusi kueri DELETE
    $query_hapus = "DELETE FROM jemaat WHERE id_jemaat = '$id_jemaat'";

    if (mysqli_query($conn, $query_hapus)) {
        // Kalau berhasil, balikin ke halaman data jemaat bawa pesan sukses_hapus
        header("location: data_jemaat_admin.php?pesan=sukses_hapus");
        exit();
    } else {
        // Kalau gagal (misal karena datanya nyangkut di tabel lain/foreign key)
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    // Kalau ada orang iseng buka URL ini tanpa ID, lempar balik aja
    header("location: data_jemaat_admin.php");
    exit();
}
?>