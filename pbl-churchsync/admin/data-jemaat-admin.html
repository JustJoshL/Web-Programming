<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jemaat - Admin ChurchSync</title>
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

        .search-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 200px;
        }

        .filter-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-add {
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-verifikasi {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .list-card {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .item-text h4 {
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .item-text p {
            color: var(--text-gray);
            font-size: 13px;
        }

        .action-btns button {
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 5px;
        }

        .btn-edit {
            background-color: #eef2f6;
            color: var(--primary-blue);
        }

        /* Modal Style */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 500px;
            border-radius: 12px;
            padding: 30px;
        }

        .modal-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            color: var(--primary-blue);
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            background: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .akun-wrapper {
            display: none;
            /* Default sembunyi */
            margin-top: 10px;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .akun-wrapper.aktif {
            display: block !important;
            /* Paksa muncul */
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
            <a href="profil-admin.html" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">Halan Walker (Admin)</div>▼
                    <div class="dropdown-content">
                        <a href="profil-admin.html">Profil Saya</a>
                        <a href="login.html" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Data Jemaat Global</h2>
                    <div class="toolbar-actions">
                        <input type="text" class="search-box" placeholder="Cari jemaat...">
                        <select class="filter-box">
                            <option>Semua Cabang</option>
                            <option>GBI Pusat</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="verifikasi-admin.html" class="btn-verifikasi">Lihat Antrean Verifikasi (5)</a>
                    <button class="btn-add"
                        onclick="document.getElementById('modalTambahDataAdmin').style.display='flex'">
                        + Tambah Data Jemaat
                    </button>

                    <div id="modalTambahDataAdmin" class="modal-overlay">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Tambah Data Jemaat</h3>
                            </div>

                            <div class="form-group"><label>Nama Lengkap</label><input type="text"
                                    placeholder="Masukkan nama jemaat..."></div>
                            <div class="form-group"><label>Cabang Penempatan</label>
                                <select>
                                    <option>GBI Maranatha Pusat</option>
                                    <option>GBI Maranatha Dago</option>
                                </select>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div class="form-group"><label>Nomor Telepon</label><input type="text"
                                        placeholder="0812xxxx"></div>
                                <div class="form-group"><label>Tanggal Lahir</label><input type="date"></div>
                            </div>
                            <div class="form-group"><label>Alamat Domisili</label><input type="text"
                                    placeholder="Masukkan alamat lengkap..."></div>

                            <div
                                style="margin-top: 15px; padding: 15px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px;">
                                <div style="padding: 10px; background: #eef2f6; border-radius: 8px;">
                                    <input type="checkbox" id="toggleAkun" onclick="toggleAkun(this)"
                                        style="transform: scale(1.2); margin-right: 8px;">
                                    <label for="toggleAkun"
                                        style="font-weight: bold; color: var(--primary-blue); cursor: pointer;">
                                        Buatkan Akun Login Web (Opsional)
                                    </label>
                                </div>

                                <div id="akunFields" class="akun-wrapper">
                                    <div class="form-group"><label>Email Login</label><input type="email"></div>
                                    <div class="form-group"><label>Password Default</label><input type="text"
                                            value="churchsync123" disabled></div>
                                </div>
                            </div>

                            <div class="modal-actions">
                                <button class="btn-cancel"
                                    onclick="document.getElementById('modalTambahDataAdmin').style.display='none'">Batal</button>
                                <button class="btn-add"
                                    style="background-color: var(--primary-blue); color: white;">Simpan Data</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleAkun(checkbox) {
                            var fields = document.getElementById('akunFields');
                            if (checkbox.checked) {
                                fields.style.display = 'block';
                            } else {
                                fields.style.display = 'none';
                            }
                        }
                    </script>

                </div>
            </div>

            <div class="list-card">
                <div class="item-info">
                    <div class="avatar">👨🏽</div>
                    <div class="item-text">
                        <h4>Justin Bieber</h4>
                        <p>GBI Maranatha Dago • justin@gmail.com • Aktif</p>
                    </div>
                </div>
                <div class="action-btns"><button class="btn-edit" src="verifikasi-admin.html">Edit Data</button></div>
            </div>

            <div class="list-card">
                <div class="item-info">
                    <div class="avatar">👩🏻</div>
                    <div class="item-text">
                        <h4>Vanessa Felicia</h4>
                        <p>GBI Maranatha Pusat • vanessa@gmail.com • Aktif</p>
                    </div>
                </div>
                <div class="action-btns"><button class="btn-edit">Edit Data</button></div>
            </div>
        </div>
    </div>

    <div id="modalJemaat" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Registrasi Akun Jemaat Baru</h3>
            </div>
            <div class="form-group"><label>Nama Lengkap</label><input type="text"></div>
            <div class="form-group"><label>Email (Untuk Login)</label><input type="email"></div>
            <div class="form-group"><label>Password Default</label><input type="text" value="churchsync123" disabled>
            </div>
            <div class="form-group"><label>Cabang Gereja</label><select>
                    <option>GBI Maranatha Pusat</option>
                    <option>GBI Maranatha Dago</option>
                </select></div>
            <div class="modal-actions">
                <button class="btn-cancel"
                    onclick="document.getElementById('modalJemaat').style.display='none'">Batal</button>
                <button class="btn-add">Buat Akun</button>
            </div>
        </div>
    </div>
</body>

</html>