<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Verifikasi - Admin ChurchSync</title>
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

        .history-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-info h4 {
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .history-info p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-approve {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-reject {
            background-color: #fef2f2;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard-admin.html" class="nav-link">Dashboard</a>
            <a href="pengumuman-admin.html" class="nav-link">Pengumuman</a>
            <a href="jadwal-admin-up.html" class="nav-link">Jadwal Ibadah</a>
            <a href="data-jemaat-admin.html" class="nav-link active">Data Jemaat</a>
            <a href="cabang-admin.html" class="nav-link">Cabang Gereja</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">Halan Walker</div>▼
                    <div class="dropdown-content"><a href="login.html">Logout</a></div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Riwayat Verifikasi</h2>
                    <p>Catatan persetujuan dan penolakan pembaruan data jemaat</p>
                </div>
                <a href="verifikasi-admin.html"
                    style="color: var(--primary-blue); font-weight: bold; text-decoration: none;">← Kembali ke
                    Antrean</a>
            </div>

            <div class="history-card">
                <div class="history-info">
                    <h4>Vanessa Felicia</h4>
                    <p>Pembaruan Nomor Telepon • Diverifikasi pada: 25 April 2026, 14:30 WIB</p>
                </div>
                <div class="badge-status status-approve">DISETUJUI</div>
            </div>

            <div class="history-card">
                <div class="history-info">
                    <h4>Joshua Lewi</h4>
                    <p>Pembaruan Alamat Domisili • Diverifikasi pada: 20 April 2026, 09:15 WIB</p>
                </div>
                <div class="badge-status status-reject">DITOLAK</div>
            </div>
        </div>
    </div>
</body>

</html>