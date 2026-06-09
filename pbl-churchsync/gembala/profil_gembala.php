<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - ChurchSync</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        /* --- INTERNAL CSS KHUSUS PROFIL --- */
        .profile-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .profile-user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .big-avatar {
            width: 80px;
            height: 80px;
            background-color: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
        }

        .user-meta h3 {
            color: var(--primary-blue);
            font-size: 22px;
            margin-bottom: 2px;
        }

        .user-meta p {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            font-size: 14px;
            color: var(--text-dark);
        }

        .form-group input:focus {
            outline: 2px solid var(--primary-blue);
            background-color: #fff;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .btn-logout {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
        }

        .btn-submit {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #152449;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_gembala.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_gembala.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_gembala.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_gembala.php" class="nav-link">Data Jemaat</a>
            <a href="profil_gembala.php" class="nav-link active">Profil Saya</a>
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
                        <a href="profil-gembala.html">Profil Saya</a>
                        <a href="login.html" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="page-header">
                <h2>Profil Saya</h2>
                <p>Kelola Informasi Pribadi Anda</p>
            </div>

            <div class="profile-card">
                <div class="profile-user-info">
                    <div class="big-avatar">👨🏽‍💼</div>
                    <div class="user-meta">
                        <h3>Kristian Tohalim, S.Th.</h3>
                        <p>kristian.tohalim@gmail.com</p>
                        <span class="role-badge">Gembala Cabang</span>
                    </div>
                </div>

                <form>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="kristian.tohalim@gmail.com">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="Kristian Tohalim, S.Th.">
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" value="08198765432">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="text" value="15/08/1980">
                        </div>
                        <div class="form-group full-width">
                            <label>Alamat</label>
                            <input type="text" value="Perumahan Timur Indah Blok C2, Bandung">
                        </div>
                        <div class="form-group full-width">
                            <label>Cabang Penugasan (Tidak bisa diedit)</label>
                            <input type="text" value="GBI Maranatha Timur" disabled
                                style="background-color: #e2e8f0; cursor: not-allowed;">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="login.html" class="btn-logout">Logout</a>
                        <button type="button" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

</body>

</html>