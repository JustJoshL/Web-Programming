<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../koneksi.php';

$query_cabang = mysqli_query($conn, "SELECT COUNT(*) as total_cabang FROM cabang_gereja");
$data_cabang = mysqli_fetch_assoc($query_cabang);

$query_jemaat = mysqli_query($conn, "SELECT COUNT(*) as total_jemaat FROM jemaat");
$data_jemaat = mysqli_fetch_assoc($query_jemaat);

$query_laporan = mysqli_query($conn, "SELECT COUNT(*) as total_laporan FROM pendataan");
$data_laporan = mysqli_fetch_assoc($query_laporan);

$query_verifikasi = mysqli_query($conn, "SELECT COUNT(*) as butuh_verif FROM temp_update_jemaat WHERE status_pengajuan = 'pending'");
$data_verifikasi = mysqli_fetch_assoc($query_verifikasi);

$query_jadwal_terdekat = mysqli_query($conn, "
    SELECT kategori_ibadah, waktu_pelaksanaan 
    FROM jadwal_ibadah 
    WHERE waktu_pelaksanaan >= NOW() 
    ORDER BY waktu_pelaksanaan ASC 
    LIMIT 3
");

$query_aktivitas = mysqli_query($conn, "
    (SELECT 'laporan' AS tipe, waktu_pelaporan AS waktu, 'Gembala Cabang' AS aktor, 'mengirimkan laporan pendataan ibadah baru' AS aksi 
     FROM pendataan)
    UNION
    (SELECT 'update' AS tipe, temp_update_jemaat.tanggal_pengajuan AS waktu, jemaat.nama_lengkap AS aktor, 'mengajukan perubahan data profil' AS aksi 
     FROM temp_update_jemaat 
     JOIN jemaat ON temp_update_jemaat.id_jemaat = jemaat.id_jemaat)
    ORDER BY waktu DESC 
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ChurchSync</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .admin-banner {
            background-color: var(--primary-blue);
            border-radius: 12px;
            padding: 25px 30px;
            color: white;
            margin-bottom: 25px;
        }

        .admin-banner h2 {
            margin-bottom: 5px;
        }

        .admin-banner p {
            color: #d1d5db;
            font-size: 14px;
        }

        .stat-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card-admin {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .icon-box-admin {
            width: 50px;
            height: 50px;
            background-color: #eef2f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .data-box-admin p {
            font-size: 13px;
            color: #666;
            margin-bottom: 2px;
        }

        .data-box-admin h3 {
            font-size: 22px;
            color: var(--primary-blue);
        }

        .grid-two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card-admin {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-admin-header {
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .recent-activity-list {
            list-style: none;
        }

        .recent-activity-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: var(--text-dark);
            display: flex;
            justify-content: space-between;
        }

        .recent-activity-list li:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link active">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">
                    🔔<span class="noti-badge"></span>
                </div>

                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?> (Admin)</div>
                    ▼
                    <div class="dropdown-content">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="login.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="admin-banner">
                <h2>Selamat Datang Kembali, <?= $_SESSION['nama_lengkap']; ?>!</h2>
                <p>Sistem Informasi Administrasi Gereja Terpusat — Akun Admin Utama</p>
            </div>

            <div class="stat-grid-4">
                <div class="stat-card-admin">
                    <div class="icon-box-admin">⛪</div>
                    <div class="data-box-admin">
                        <p>Total Cabang</p>
                        <h3><?= $data_cabang['total_cabang']; ?> Cabang</h3>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="icon-box-admin">👥</div>
                    <div class="data-box-admin">
                        <p>Total Jemaat</p>
                        <h3><?= $data_jemaat['total_jemaat']; ?> Orang</h3>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="icon-box-admin">📄</div>
                    <div class="data-box-admin">
                        <p>Laporan Masuk</p>
                        <h3><?= $data_laporan['total_laporan']; ?> Laporan</h3>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="icon-box-admin">⏳</div>
                    <div class="data-box-admin">
                        <p>Butuh Verifikasi</p>
                        <h3 style="color: #dc3545;">5 Data</h3>
                    </div>
                </div>
            </div>

            <div class="grid-two-column">
                <div class="card-admin">
                    <div class="card-admin-header">🔄 Aktivitas Sistem Terbaru</div>
                    <ul class="recent-activity-list">
                        <?php if (mysqli_num_rows($query_aktivitas) == 0) : ?>
                            <li style="justify-content: center; color: #94a3b8;">Belum ada aktivitas sistem terbaru.</li>
                        <?php else : ?>
                            <?php while ($row_act = mysqli_fetch_assoc($query_aktivitas)) : ?>
                                <li>
                                    <span>
                                        <?= $row_act['tipe'] == 'laporan' ? '📊' : '👤'; ?>
                                        <strong><?= $row_act['aktor']; ?></strong> <?= $row_act['aksi']; ?>
                                    </span>
                                    <span class="activity-time">
                                        <?= date('d M Y, H:i', strtotime($row_act['waktu'])); ?>
                                    </span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card-admin">
                    <div class="card-admin-header">📅 Jadwal Ibadah Mendatang</div>
                    <?php if (mysqli_num_rows($query_jadwal_terdekat) == 0) : ?>
                        <div style="text-align: center; color: #666; padding: 40px 0; background: #f8fafc; border-radius: 8px; font-size: 14px;">
                            [ Tidak ada jadwal ibadah terdekat ]
                        </div>
                    <?php else : ?>
                        <?php while ($row_jdwal = mysqli_fetch_assoc($query_jadwal_terdekat)) : ?>
                            <li style="background: #f8fafc; margin-bottom: 8px; padding: 10px 15px; border-radius: 8px; border-left: 4px solid var(--primary-yellow);">
                                <div>
                                    <strong style="color: var(--primary-blue); d-block;"><?= $row_jdwal['kategori_ibadah']; ?></strong>
                                    <div style="font-size: 12px; color: #666; margin-top: 3px;">
                                        🕒 <?= date('l, d M Y - H:i', strtotime($row_jdwal['waktu_pelaksanaan'])); ?> WIB
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</body>

</html>