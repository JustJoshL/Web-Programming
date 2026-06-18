<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */


// 1. Tangkap ID Jemaat yang dikirim dari URL
if (isset($_GET['id'])) {
    $id_jemaat = $_GET['id'];

    $query_hapus = "DELETE FROM jemaat WHERE id_jemaat = '$id_jemaat'";

    if (mysqli_query($conn, $query_hapus)) {
        header("location: data_jemaat_admin.php?pesan=sukses_hapus");
        exit();
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("location: data_jemaat_admin.php");
    exit();
}
?>