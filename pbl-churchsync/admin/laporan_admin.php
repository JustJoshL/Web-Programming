<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ibadah - Admin ChurchSync</title>
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
        }

        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .filter-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-doc {
            width: 45px;
            height: 45px;
            background: #eef2f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .report-text h4 {
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .report-text p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-ok {
            background-color: #dcfce7;
            color: #166534;
        }

        .btn-view {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
            <a href="laporan_admin.php" class="nav-link active">Laporan Ibadah</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">Halan Walker (Admin)</div>▼
                    <div class="dropdown-content"><a href="login.html">Logout</a></div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Rekap Laporan Ibadah</h2>
                    <div class="toolbar-actions">
                        <select class="filter-box">
                            <option>Bulan Ini (Mei 2026)</option>
                        </select>
                        <select class="filter-box">
                            <option>Semua Cabang</option>
                        </select>
                    </div>
                </div>
                <button
                    style="background: var(--primary-yellow); color: var(--primary-blue); padding: 10px 20px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">🖨️
                    Cetak PDF</button>
            </div>

            <div class="report-card">
                <div class="report-info">
                    <div class="icon-doc">📄</div>
                    <div class="report-text">
                        <h4>Ibadah Raya - GBI Maranatha Pusat</h4>
                        <p>Dilaporkan oleh: Pdt. Samuel Wibowo • Tanggal: 11 Mei 2026</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span class="badge-status status-ok">TERVERIFIKASI SISTEM</span>
                    <a href="#" class="btn-view">Lihat Detail</a>
                </div>
            </div>

            <div class="report-card">
                <div class="report-info">
                    <div class="icon-doc">📄</div>
                    <div class="report-text">
                        <h4>Ibadah Youth - GBI Maranatha Dago</h4>
                        <p>Dilaporkan oleh: Pdt. Andreas Siregar • Tanggal: 7 Mei 2026</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span class="badge-status status-ok">TERVERIFIKASI SISTEM</span>
                    <a href="#" class="btn-view">Lihat Detail</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>