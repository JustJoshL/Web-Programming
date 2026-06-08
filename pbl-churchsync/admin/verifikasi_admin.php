<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

/* PROSES SETUJUI */
if (isset($_GET['setujui'])) {

    $id_pengajuan = $_GET['setujui'];

    $q = mysqli_query($conn, "
        SELECT *
        FROM temp_update_jemaat
        WHERE id_pengajuan='$id_pengajuan'
    ");

    $data = mysqli_fetch_assoc($q);

    mysqli_query($conn, "
        UPDATE jemaat
        SET
            no_telp='" . $data['no_hp_baru'] . "',
            alamat='" . $data['alamat_baru'] . "'
        WHERE id_jemaat='" . $data['id_jemaat'] . "'
    ");

    mysqli_query($conn, "
        UPDATE temp_update_jemaat
        SET status_pengajuan='disetujui'
        WHERE id_pengajuan='$id_pengajuan'
    ");

    header("Location: verifikasi_admin.php");
    exit();
}

/* PROSES TOLAK */
if (isset($_GET['tolak'])) {

    $id_pengajuan = $_GET['tolak'];

    mysqli_query($conn, "
        UPDATE temp_update_jemaat
        SET status_pengajuan='ditolak'
        WHERE id_pengajuan='$id_pengajuan'
    ");

    header("Location: verifikasi_admin.php");
    exit();
}

$query_pengajuan = mysqli_query($conn, "
    SELECT
        t.*,
        j.nama_lengkap,
        j.no_telp,
        j.alamat
    FROM temp_update_jemaat t
    JOIN jemaat j
        ON t.id_jemaat = j.id_jemaat
    WHERE t.status_pengajuan='pending'
    ORDER BY t.tanggal_pengajuan DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Data - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .header-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .req-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid #ffc107;
        }

        .req-info h4 {
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .req-info p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .btn-tinjau {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        /* Modal Tinjau (Kiri-Kanan) */
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
            width: 700px;
            border-radius: 12px;
            padding: 30px;
        }

        .diff-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .diff-box {
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .diff-old {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .diff-new {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-tolak {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-terima {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
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
            <a href="data_jemaat_admin.php" class="nav-link active">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">Halan Walker (Admin)</div>▼
                    <div class="dropdown-content">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="login.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Verifikasi Perubahan Data</h2>
                    <p>Tinjau pengajuan perubahan data diri dari jemaat</p>
                </div>
                <a href="data_jemaat_admin.php"
                    style="color: var(--primary-blue); font-weight: bold; text-decoration: none;">← Kembali ke Data
                    Jemaat</a>
            </div>

            <?php while ($row = mysqli_fetch_assoc($query_pengajuan)) : ?>

                <div class="req-card">

                    <div class="req-info">
                        <h4><?= $row['nama_lengkap']; ?></h4>

                        <p>
                            Pengajuan Perubahan Data
                            • <?= $row['tanggal_pengajuan']; ?>
                        </p>
                    </div>

                    <button
                        class="btn-tinjau"
                        onclick="bukaModal(
            '<?= $row['id_pengajuan']; ?>',
            '<?= htmlspecialchars($row['nama_lengkap']); ?>',
            '<?= htmlspecialchars($row['no_telp']); ?>',
            '<?= htmlspecialchars($row['alamat']); ?>',
            '<?= htmlspecialchars($row['no_hp_baru']); ?>',
            '<?= htmlspecialchars($row['alamat_baru']); ?>'
        )">
                        Tinjau Perubahan
                    </button>

                </div>

            <?php endwhile; ?>
        </div>
    </div>

    <div id="modalTinjau" class="modal-overlay">
        <div class="modal-content">

            <h3 id="modalNama"
                style="color: var(--primary-blue); border-bottom:1px solid #eee; padding-bottom:15px;">
            </h3>

            <div class="diff-grid">

                <div class="diff-box diff-old">
                    <strong style="color:#dc3545;">❌ Data Lama</strong>
                    <br><br>

                    <strong>No. Telepon:</strong>
                    <span id="oldTelp"></span>

                    <br>

                    <strong>Alamat:</strong>
                    <span id="oldAlamat"></span>
                </div>

                <div class="diff-box diff-new">
                    <strong style="color:#28a745;">✅ Pengajuan Data Baru</strong>
                    <br><br>

                    <strong>No. Telepon:</strong>
                    <span id="newTelp"></span>

                    <br>

                    <strong>Alamat:</strong>
                    <span id="newAlamat"></span>
                </div>

            </div>

            <div class="modal-actions">

                <button
                    class="btn-cancel"
                    style="margin-right:auto;"
                    onclick="document.getElementById('modalTinjau').style.display='none'">
                    Tutup
                </button>

                <a id="btnTolak" class="btn-tolak">
                    Tolak Ajuan
                </a>

                <a id="btnSetujui" class="btn-terima">
                    Verifikasi & Simpan
                </a>

            </div>

        </div>
    </div>
    <script>
        function bukaModal(
            id,
            nama,
            telpLama,
            alamatLama,
            telpBaru,
            alamatBaru
        ) {

            document.getElementById('modalTinjau').style.display = 'flex';

            document.getElementById('modalNama').innerHTML =
                'Tinjau Perubahan : ' + nama;

            document.getElementById('oldTelp').innerHTML = telpLama;
            document.getElementById('oldAlamat').innerHTML = alamatLama;

            document.getElementById('newTelp').innerHTML = telpBaru;
            document.getElementById('newAlamat').innerHTML = alamatBaru;

            document.getElementById('btnSetujui').href =
                'verifikasi_admin.php?setujui=' + id;

            document.getElementById('btnTolak').href =
                'verifikasi_admin.php?tolak=' + id;
        }
    </script>
</body>

</html>