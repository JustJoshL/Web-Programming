<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

$nama_lengkap  = $_POST['nama_lengkap'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$no_telp       = $_POST['no_telp'];
$alamat        = $_POST['alamat'];
$id_cabang     = $_POST['id_cabang'];

$role          = 'jemaat'; 

$buat_akun = isset($_POST['buat_akun']) ? $_POST['buat_akun'] : '';

if ($buat_akun == 'ya') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email != '') {
        $cek_email = mysqli_query($conn, "SELECT id_jemaat FROM jemaat WHERE email = '$email'");
        
        if (mysqli_num_rows($cek_email) > 0) {
            header("location: data_jemaat_admin.php?pesan=email_terdaftar");
            exit(); 
        }
    }
} else {
    // Kalau centang dilepas, email & password diisi kosong biar MySQL ga error 
    $email = "";
    $password = "";
}

$query_insert = "INSERT INTO jemaat 
                 (nama_lengkap, tanggal_lahir, no_telp, alamat, email, password, role, id_cabang) 
                 VALUES 
                 ('$nama_lengkap', '$tanggal_lahir', '$no_telp', '$alamat', '$email', '$password', '$role', '$id_cabang')";

if (mysqli_query($conn, $query_insert)) {
    header("location: data_jemaat_admin.php?pesan=sukses_tambah");
    exit();
} else {
    // Nangkep kalau misal ada typo nama kolom atau error dari database
    echo "Gagal: " . mysqli_error($conn);
}
?>