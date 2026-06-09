<?php
session_start();
/** @var mysqli $conn */

include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

$id_cabang_filter = $_GET['id_cabang'] ?? '';
$search = $_GET['search'] ?? '';

$where = [];
if ($id_cabang_filter != '') {
    $id = mysqli_real_escape_string($conn, $id_cabang_filter);
    $where[] = "j.id_cabang = '$id'";
}
if ($search != '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where[] = "(j.nama_lengkap LIKE '%$s%' OR j.email LIKE '%$s%')";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$query_jemaat = mysqli_query($conn, "
    SELECT j.*, c.nama_cabang 
    FROM jemaat j 
    LEFT JOIN cabang_gereja c ON j.id_cabang = c.id_cabang 
    $where_sql 
    ORDER BY j.id_jemaat DESC
");

$query_cabang = mysqli_query($conn, "SELECT * FROM cabang_gereja ORDER BY nama_cabang");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jemaat - ChurchSync</title>
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

        .toolbar-actions {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .search-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }

        .filter-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: white;
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

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .list-item {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .item-text h4 {
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .item-text p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .btn-view {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--primary-blue);
        }

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
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
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

        .btn-save {
            background: var(--primary-yellow);
            color: var(--primary-blue);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_gembala.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_gembala.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_gembala.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_gembala.php" class="nav-link active">Data Jemaat</a>
            <a href="laporan_ibadah_gembala.php" class="nav-link">Laporan Ibadah</a>
            <a href="profil_gembala.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">
                    🔔<span class="noti-badge"></span>
                </div>

                <div class="user-profile-dropdown">
                    <div class="nav-avatar">👨🏽‍💼</div>
                    <div class="nav-user-name"><?= $gembala['nama_lengkap']; ?></div>
                    ▼
                    <div class="dropdown-content">
                        <a href="profil_gembala.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

    <div class="content-wrapper">
        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Daftar Jemaat</h2>
                    <div class="toolbar-actions">
                        <form method="GET" style="display: flex; gap: 15px;">
                        <input type="text" name="search" class="search-box" 
                            placeholder="Cari nama atau email..." 
                            value="<?= htmlspecialchars($search); ?>">

                        <select name="id_cabang" class="filter-box" onchange="this.form.submit()">
                            <option value="">Semua Cabang</option>
                            <?php while ($cabang = mysqli_fetch_assoc($query_cabang)) { ?>
                                <option value="<?= $cabang['id_cabang']; ?>" 
                                    <?= ($id_cabang_filter == $cabang['id_cabang']) ? 'selected' : ''; ?>>
                                    <?= $cabang['nama_cabang']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        
                        <button type="submit" class="btn-add" style="padding: 10px 15px;">Cari</button>
                    </form>
                    </div>             
                </div>
            </div>

            <div class="list-container">
                <?php while ($row = mysqli_fetch_assoc($query_jemaat)) { ?>
                    <div class="list-item">
                        <div class="item-info">
                            <div class="avatar">👤</div>
                            <div class="item-text">
                                <h4><?= htmlspecialchars($row['nama_lengkap']); ?></h4>
                                <p>
                                    <?= htmlspecialchars($row['alamat']); ?> • <?= htmlspecialchars($row['email']); ?>
                                    <br>
                                    <span style="font-size: 10px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                        <?= ucwords(str_replace('_', ' ', strtolower($row['role']))); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <button class="btn-view"
                            onclick="viewJemaat(
                                '<?= addslashes($row['nama_lengkap']); ?>',
                                '<?= htmlspecialchars($row['no_telp']); ?>',
                                '<?= htmlspecialchars($row['tanggal_lahir']); ?>',
                                '<?= addslashes($row['alamat']); ?>',
                                '<?= htmlspecialchars($row['email']); ?>',
                                '<?= addslashes($row['nama_cabang']); ?>'
                            )">👁️</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="modalViewData" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Jemaat</h3>
            </div>
            <div class="detail-row"><label>Nama Lengkap: </label><span id="view_nama"></span></div>
            <div class="detail-row"><label>Email: </label><span id="view_email"></span></div>
            <div class="detail-row"><label>No. Telepon: </label><span id="view_telp"></span></div>
            <div class="detail-row"><label>Tanggal Lahir: </label><span id="view_tgl"></span></div>
            <div class="detail-row"><label>Alamat: </label><span id="view_alamat"></span></div>
            <div class="detail-row"><label>Cabang: </label><span id="view_cabang"></span></div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="document.getElementById('modalViewData').style.display='none'">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function viewJemaat(nama, telp, tgl, alamat, email, cabang) {

            document.getElementById('view_nama').innerText = nama;
            document.getElementById('view_telp').innerText = telp;
            document.getElementById('view_tgl').innerText = tgl;
            document.getElementById('view_alamat').innerText = alamat;
            document.getElementById('view_email').innerText = email;
            document.getElementById('view_cabang').innerText = cabang;

            document.getElementById('modalViewData').style.display = 'flex';
        }
    </script>
</body>

</html>