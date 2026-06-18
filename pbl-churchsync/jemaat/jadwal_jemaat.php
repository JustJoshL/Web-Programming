<?php

/** @var mysqli $conn */
session_start();

include "../koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jemaat') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

$id_cabang_user = $_SESSION['id_cabang'];

$query_semua_jadwal = mysqli_query($conn, "
    SELECT * FROM jadwal_ibadah 
    WHERE waktu_pelaksanaan >= CURDATE() 
    AND id_cabang = '$id_cabang_user'
    ORDER BY waktu_pelaksanaan ASC
");

$jadwal_dikelompokkan = [];

$nama_hari_indo = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];

if ($query_semua_jadwal && mysqli_num_rows($query_semua_jadwal) > 0) {
    while ($row = mysqli_fetch_assoc($query_semua_jadwal)) {
        $hari_inggris = date('l', strtotime($row['waktu_pelaksanaan']));
        $hari_indo = $nama_hari_indo[$hari_inggris];
        $jadwal_dikelompokkan[$hari_indo][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ibadah - ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .day-section {
            margin-bottom: 30px;
        }

        .day-title {
            font-size: 20px;
            color: var(--primary-blue);
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }

        .schedule-card {
            display: flex;
            align-items: center;
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .time-box {
            width: 70px;
            height: 70px;
            background-color: #f1f5f9;
            border: 2px solid var(--primary-blue);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-blue);
            font-size: 14px;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .schedule-details h4 {
            font-size: 18px;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .schedule-details .location {
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 2px;
            font-weight: 600;
        }

        .schedule-details .session {
            font-size: 13px;
            color: #888;
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
            <a href="pengumuman_jemaat.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_jemaat.php" class="nav-link active">Jadwal Ibadah</a>
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
                <h2>Jadwal Ibadah</h2>
                <p>Jadwal Ibadah rutin di setiap cabang gereja</p>
            </div>

            <?php
            // Kalau jadwalnya kosong
            if (empty($jadwal_dikelompokkan)) {
                echo "<div style='text-align: center; color: #64748b; margin-top: 50px;'>Belum ada jadwal ibadah yang tersedia saat ini.</div>";
            } else {
                // Kalau jadwalnya ada, kita looping per Hari-nya dulu (Minggu, Rabu, dst)
                foreach ($jadwal_dikelompokkan as $hari => $daftar_jadwal) {
            ?>
                    <div class="day-section">
                        <!-- Judul Hari -->
                        <div class="day-title"><?= $hari; ?></div>

                        <?php
                        // Looping kotak jadwal di dalam hari tersebut
                        foreach ($daftar_jadwal as $jadwal) {
                        ?>
                            <div class="schedule-card">
                                <!-- Kotak Jam -->
                                <div class="time-box">
                                    <span><?= date('H:i', strtotime($jadwal['waktu_pelaksanaan'])); ?></span>
                                </div>

                                <!-- Detail -->
                                <div class="schedule-details">
                                    <h4><?= htmlspecialchars($jadwal['kategori_ibadah']); ?></h4>
                                    <p class="location">
                                        📍 <?= htmlspecialchars($jadwal['lokasi'] ?? 'Gereja Lokal'); ?> • <?= date('H:i', strtotime($jadwal['waktu_pelaksanaan'])); ?> WIB
                                    </p>
                                    <p class="session">Jadwal Rutin Jemaat</p>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
            <?php
                }
            }
            ?>

        </div>
    </div>
</body>

</html>