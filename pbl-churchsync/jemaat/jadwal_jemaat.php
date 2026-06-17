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
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
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
            <div class="page-header">
                <h2>Jadwal Ibadah</h2>
                <p>Jadwal Ibadah rutin di setiap cabang gereja</p>
            </div>

            <div class="day-section">
                <div class="day-title">Minggu</div>

                <div class="schedule-card">
                    <div class="time-box"><span>07:00</span></div>
                    <div class="schedule-details">
                        <h4>Ibadah Raya</h4>
                        <p class="location">GBI Maranatha Pusat • 07:00 - 09.00 WIB</p>
                        <p class="session">Ibadah Raya Segmen Wilayah - Sesi 1</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="time-box"><span>10:00</span></div>
                    <div class="schedule-details">
                        <h4>Ibadah Raya</h4>
                        <p class="location">GBI Maranatha Pusat • 10:00 - 12.00 WIB</p>
                        <p class="session">Ibadah Raya Segmen Wilayah - Sesi 2</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="time-box"><span>09:00</span></div>
                    <div class="schedule-details">
                        <h4>Ibadah Raya</h4>
                        <p class="location">GBI Maranatha Dago • 09:00 - 11.00 WIB</p>
                        <p class="session">Ibadah Raya Segmen Wilayah</p>
                    </div>
                </div>
            </div>

            <div class="day-section">
                <div class="day-title">Rabu</div>

                <div class="schedule-card">
                    <div class="time-box"><span>19:00</span></div>
                    <div class="schedule-details">
                        <h4>Ibadah Raya</h4>
                        <p class="location">GBI Maranatha Pusat • 19:00 - 20.30 WIB</p>
                        <p class="session">Ibadah Ibu Rumah Tangga</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>