<?php
include '../koneksi.php';
/** @var mysqli $conn */
$q = mysqli_real_escape_string($conn, $_GET['q']);
$query = mysqli_query($conn, "SELECT id_jemaat, nama_lengkap FROM jemaat WHERE nama_lengkap LIKE '%$q%' LIMIT 5");
while($row = mysqli_fetch_assoc($query)) {
    echo "<div class='search-item' onclick='pilihGembala(".$row['id_jemaat'].", \"".htmlspecialchars($row['nama_lengkap'])."\")' style='padding:5px; cursor:pointer;'>".$row['nama_lengkap']."</div>";
}
?>