<?php
// 1. Nyalain session biar PHP ingat siapa yang lagi login
session_start();

// 2. Panggil jembatan koneksi yang tadi lu bikin
include 'koneksi.php';

// 3. Tangkap data yang dikirim dari form login
$email = $_POST['email'];
$password = $_POST['password'];

/** @var mysqli $conn */
// 4. Tanya ke satpam MySQL: "Ada gak orang yang email dan passwordnya cocok?"
$query = mysqli_query($conn, "SELECT * FROM jemaat WHERE email='$email' AND password='$password'");

// Ngitung jumlah data yang ketemu
$cek_data = mysqli_num_rows($query);

// 5. Kalau datanya ketemu (ada 1 baris)
if ($cek_data > 0) {
	$data = mysqli_fetch_assoc($query);

	// Simpan identitasnya ke dalam "KTP Sementara" (Session)
	$_SESSION['id_jemaat'] = $data['id_jemaat'];
	$_SESSION['nama_lengkap'] = $data['nama_lengkap'];
	$_SESSION['role'] = $data['role'];
	$_SESSION['id_cabang'] = $data['id_cabang']; // Penting buat nampilin data sesuai cabang nanti

	// 6. Percabangan Cerdas: Lempar ke dashboard sesuai Role!
	if ($data['role'] == 'admin') {
		header("location:admin/dashboard_admin.php");
	} else if ($data['role'] == 'gembala_cabang') {
		header("location:gembala/dashboard_gembala.php");
	} else if ($data['role'] == 'jemaat') {
		header("location:jemaat/dashboard_jemaat.php");
	}
} else {

	header("location:login.php?error=1");
	exit();
}
