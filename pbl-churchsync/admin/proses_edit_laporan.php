<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

// Tangkap data Laporan dari Form Edit
$id_jadwal = $_POST['id_jadwal'];
$kehadiran = $_POST['kehadiran'];
$persembahan = $_POST['persembahan'];
$perpuluhan = $_POST['perpuluhan']; 
$catatan = $_POST['catatan']; 

// === 1. UPDATE DATA DI TABEL PENDATAAN ===
$query_update = "UPDATE pendataan SET 
                    jumlah_kehadiran = '$kehadiran', 
                    total_persembahan = '$persembahan', 
                    total_perpuluhan = '$perpuluhan', 
                    catatan = '$catatan' 
                 WHERE id_jadwal = '$id_jadwal'";
                  
if(mysqli_query($conn, $query_update)) {
    
    // === 2. UPDATE DATA MULTIPLE PELAYAN ===
    // Trik paling ampuh: Hapus pelayan lama, lalu insert ulang yang baru
    mysqli_query($conn, "DELETE FROM penugasan_pelayan WHERE id_jadwal = '$id_jadwal'");

    if (isset($_POST['nama_pelayan']) && isset($_POST['peran_pelayan'])) {
        $nama_pelayan = $_POST['nama_pelayan'];
        $peran_pelayan = $_POST['peran_pelayan'];

        if (!empty($nama_pelayan[0])) {
            for ($i = 0; $i < count($nama_pelayan); $i++) {
                $nama = $nama_pelayan[$i];
                $peran = $peran_pelayan[$i];

                if ($nama != "" && $peran != "") {
                    $q_pelayan = "INSERT INTO penugasan_pelayan (id_jadwal, nama_pelayan, peran_pelayanan) 
                                  VALUES ('$id_jadwal', '$nama', '$peran')";
                    mysqli_query($conn, $q_pelayan);
                }
            }
        }
    }

    // Balik ke halaman jadwal
    header("location: jadwal_admin_up.php?pesan=sukses_edit_laporan");
    
} else {
    echo "Gagal mengedit laporan bung: " . mysqli_error($conn);
}
?>