<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jemaat') {
    header("location:../login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jemaat - ChurchSync</title>
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
            margin-bottom: 30px;
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

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: var(--primary-blue);
        }

        .birthday-list {
            display: flex;
            gap: 20px;
            justify-content: space-around;
        }

        .birthday-item {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 30%;
        }

        .birthday-item .avatar {
            margin: 0 auto 10px;
        }

        .btn-ucapan {
            margin-top: 10px;
            background-color: var(--primary-yellow);
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .pengumuman-list {
            list-style: none;
        }

        .pengumuman-list li {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            ChurchSync
            <span>ALL ABOUT OUR CHURCH</span>
        </div>
        <nav>
            <a href="dashboard_jemaat.php" class="nav-link active">Dashboard</a>
            <a href="pengumuman_jemaat.php" class="nav-link">Pengumuman</a>
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
                    <div class="nav-user-name">Justin Bieber</div>
                    ▼
                    <div class="dropdown-content">
                        <a href="profil_jemaat.php">Profil Saya</a>
                        <a href="login.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">

            <div class="profile-banner">
                <div class="profile-info">
                    <div class="avatar">👨🏽</div>
                    <div class="profile-text">
                        <h2>Justin Bieber</h2>
                        <p>Jemaat GBI Maranatha Dago</p>
                    </div>
                </div>
                <button class="btn-profile">Profile</button>
            </div>

            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3>Ulang Tahun (30 April 2026)</h3>
                </div>
                <div class="birthday-list">
                    <div class="birthday-item">
                        <div class="avatar">🧑🏽</div>
                        <p>Jemaat 1</p>
                        <button class="btn-ucapan">Kirim Ucapan</button>
                    </div>
                    <div class="birthday-item">
                        <div class="avatar">🧑🏽</div>
                        <p>Jemaat 2</p>
                        <button class="btn-ucapan">Kirim Ucapan</button>
                    </div>
                    <div class="birthday-item">
                        <div class="avatar">🧑🏽</div>
                        <p>Jemaat 3</p>
                        <button class="btn-ucapan">Kirim Ucapan</button>
                    </div>
                </div>
            </div>

            <div class="grid-container">
                <div class="card">
                    <div class="card-header">
                        <h3>Pengumuman</h3>
                    </div>
                    <ul class="pengumuman-list">
                        <li>Jadwal Ibadah Paskah 2026</li>
                        <li>Rekapitulasi Persembahan Per Wilayah</li>
                        <li>Pengumuman Pernikahan Pendeta</li>
                        <li>Update Lokasi Baru</li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>April 2026</h3>
                    </div>
                    <div
                        style="text-align: center; color: #666; padding: 40px 0; background: #f8fafc; border-radius: 8px;">
                        [Widget Kalender Placeholder]
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>