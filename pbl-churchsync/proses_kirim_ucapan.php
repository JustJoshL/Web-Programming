<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** @var mysqli $conn */
include 'koneksi.php';

if (isset($_GET['id_penerima'])) {
    $id_penerima = mysqli_real_escape_string($conn, $_GET['id_penerima']);
    $nama_pengirim = $_SESSION['nama_lengkap'];
    $tahun_sekarang = date('Y');
    
    $id_pengirim = 0; 

    $q_cari_pengirim = mysqli_query($conn, "SELECT id_jemaat FROM jemaat WHERE nama_lengkap = '$nama_pengirim'");
    
    if ($q_cari_pengirim && mysqli_num_rows($q_cari_pengirim) > 0) {
        $data_pengirim = mysqli_fetch_assoc($q_cari_pengirim);
        $id_pengirim = $data_pengirim['id_jemaat'];
    } else {
        ob_clean();
        echo "Error: Akun kamu ($nama_pengirim) belum terdaftar di tabel data jemaat!";
        exit();
    }

    $q_cek = mysqli_query($conn, "SELECT * FROM ucapan_ultah WHERE id_pengirim = '$id_pengirim' AND id_penerima = '$id_penerima' AND tahun = '$tahun_sekarang'");
    
    ob_clean();

    if ($q_cek && mysqli_num_rows($q_cek) > 0) {
        echo "udah_pernah";
    } else {
        $q_insert = mysqli_query($conn, "INSERT INTO ucapan_ultah (id_pengirim, id_penerima, tahun) VALUES ('$id_pengirim', '$id_penerima', '$tahun_sekarang')");
        
        if ($q_insert) {
            echo "sukses";
        } else {
            echo "Error DB: " . mysqli_error($conn);
        }
    }
    exit();
}
?>