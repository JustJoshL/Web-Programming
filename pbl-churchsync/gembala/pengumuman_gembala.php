<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_cabang = $_SESSION['id_cabang'];

$filter = $_GET['filter'] ?? 'semua';
$cari   = $_GET['cari'] ?? '';

$where = "
status_publikasi='Published'
AND (
    target_tipe='umum'
    OR id_cabang='$id_cabang'
)
";

if ($filter == 'umum') {
    $where .= " AND target_tipe='umum'";
}

if ($filter == 'cabang') {
    $where .= " AND id_cabang='$id_cabang'";
}

if (!empty($cari)) {
    $where .= "
    AND (
        judul_pengumuman LIKE '%$cari%'
        OR isi_pengumuman LIKE '%$cari%'
    )";
}

$query_pengumuman = mysqli_query($conn, "
    SELECT *
    FROM pengumuman
    WHERE $where
    ORDER BY tanggal_publikasi DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - ChurchSync</title>

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
            margin-bottom: 5px;
        }

        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }

        /* CSS Card Pengumuman */
        .list-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border-left: 5px solid var(--primary-blue);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 15px;
        }

        .list-info h4 {
            color: var(--primary-blue);
            margin-bottom: 8px;
            font-size: 20px;
        }

        .list-info p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .badge-kategori {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            margin-right: 10px;
            background-color: #17a2b8;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 10px;
        }

        .status-Published {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-Draft {
            background-color: #fef3c7;
            color: #b45309;
        }

        .pengumuman-body {
            font-size: 15px;
            color: var(--text-dark);
            line-height: 1.6;
            white-space: pre-line;
            /* Mempertahankan enter/baris baru dari database */
        }

        .pengumuman-img {
            margin-top: 15px;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
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

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 600px;
            border-radius: 12px;
            padding: 30px;
        }

        .modal-header {
            margin-bottom: 20px;
            color: var(--primary-blue);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-cancel {
            background: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
        }

        .btn-upload {
            background: #eef2f6;
            color: var(--primary-blue);
            border: 1px dashed var(--primary-blue);
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: #dbeafe;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_gembala.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_gembala.php" class="nav-link active">Pengumuman</a>
            <a href="../admin/jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_gembala.php" class="nav-link">Data Jemaat</a>
            <a href="profil_gembala.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>

                <div class="user-profile-dropdown">
                    <div class="nav-avatar">👨🏽‍💼</div>
                    <div class="nav-user-name">Kristian Tohalim, S.Th.</div>
                    ▼
                    <div class="dropdown-content">
                        <a href="profil_gembala.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">

                <div class="page-title">
                    <h2>Informasi & Pengumuman</h2>
                    <p>Pusat informasi kegiatan dan ibadah gereja</p>
                </div>

                <button class="btn-add"
                    onclick="document.getElementById('modalTambah').style.display='flex'">
                    + Buat Pengumuman
                </button>

            </div>

            <form id="formFilter" method="GET" style="
display:flex;
gap:10px;
margin-bottom:25px;
flex-wrap:wrap;
align-items:center;
">

                <input
                    type="text"
                    name="cari"
                    placeholder="Cari pengumuman..."
                    value="<?= htmlspecialchars($cari); ?>"
                    style="
        padding:10px;
        border:1px solid #ccc;
        border-radius:6px;
        min-width:250px;
        ">

                <button
                    type="submit"
                    class="btn-add">
                    Cari
                </button>

                <select
                    name="filter"
                    onchange="document.getElementById('formFilter').submit()"
                    style="
        padding:10px;
        border:1px solid #ccc;
        border-radius:6px;
        ">

                    <option value="semua" <?= $filter == 'semua' ? 'selected' : ''; ?>>
                        Semua
                    </option>

                    <option value="umum" <?= $filter == 'umum' ? 'selected' : ''; ?>>
                        Pengumuman Umum
                    </option>

                    <option value="cabang" <?= $filter == 'cabang' ? 'selected' : ''; ?>>
                        Cabang Saya
                    </option>

                </select>

            </form>

            <?php
            if (mysqli_num_rows($query_pengumuman) > 0) {
                while ($row = mysqli_fetch_assoc($query_pengumuman)) {
            ?>
                    <div class="list-card">
                        <div class="list-header">
                            <div class="list-info">
                                <h4><?= htmlspecialchars($row['judul_pengumuman']); ?></h4>
                                <p>
                                <p>

                                    <span class="badge-kategori">
                                        <?= htmlspecialchars($row['kategori_pengumuman']); ?>
                                    </span>

                                    <?php if ($row['target_tipe'] == 'umum') : ?>

                                        <span class="badge-status status-Published">
                                            UMUM
                                        </span>

                                    <?php else : ?>

                                        <span class="badge-status status-Draft">
                                            CABANG
                                        </span>

                                    <?php endif; ?>
                                    Dipublikasikan pada: <?= date('d F Y', strtotime($row['tanggal_publikasi'])); ?>
                                </p>
                            </div>
                        </div>

                        <div class="pengumuman-body">
                            <?= htmlspecialchars($row['isi_pengumuman']); ?>

                            <?php if (!empty($row['gambar_pendukung'])) { ?>
                                <br>
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_pendukung']); ?>" alt="Gambar Pengumuman" class="pengumuman-img">
                            <?php } ?>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p style='text-align: center; color: var(--text-gray); padding: 40px; background: white; border-radius: 12px;'>Belum ada informasi atau pengumuman saat ini.</p>";
            }
            ?>

        </div>
    </div>
    <div id="modalTambah" class="modal-overlay">

        <div class="modal-content">

            <div class="modal-header">
                <h3>Buat Pengumuman Cabang</h3>
            </div>

            <form
                action="proses_tambah_pengumuman_gembala.php"
                method="POST"
                enctype="multipart/form-data">

                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input
                        type="text"
                        name="judul_pengumuman"
                        required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>

                    <select
                        name="kategori_pengumuman"
                        required>

                        <option value="Penting">Penting</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Ibadah">Ibadah</option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Publikasi</label>

                    <input
                        type="date"
                        name="tanggal_publikasi"
                        required>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>

                    <textarea
                        name="isi_pengumuman"
                        rows="5"
                        required></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar (Opsional)</label>

                    <button type="button" class="btn-upload" onclick="document.getElementById('uploadGambar').click()">
                        📷 Pilih Gambar untuk Diunggah
                    </button>
                </div>

                <div class="modal-actions">

                    <button
                        type="button"
                        class="btn-cancel"
                        onclick="document.getElementById('modalTambah').style.display='none'">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn-add">

                        Simpan Pengumuman

                    </button>

                </div>

            </form>

        </div>

    </div>
</body>

</html>