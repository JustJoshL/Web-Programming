<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .announcement-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .announcement-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            color: white;
            text-transform: uppercase;
        }

        .badge.important {
            background-color: var(--tag-important);
        }

        .badge.activity {
            background-color: var(--tag-activity);
        }

        .badge.worship {
            background-color: var(--tag-worship);
        }

        .badge.general {
            background-color: var(--text-gray);
        }

        .date {
            color: var(--text-gray);
        }

        .announcement-card h3 {
            color: var(--primary-blue);
            font-size: 20px;
            margin-bottom: 10px;
        }

        .announcement-card p {
            color: var(--text-dark);
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../uploads/churchsync-logo.png" alt="Logo ChurchSync">
            <div class="logo-text-wrapper">
                ChurchSync
                <span>
                    ALL ABOUT OUR CHURCH
                </span>
            </div>
        </div>
        <nav>
            <a href="dashboard_jemaat.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_jemaat.php" class="nav-link active">Pengumuman</a>
            <a href="jadwal_jemaat.php" class="nav-link">Jadwal Ibadah</a>
            <a href="profil_jemaat.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>

                <div class="user-profile-dropdown">
                    <div class="nav-avatar">👨🏽</div>
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?></div>
                    ▼
                    <div class="dropdown-content">
                        <a href="profil_jemaat.php">Profil Saya</a>
                        <a href="login.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Pengumuman</h2>
                <p>Informasi dan berita terbaru dari gereja</p>
            </div>
            <form method="GET" id="formFilter" style="
display:flex;
gap:10px;
margin-bottom:20px;
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
                    style="
        background:var(--primary-yellow);
        color:var(--primary-blue);
        border:none;
        padding:10px 20px;
        border-radius:6px;
        font-weight:bold;
        cursor:pointer;
        ">
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
                        Umum
                    </option>

                    <option value="cabang" <?= $filter == 'cabang' ? 'selected' : ''; ?>>
                        Cabang Saya
                    </option>

                </select>

            </form>
            <div class="announcement-container">
                <?php if (mysqli_num_rows($query_pengumuman) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($query_pengumuman)): ?>

                        <div class="announcement-card">

                            <div class="card-meta">

                                <span class="badge general">
                                    <?= $row['kategori_pengumuman']; ?>
                                </span>

                                <?php if ($row['target_tipe'] == 'umum'): ?>

                                    <span class="badge activity">
                                        UMUM
                                    </span>

                                <?php else: ?>

                                    <span class="badge important">
                                        CABANG
                                    </span>

                                <?php endif; ?>

                                <span class="date">
                                    <?= date('d F Y', strtotime($row['tanggal_publikasi'])); ?>
                                </span>

                            </div>

                            <h3>
                                <?= htmlspecialchars($row['judul_pengumuman']); ?>
                            </h3>

                            <p>
                                <?= nl2br(htmlspecialchars($row['isi_pengumuman'])); ?>
                            </p>

                            <?php if (!empty($row['gambar_pendukung'])): ?>

                                <img
                                    src="../uploads/<?= $row['gambar_pendukung']; ?>"
                                    style="
                    margin-top:15px;
                    max-width:300px;
                    border-radius:8px;
                    ">

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="announcement-card">
                        Tidak ada pengumuman ditemukan.
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>