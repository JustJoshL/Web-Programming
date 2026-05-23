<?php
/** @var mysqli $conn */
include 'koneksi.php';

$nama = $_POST['nama'];
$kelas = $_POST['kelas'];

$sql = "INSERT INTO siswa (nama, kelas) VALUES ('$nama', '$kelas')";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>