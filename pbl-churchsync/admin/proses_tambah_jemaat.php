<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

// 1. Tangkap semua data identitas dari form 
// (Pastiin atribut name="..." di HTML lu sesuai sama variabel $_POST di bawah ini ya)
$nama_lengkap  = $_POST['nama_lengkap'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$no_telp       = $_POST['no_telp'];
$alamat        = $_POST['alamat'];
$id_cabang     = $_POST['id_cabang'];

// Otomatis kita set 'jemaat' sesuai tipe ENUM di database lu
$role          = 'jemaat'; 

// 2. Tangkap urusan form akun (Centangan)
$buat_akun = isset($_POST['buat_akun']) ? $_POST['buat_akun'] : '';

if ($buat_akun == 'ya') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 🚨 SATPAM EMAIL DOBEL: Cuma jalan kalau centang aktif dan email diisi 🚨
    if ($email != '') {
        $cek_email = mysqli_query($conn, "SELECT id_jemaat FROM jemaat WHERE email = '$email'");
        
        if (mysqli_num_rows($cek_email) > 0) {
            // Kalau email udah ada, tendang balik adminnya!
            header("location: data_jemaat_admin.php?pesan=email_terdaftar");
            exit(); 
        }
    }
} else {
    // Kalau centang dilepas, email & password kita isi kosong biar MySQL ga error "Column cannot be null"
    $email = "";
    $password = "";
}

// ✅ 3. EKSEKUSI INSERT FULL KOLOM SESUAI DATABASE LU!
$query_insert = "INSERT INTO jemaat 
                 (nama_lengkap, tanggal_lahir, no_telp, alamat, email, password, role, id_cabang) 
                 VALUES 
                 ('$nama_lengkap', '$tanggal_lahir', '$no_telp', '$alamat', '$email', '$password', '$role', '$id_cabang')";

if (mysqli_query($conn, $query_insert)) {
    header("location: data_jemaat_admin.php?pesan=sukses_tambah");
    exit();
} else {
    // Pesan ini buat nangkep kalau misal ada typo nama kolom atau error dari database
    echo "Gagal: " . mysqli_error($conn);
}
?>