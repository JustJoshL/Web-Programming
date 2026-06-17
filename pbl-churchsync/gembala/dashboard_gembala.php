<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$nama_session = $_SESSION['nama_lengkap'];
$q_gembala = mysqli_query($conn, "SELECT j.*, c.nama_cabang 
                                  FROM jemaat j 
                                  JOIN cabang_gereja c ON j.id_cabang = c.id_cabang 
                                  WHERE j.nama_lengkap = '$nama_session' AND j.role = 'gembala_cabang'");
$gembala = mysqli_fetch_assoc($q_gembala);

$id_cabang_gembala = $gembala['id_cabang'];
$nama_cabang = $gembala['nama_cabang'];

$q_total_jemaat = mysqli_query($conn, "SELECT COUNT(*) as total FROM jemaat WHERE id_cabang = '$id_cabang_gembala' AND role = 'jemaat'");
$total_jemaat = mysqli_fetch_assoc($q_total_jemaat)['total'];

$q_total_laporan = mysqli_query($conn, "SELECT COUNT(p.id_pendataan) as total 
                                        FROM pendataan p 
                                        JOIN jadwal_ibadah j ON p.id_jadwal = j.id_jadwal 
                                        WHERE j.id_cabang = '$id_cabang_gembala'");
$total_laporan = mysqli_fetch_assoc($q_total_laporan)['total'];

$bulan_angka = date('m');
$q_ultah = mysqli_query($conn, "SELECT nama_lengkap, DAY(tanggal_lahir) as tgl 
                                FROM jemaat 
                                WHERE id_cabang = '$id_cabang_gembala' 
                                AND MONTH(tanggal_lahir) = '$bulan_angka' 
                                ORDER BY DAY(tanggal_lahir) ASC LIMIT 3");

$q_pengumuman = mysqli_query($conn, "SELECT judul_pengumuman, tanggal_publikasi 
                                     FROM pengumuman 
                                     WHERE status_publikasi = 'Published' 
                                     ORDER BY tanggal_publikasi DESC LIMIT 4");

$waktu_sekarang = date('Y-m-d H:i:s');
$query_jadwal_terdekat = mysqli_query($conn, "SELECT * FROM jadwal_ibadah 
                                              WHERE id_cabang = '$id_cabang_gembala' 
                                              AND waktu_pelaksanaan >= '$waktu_sekarang' 
                                              ORDER BY waktu_pelaksanaan ASC LIMIT 4");

$bulan_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$bulan_nama = $bulan_indo[date('n') - 1];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gembala - ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .profile-banner {
            background-color: var(--primary-blue);
            border-radius: 12px;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            margin-bottom: 25px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            background-color: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-blue);
        }

        .profile-text h2 {
            margin-bottom: 5px;
        }

        .profile-text p {
            color: #d1d5db;
            font-size: 14px;
        }

        .btn-profile {
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .card-header {
            margin-bottom: 15px;
            color: var(--primary-blue);
            font-weight: bold;
        }

        .birthday-list {
            display: flex;
            gap: 20px;
            justify-content: space-around;
        }

        .birthday-item {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 32%;
        }

        .birthday-item .avatar {
            margin: 0 auto 10px;
        }

        .btn-ucapan {
            margin-top: 10px;
            background-color: var(--primary-yellow);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .summary-section {
            margin-bottom: 25px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: #eef2f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-data p {
            font-size: 14px;
            color: #666;
        }

        .stat-data h3 {
            font-size: 24px;
            color: var(--primary-blue);
        }

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .pengumuman-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pengumuman-list li {
            background-color: #f1f5f9;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tgl-pengumuman {
            font-size: 12px;
            color: #666;
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
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_gembala.php" class="nav-link active">Dashboard</a>
            <a href="pengumuman_gembala.php" class="nav-link">Pengumuman</a>
            <a href="../admin/jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_gembala.php" class="nav-link">Data Jemaat</a>
            <a href="profil_gembala.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>

                <div class="user-profile-dropdown" onclick="toggleDropdown()">
                    <div class="nav-avatar">👨🏽‍💼</div>
                    <div class="nav-user-name"><?= htmlspecialchars($gembala['nama_lengkap']); ?> (Gembala) ▼</div> 
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="profil_gembala.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="profile-banner">
                <div class="profile-info">
                    <div class="avatar">👨🏽‍💼</div>
                    <div class="profile-text">
                        <h2><?= htmlspecialchars($gembala['nama_lengkap']); ?></h2>
                        <p>Gembala <?= htmlspecialchars($nama_cabang); ?></p>
                    </div>
                </div>
                <button class="btn-profile" onclick="window.location.href='profil_gembala.php'">Profile</button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Ulang Tahun Jemaat (7 Hari Mendatang)</h3>
                </div>
                <div class="birthday-list">
                    <?php if (mysqli_num_rows($q_ultah) > 0): ?>
                        <?php while ($ultah = mysqli_fetch_assoc($q_ultah)): ?>
                            <div class="birthday-item">
                                <div class="avatar">🧑🏽</div>
                                <p style="font-weight: bold; margin-bottom: 5px; color: var(--text-dark);"><?= htmlspecialchars($ultah['nama_lengkap']); ?></p>

                                <p style="font-size: 12px; color: #64748b; margin-top: 0; margin-bottom: 3px;"><?= $ultah['tgl'] . ' ' . $bulan_indo[$ultah['bln'] - 1]; ?></p>

                                <p style="font-size: 11px; color: var(--primary-blue); font-weight: 600; margin-top: 0; margin-bottom: 8px;">
                                    📍 <?= $ultah['nama_cabang'] ? htmlspecialchars($ultah['nama_cabang']) : 'Pusat'; ?>
                                </p>

                                <button class="btn-ucapan" onclick="kirimUcapanIni(<?= $ultah['id_jemaat']; ?>, this)">Kirim Ucapan</button>
                                <p style="font-size: 11px; margin-top: 8px; color: #f59e0b; font-weight: bold;">
                                    🎉 <?= $ultah['total_ucapan']; ?> orang mengucapkan
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #64748b; padding: 10px;">Tidak ada jemaat yang berulang tahun dalam 7 hari ke depan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-title">Rangkuman Gembala</div>
                <div class="summary-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-data">
                            <p>Total Jemaat Cabang</p>
                            <h3><?= $total_jemaat; ?> Jiwa</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📄</div>
                        <div class="stat-data">
                            <p>Total Laporan Terkirim</p>
                            <h3><?= $total_laporan; ?> Laporan</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-container">
                <div class="card">
                    <div class="card-header">
                        <h3>Pengumuman Terbaru</h3>
                    </div>
                    <ul class="pengumuman-list">
                        <?php if (mysqli_num_rows($q_pengumuman) > 0): ?>
                            <?php while ($pengumuman = mysqli_fetch_assoc($q_pengumuman)): ?>
                                <li>
                                    <span><?= htmlspecialchars($pengumuman['judul_pengumuman']); ?></span>
                                    <span class="tgl-pengumuman"><?= date('d M', strtotime($pengumuman['tanggal_publikasi'])); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li style="justify-content: center; color: #64748b;">Belum ada pengumuman.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📅 Jadwal Ibadah Mendatang</h3>
                    </div>

                    <?php if (mysqli_num_rows($query_jadwal_terdekat) == 0) : ?>
                        <div style="text-align: center; color: #666; padding: 40px 0; background: #f8fafc; border-radius: 8px; font-size: 14px;">
                            [ Tidak ada jadwal ibadah terdekat ]
                        </div>
                    <?php else : ?>
                        <ul style="padding: 0; margin: 0;">
                            <?php while ($row_jdwal = mysqli_fetch_assoc($query_jadwal_terdekat)) : ?>
                                <li style="list-style: none; background: #f8fafc; margin-bottom: 8px; padding: 10px 15px; border-radius: 8px; border-left: 4px solid var(--primary-yellow);">
                                    <div>
                                        <strong style="color: var(--primary-blue); display: block; margin-bottom: 3px;"><?= htmlspecialchars($row_jdwal['kategori_ibadah']); ?></strong>
                                        <div style="font-size: 12px; color: #666;">
                                            🕒 <?= date('l, d M Y - H:i', strtotime($row_jdwal['waktu_pelaksanaan'])); ?> WIB
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
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