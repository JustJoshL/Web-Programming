<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabang Gereja - Admin ChurchSync</title>
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

        .btn-add {
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .branch-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .branch-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            border-top: 4px solid var(--primary-yellow);
        }

        .branch-card h3 {
            color: var(--primary-blue);
            font-size: 20px;
            margin-bottom: 15px;
        }

        .branch-detail {
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-gray);
            display: flex;
            gap: 10px;
        }

        .action-btns {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .action-btns button {
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            flex: 1;
        }

        .btn-edit {
            background-color: #eef2f6;
            color: var(--primary-blue);
        }

        .btn-delete {
            background-color: #fef2f2;
            color: #dc3545;
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
            display: flex;
            justify-content: space-between;
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
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .form-group input {
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
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard-admin.html" class="nav-link">Dashboard</a>
            <a href="pengumuman-admin.html" class="nav-link">Pengumuman</a>
            <a href="jadwal-admin-up.html" class="nav-link">Jadwal Ibadah</a>
            <a href="data-jemaat-admin.html" class="nav-link">Data Jemaat</a>
            <a href="cabang-admin.html" class="nav-link active">Cabang Gereja</a>
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
                    <h2>Manajemen Cabang Gereja</h2>
                    <p>Kelola data alamat dan gembala penanggung jawab tiap cabang</p>
                </div>
                <button class="btn-add" onclick="document.getElementById('modalCabang').style.display='flex'">+ Tambah
                    Cabang</button>
            </div>

            <div class="branch-grid">
                <div class="branch-card">
                    <h3>GBI Maranatha Pusat</h3>
                    <div class="branch-detail">📍 Jl. Prof. Dr. Surya Sumantri No.65, Bandung</div>
                    <div class="branch-detail">👨‍💼 Pdt. Samuel Wibowo</div>
                    <div class="branch-detail">📞 022-2012345</div>
                    <div class="action-btns">
                        <button class="btn-edit"
                            onclick="document.getElementById('modalCabang').style.display='flex'">Edit</button>
                        <button class="btn-delete">Hapus</button>
                    </div>
                </div>

                <div class="branch-card">
                    <h3>GBI Maranatha Dago</h3>
                    <div class="branch-detail">📍 Jl. Ir. H. Juanda No.100, Bandung</div>
                    <div class="branch-detail">👨‍💼 Pdt. Andreas Siregar</div>
                    <div class="branch-detail">📞 022-2509876</div>
                    <div class="action-btns">
                        <button class="btn-edit">Edit</button>
                        <button class="btn-delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalCabang" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Data Cabang</h3>
            </div>
            <div class="form-group"><label>Nama Cabang</label><input type="text"
                    placeholder="Contoh: GBI Maranatha Cimahi"></div>
            <div class="form-group"><label>Alamat Lengkap</label><input type="text" placeholder="Masukkan alamat...">
            </div>
            <div class="form-group"><label>Nama Gembala / Penanggung Jawab</label><input type="text"
                    placeholder="Masukkan nama gembala..."></div>
            <div class="form-group"><label>Nomor Telepon</label><input type="text" placeholder="Contoh: 022-XXXXXXX">
            </div>
            <div class="modal-actions">
                <button class="btn-cancel"
                    onclick="document.getElementById('modalCabang').style.display='none'">Batal</button>
                <button class="btn-add">Simpan Data</button>
            </div>
        </div>
    </div>
</body>

</html>