<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* CSS INTERNAL ADMIN */
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

        .list-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-info h4 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 18px;
        }

        .list-info p {
            color: var(--text-gray);
            font-size: 14px;
        }

        .badge-kategori {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            margin-right: 10px;
            background-color: #17a2b8;
        }

        .action-btns button {
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 5px;
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
            width: 600px;
            border-radius: 12px;
            padding: 30px;
            max-height: 90vh;
            overflow-y: auto;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
        }

        /* Styling Button Upload Custom */
        .btn-upload {
            background: #eef2f6;
            color: var(--primary-blue);
            border: 1px dashed var(--primary-blue);
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: #dbeafe;
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
            <a href="pengumuman-admin.html" class="nav-link active">Pengumuman</a>
            <a href="jadwal-admin-up.html" class="nav-link">Jadwal Ibadah</a>
            <a href="data-jemaat-admin.html" class="nav-link">Data Jemaat</a>
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
                    <h2>Kelola Pengumuman</h2>
                    <p>Pusat kontrol informasi untuk seluruh cabang</p>
                </div>
                <button class="btn-add" onclick="document.getElementById('modalTambah').style.display='flex'">+ Buat
                    Pengumuman Baru</button>
            </div>

            <div class="list-card">
                <div class="list-info">
                    <h4>Ibadah Natal Bersama 2026</h4>
                    <p><span class="badge-kategori">Penting</span> Dipublikasikan: 28 April 2026 • Target: Semua Cabang
                    </p>
                </div>
                <div class="action-btns">
                    <button class="btn-edit"
                        onclick="document.getElementById('modalTambah').style.display='flex'">Edit</button>
                    <button class="btn-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Pengumuman</h3>
            </div>
            <div class="form-group"><label>Judul Pengumuman</label><input type="text" placeholder="Masukkan judul...">
            </div>
            
            <!-- Tambahan Field Dilaporkan/Dibuat Oleh -->
            <div class="form-group">
                <label>Dibuat Oleh</label>
                <input type="text" placeholder="Contoh: Pdt. Samuel / Divisi Pemuda" value="Halan Walker">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;"><label>Kategori</label><select>
                        <option>Penting</option>
                        <option>Kegiatan</option>
                        <option>Ibadah</option>
                    </select></div>
                <div class="form-group" style="flex: 1;"><label>Target Cabang</label><select>
                        <option>Semua Cabang (Global)</option>
                        <option>GBI Maranatha Dago</option>
                    </select></div>
            </div>
            
            <div class="form-group"><label>Isi Pengumuman</label><textarea rows="4" placeholder="Tulis rincian pengumuman..."></textarea></div>
            
            <div class="form-group">
                <label>Gambar Pendukung (Opsional)</label>
                <button type="button" class="btn-upload" onclick="document.getElementById('uploadGambar').click()">
                    📷 Pilih Gambar untuk Diunggah
                </button>
                <input type="file" id="uploadGambar" accept="image/*" style="display: none;">
            </div>

            <div class="modal-actions">
                <button class="btn-cancel"
                    onclick="document.getElementById('modalTambah').style.display='none'">Batal</button>
                <button class="btn-add">Publikasikan</button>
            </div>
        </div>
    </div>
</body>

</html>