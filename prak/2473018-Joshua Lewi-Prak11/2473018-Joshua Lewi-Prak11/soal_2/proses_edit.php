<?php
/** @var mysqli $conn */
include 'koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$kelas = $_POST['kelas'];

$sql = "UPDATE siswa SET nama='$nama', kelas='$kelas' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>