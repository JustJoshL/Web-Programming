<?php
session_start();

include 'koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

/** @var mysqli $conn */
$query = mysqli_query($conn, "SELECT * FROM jemaat WHERE email='$email' AND password='$password'");

$cek_data = mysqli_num_rows($query);

if ($cek_data > 0) {
	$data = mysqli_fetch_assoc($query);

	$_SESSION['id_jemaat'] = $data['id_jemaat'];
	$_SESSION['nama_lengkap'] = $data['nama_lengkap'];
	$_SESSION['role'] = $data['role'];
	$_SESSION['id_cabang'] = $data['id_cabang']; // Penting buat nampilin data sesuai cabang nanti

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
