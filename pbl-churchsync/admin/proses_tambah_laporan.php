<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

// Tangkap data Laporan dari Form
$id_jadwal = $_POST['id_jadwal'];
$kehadiran = $_POST['kehadiran'];
$persembahan = $_POST['persembahan'];
$perpuluhan = $_POST['perpuluhan']; 
$catatan = $_POST['catatan']; 
$waktu_pelaporan = date('Y-m-d H:i:s');
date_default_timezone_set('Asia/Jakarta');

// === 1. SIMPAN DATA KE TABEL PENDATAAN ===
// Tambahin 'catatan' ke dalam query-nya
$query_laporan = "INSERT INTO pendataan (id_jadwal, jumlah_kehadiran, total_persembahan, total_perpuluhan, catatan, waktu_pelaporan) 
                  VALUES ('$id_jadwal', '$kehadiran', '$persembahan', '$perpuluhan', '$catatan', '$waktu_pelaporan')";


if(mysqli_query($conn, $query_laporan)) {
    
    // === 2. SIMPAN DATA MULTIPLE PELAYAN ===
    if (isset($_POST['nama_pelayan']) && isset($_POST['peran_pelayan'])) {
        $nama_pelayan = $_POST['nama_pelayan'];
        $peran_pelayan = $_POST['peran_pelayan'];

        if (!empty($nama_pelayan[0])) {
            for ($i = 0; $i < count($nama_pelayan); $i++) {
                $nama = $nama_pelayan[$i];
                $peran = $peran_pelayan[$i];

                if ($nama != "" && $peran != "") {
                    // Sesuai dengan kolom penugasan_pelayan yang baru di-update: id_jadwal, nama_pelayan, peran_pelayanan
                    $q_pelayan = "INSERT INTO penugasan_pelayan (id_jadwal, nama_pelayan, peran_pelayanan) 
                                  VALUES ('$id_jadwal', '$nama', '$peran')";
                    mysqli_query($conn, $q_pelayan);
                }
            }
        }
    }

    // Kalau semua beres, balik ke halaman jadwal
    header("location: jadwal_admin_up.php?pesan=sukses_laporan");
    
} else {
    echo "Gagal bikin laporan bung: " . mysqli_error($conn);
}
?>