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
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
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
                <div class="noti-icon">
                    🔔<span class="noti-badge"></span>
                </div>

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
                <h2>Pengumuman</h2>
                <p>Informasi dan berita terbaru dari gereja</p>
            </div>

            <div class="announcement-container">
                <div class="announcement-card">
                    <div class="card-meta">
                        <span class="badge important">Penting</span>
                        <span class="date">28 April 2026</span>
                    </div>
                    <h3>Ibadah Natal Bersama 2026</h3>
                    <p>Ibadah Natal Bersama akan dilaksanakan pada tanggal 25 Desember 2026 pukul 09.00 WIB di Gedung
                        Utama
                        GBI Maranatha Pusat. Seluruh jemaat dari semua cabang diundang untuk hadir bersama keluarga.</p>
                </div>

                <div class="announcement-card">
                    <div class="card-meta">
                        <span class="badge activity">Kegiatan</span>
                        <span class="date">23 April 2026</span>
                    </div>
                    <h3>Pendaftaran Pelayanan Worship Team</h3>
                    <p>Dibuka pendaftaran untuk bergabung dalam tim pujian dan penyembahan gereja. Bagi jemaat yang
                        memiliki
                        talenta di bidang musik dan vokal, silakan mendaftarkan diri melalui sekretariat masing-masing
                        cabang.</p>
                </div>

                <div class="announcement-card">
                    <div class="card-meta">
                        <span class="badge worship">Ibadah</span>
                        <span class="date">20 April 2026</span>
                    </div>
                    <h3>Jadwal Ibadah Bulan Mei 2026</h3>
                    <p>Informasi jadwal ibadah untuk bulan Mei 2026 telah diperbarui. Terdapat perubahan waktu ibadah
                        pada
                        Ibadah sesi kedua di cabang Dago. Silakan cek halaman jadwal ibadah untuk informasi
                        selengkapnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>