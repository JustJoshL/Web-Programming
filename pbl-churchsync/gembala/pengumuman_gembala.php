<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$query_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman WHERE status_publikasi = 'Published' ORDER BY tanggal_publikasi DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - ChurchSync</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .header-toolbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .page-title h2 { color: var(--primary-blue); font-size: 28px; margin-bottom: 5px; }
        .page-title p { color: var(--text-gray); font-size: 14px; }
        
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

        .list-info h4 { color: var(--primary-blue); margin-bottom: 8px; font-size: 20px; }
        .list-info p { color: var(--text-gray); font-size: 13px; }
        
        .badge-kategori { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; color: white; margin-right: 10px; background-color: #17a2b8; }
        .badge-status { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-right: 10px; }
        .status-Published { background-color: #dcfce7; color: #166534; }
        .status-Draft { background-color: #fef3c7; color: #b45309; }

        .pengumuman-body {
            font-size: 15px;
            color: var(--text-dark);
            line-height: 1.6;
            white-space: pre-line; /* Mempertahankan enter/baris baru dari database */
        }

        .pengumuman-img {
            margin-top: 15px;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
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
                <div class="noti-icon">
                    🔔<span class="noti-badge"></span>
                </div>

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
            </div>

            <?php 
            if(mysqli_num_rows($query_pengumuman) > 0) {
                while($row = mysqli_fetch_assoc($query_pengumuman)) { 
            ?>
            <div class="list-card">
                <div class="list-header">
                    <div class="list-info">
                        <h4><?= htmlspecialchars($row['judul_pengumuman']); ?></h4>
                        <p>
                            <span class="badge-kategori"><?= htmlspecialchars($row['kategori_pengumuman']); ?></span>
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

</body>
</html>