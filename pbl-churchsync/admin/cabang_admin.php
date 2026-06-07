<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = $_POST['aksi'];

    if ($aksi == 'simpan') {
        $id_cabang = mysqli_real_escape_string($conn, $_POST['id_cabang']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_cabang']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat_cabang']);

        if (empty($id_cabang)) {
            $query = "INSERT INTO cabang_gereja (nama_cabang, alamat_cabang) VALUES ('$nama', '$alamat')";
        } else {
            $query = "UPDATE cabang_gereja SET nama_cabang='$nama', alamat_cabang='$alamat' WHERE id_cabang='$id_cabang'";
        }
        mysqli_query($conn, $query);
        
        header("Location: cabang_admin.php");
        exit;

    } elseif ($aksi == 'hapus') {
        $id_cabang = mysqli_real_escape_string($conn, $_POST['id_cabang']);
        $query = "DELETE FROM cabang_gereja WHERE id_cabang='$id_cabang'";
        mysqli_query($conn, $query);
        
        header("Location: cabang_admin.php");
        exit;
    }
}

$query_tampil = "SELECT * FROM cabang_gereja ORDER BY id_cabang DESC";
$result_cabang = mysqli_query($conn, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabang Gereja - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .header-toolbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .page-title h2 { color: var(--primary-blue); font-size: 28px; }
        .page-title p { color: var(--text-gray); font-size: 14px; }
        .btn-add { background-color: var(--primary-yellow); color: var(--primary-blue); border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .branch-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .branch-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); position: relative; border-top: 4px solid var(--primary-yellow); }
        .branch-card h3 { color: var(--primary-blue); font-size: 20px; margin-bottom: 15px; }
        .branch-detail { margin-bottom: 8px; font-size: 14px; color: var(--text-gray); display: flex; gap: 10px; }
        .action-btns { margin-top: 20px; display: flex; gap: 10px; }
        .action-btns button { border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; flex: 1; }
        .btn-edit { background-color: #eef2f6; color: var(--primary-blue); }
        .btn-delete { background-color: #fef2f2; color: #dc3545; }
        
        /* Modal Style */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 1000; }
        .modal-content { background: white; width: 500px; border-radius: 12px; padding: 30px; }
        .modal-header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; color: var(--primary-blue); }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        .form-group label { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 5px; }
        .form-group input { padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn-cancel { background: black; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link active">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">Admin ChurchSync</div>▼
                    <div class="dropdown-content">
                        <a href="profil-admin.html">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Manajemen Cabang Gereja</h2>
                    <p>Kelola data dan alamat tiap cabang gereja</p>
                </div>
                <button class="btn-add" onclick="tambahCabang()">+ Tambah Cabang</button>
            </div>

            <div class="branch-grid">
                <?php 
                // Melakukan looping data dari database
                if(mysqli_num_rows($result_cabang) > 0) {
                    while($row = mysqli_fetch_assoc($result_cabang)) { 
                ?>
                <div class="branch-card">
                    <h3><?= htmlspecialchars($row['nama_cabang']) ?></h3>
                    <div class="branch-detail">📍 <?= htmlspecialchars($row['alamat_cabang']) ?></div>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editCabang('<?= $row['id_cabang'] ?>', '<?= addslashes($row['nama_cabang']) ?>', '<?= addslashes($row['alamat_cabang']) ?>')">Edit</button>
                        
                        <form action="" method="POST" style="flex: 1;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini? Perhatian: Menghapus cabang akan menghapus jadwal ibadah yang terkait dengan cabang ini (Cascade).');">
                            <input type="hidden" name="id_cabang" value="<?= $row['id_cabang'] ?>">
                            <button type="submit" name="aksi" value="hapus" class="btn-delete" style="width: 100%;">Hapus</button>
                        </form>
                    </div>
                </div>
                <?php 
                    } 
                } else {
                    echo "<p style='grid-column: span 2; text-align: center; color: var(--text-gray);'>Belum ada data cabang gereja.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <div id="modalCabang" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Form Data Cabang</h3>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id_cabang" id="input_id_cabang">
                
                <div class="form-group">
                    <label>Nama Cabang</label>
                    <input type="text" name="nama_cabang" id="input_nama" placeholder="Contoh: GBI Maranatha Cimahi" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat_cabang" id="input_alamat" placeholder="Masukkan alamat..." required>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalCabang').style.display='none'">Batal</button>
                    <button type="submit" name="aksi" value="simpan" class="btn-add">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal dalam mode Tambah (Mengosongkan form)
        function tambahCabang() {
            document.getElementById('modalTitle').innerText = 'Tambah Data Cabang';
            document.getElementById('input_id_cabang').value = '';
            document.getElementById('input_nama').value = '';
            document.getElementById('input_alamat').value = '';
            document.getElementById('modalCabang').style.display = 'flex';
        }

        // Fungsi untuk membuka modal dalam mode Edit (Mengisi form dengan data dari database)
        function editCabang(id, nama, alamat) {
            document.getElementById('modalTitle').innerText = 'Edit Data Cabang';
            document.getElementById('input_id_cabang').value = id;
            document.getElementById('input_nama').value = nama;
            document.getElementById('input_alamat').value = alamat;
            document.getElementById('modalCabang').style.display = 'flex';
        }
    </script>
</body>

</html>