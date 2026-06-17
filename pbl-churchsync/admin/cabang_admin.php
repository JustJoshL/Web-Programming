<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi == 'simpan') {

        $id_cabang = mysqli_real_escape_string($conn, $_POST['id_cabang']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_cabang']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat_cabang']);

        if (empty($id_cabang)) {

            $query = "INSERT INTO cabang_gereja
            (nama_cabang, alamat_cabang)
            VALUES
            ('$nama', '$alamat')";

            mysqli_query($conn, $query);

            $id_cabang = mysqli_insert_id($conn);
        } else {

            $query = "UPDATE cabang_gereja
            SET nama_cabang='$nama',
                alamat_cabang='$alamat'
            WHERE id_cabang='$id_cabang'";

            mysqli_query($conn, $query);
        }

        if (!empty($_POST['id_gembala'])) {

            $id_gembala = mysqli_real_escape_string(
                $conn,
                $_POST['id_gembala']
            );

            mysqli_query($conn, "
            UPDATE jemaat
            SET id_cabang = NULL
            WHERE role='gembala_cabang'
            AND id_cabang='$id_cabang'
        ");

            mysqli_query($conn, "
            UPDATE jemaat
            SET id_cabang='$id_cabang'
            WHERE id_jemaat='$id_gembala'
        ");
        }

        header("Location: cabang_admin.php");
        exit;
    }
}

$query_tampil = "
SELECT
    c.*,
    j.id_jemaat AS id_gembala,
    j.nama_lengkap AS nama_gembala
FROM cabang_gereja c
LEFT JOIN jemaat j
    ON j.id_cabang = c.id_cabang
    AND j.role = 'gembala_cabang'
ORDER BY c.id_cabang DESC
";
$result_cabang = mysqli_query($conn, $query_tampil);

$query_gembala = mysqli_query($conn, "
    SELECT id_jemaat, nama_lengkap 
    FROM jemaat 
    WHERE role = 'gembala_cabang' 
    ORDER BY nama_lengkap ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabang Gereja - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .header-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .page-title h2 {
            color: var(--primary-blue);
            font-size: 28px;
        }

        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }

        .btn-add {
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .branch-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .branch-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            border-top: 4px solid var(--primary-yellow);
        }

        .branch-card h3 {
            color: var(--primary-blue);
            font-size: 20px;
            margin-bottom: 15px;
        }

        .branch-detail {
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-gray);
            display: flex;
            gap: 10px;
        }

        .action-btns {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .action-btns button {
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            flex: 1;
        }

        .btn-edit {
            background-color: #eef2f6;
            color: var(--primary-blue);
        }

        .btn-delete {
            background-color: #fef2f2;
            color: #dc3545;
        }

        /* Modal Style */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 500px;
            border-radius: 12px;
            padding: 30px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            color: var(--primary-blue);
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            background: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .user-profile-dropdown {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            z-index: 100;
            margin-top: 15px;
            border: 1px solid #e2e8f0;
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-content a {
            color: #334155;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-content a:hover {
            background-color: #f8fafc;
            color: var(--primary-blue);
        }

        .logout-item {
            color: #dc3545 !important;
            font-weight: bold;
        }

        .logout-item:hover {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
        }

        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
        }
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
                <?php include '../widget_notif.php'; ?>
                <div class="user-profile-dropdown" onclick="toggleDropdown()">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?> (Admin) ▼</div>
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item" onclick="return confirm('Yakin mau keluar?');">Logout</a>
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
                if (mysqli_num_rows($result_cabang) > 0) {
                    while ($row = mysqli_fetch_assoc($result_cabang)) {
                ?>
                        <div class="branch-card">
                            <h3><?= htmlspecialchars($row['nama_cabang']) ?></h3>
                            <div class="branch-detail">📍 <?= htmlspecialchars($row['alamat_cabang']) ?></div>
                            <div class="branch-detail" style="margin-top: 5px; color: #166534; font-weight: 600;">
                                👤 Gembala: <?= $row['nama_gembala'] ? htmlspecialchars($row['nama_gembala']) : '<em>Belum ditunjuk</em>' ?>
                            </div>
                            <div class="action-btns">
                                <button class="btn-edit"
                                    onclick="editCabang(
                                        '<?= $row['id_cabang']; ?>',
                                        '<?= addslashes($row['nama_cabang']); ?>',
                                        '<?= addslashes($row['alamat_cabang']); ?>',
                                        '<?= $row['id_gembala']; ?>'
                                    )">
                                    Edit
                                </button>

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
                <input type="hidden" name="aksi" value="simpan">

                <div class="form-group">
                    <label>Nama Cabang</label>
                    <input type="text" name="nama_cabang" id="input_nama" placeholder="Contoh: GBI Maranatha Cimahi" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat_cabang" id="input_alamat" placeholder="Masukkan alamat..." required>
                </div>
                <div class="form-group">
                    <label>Gembala Cabang</label>
                    <select name="id_gembala" id="input_id_gembala">
                        <option value="">-- Pilih Gembala Cabang --</option>
                        <?php
                        mysqli_data_seek($query_gembala, 0);
                        while ($gembala = mysqli_fetch_assoc($query_gembala)) :
                        ?>
                            <option value="<?= $gembala['id_jemaat']; ?>">
                                <?= $gembala['nama_lengkap']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalCabang').style.display='none'">Batal</button>
                    <button type="submit" class="btn-add">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tambahCabang() {
            document.getElementById('modalTitle').innerText = 'Tambah Data Cabang';
            document.getElementById('input_id_cabang').value = '';
            document.getElementById('input_nama').value = '';
            document.getElementById('input_alamat').value = '';
            document.getElementById('input_id_gembala').value = ''; // Kosongin dropdown
            document.getElementById('modalCabang').style.display = 'flex';
        }

        function editCabang(id, nama, alamat, id_gembala) {
            document.getElementById('modalTitle').innerText = 'Edit Data Cabang';
            document.getElementById('input_id_cabang').value = id;
            document.getElementById('input_nama').value = nama;
            document.getElementById('input_alamat').value = alamat;

            document.getElementById('input_id_gembala').value = id_gembala;

            document.getElementById('modalCabang').style.display = 'flex';
        }

        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-profile-dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>