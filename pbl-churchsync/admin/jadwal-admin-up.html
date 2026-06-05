<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ibadah - Admin ChurchSync</title>
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

        .schedule-list {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Penanda Visual Untuk Jadwal Selesai & Mendatang */
        .schedule-list.past { border-left: 5px solid #166534; }
        .schedule-list.upcoming { border-left: 5px solid #f59e0b; }

        .schedule-info h4 {
            color: var(--text-dark);
            margin-bottom: 5px;
            font-size: 18px;
        }

        .schedule-info p {
            color: var(--text-gray);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-past { background-color: #dcfce7; color: #166534; }
        .status-upcoming { background-color: #fef3c7; color: #b45309; }
        
        /* Status Laporan */
        .badge-laporan {
            background-color: #eef2f6;
            color: var(--primary-blue);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid var(--primary-blue);
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .action-btns button {
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-edit { background-color: #eef2f6; color: var(--primary-blue); }
        .btn-delete { background-color: #fef2f2; color: #dc3545; }
        
        /* Styling Tombol Laporan */
        .btn-report { background-color: #dcfce7; color: #166534; border: 1px solid #166534; }
        .btn-report:hover { background-color: #bbf7d0; }
        .btn-report.view-mode { background-color: var(--primary-blue); color: white; border: none; }
        .btn-report:disabled { 
            background-color: #f1f5f9; 
            color: #94a3b8; 
            border: 1px solid #cbd5e1;
            cursor: not-allowed; 
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
            width: 550px;
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
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
        }

        /* Styling Dinamis Pelayan Ibadah */
        .pelayan-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        
        .pelayan-row input {
            flex: 2;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>');
            background-repeat: no-repeat;
            background-position: 10px center;
            padding-left: 35px !important;
        }

        .pelayan-row select {
            flex: 1.5;
        }

        .btn-remove-row {
            background: #fef2f2;
            color: #dc3545;
            border: 1px solid #f87171;
            border-radius: 6px;
            width: 35px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-add-row {
            background: #eef2f6;
            color: var(--primary-blue);
            border: 1px dashed var(--primary-blue);
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            text-align: center;
            margin-top: 5px;
        }
        .btn-add-row:hover { background: #dbeafe; }

        /* Styling Daftar Pelayan di Laporan */
        .pelayan-list-summary {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            max-height: 120px;
            overflow-y: auto;
        }

        .pelayan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .pelayan-item:last-child {
            border-bottom: none;
        }

        .pelayan-name {
            font-size: 13px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .pelayan-role {
            font-size: 11px;
            background: #eef2f6;
            color: var(--primary-blue);
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
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
            <a href="jadwal-admin-up.html" class="nav-link active">Jadwal Ibadah</a>
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
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Kelola Jadwal & Laporan Ibadah</h2>
                    <p>Atur jadwal ibadah serta kelola laporan kehadiran untuk setiap sesinya</p>
                </div>
                <button class="btn-add" onclick="bukaModal('modalJadwal')">+ Tambah Jadwal</button>
            </div>

            <!-- JADWAL SUDAH LEWAT (BELUM DIBUAT LAPORAN) -->
            <div class="schedule-list past">
                <div class="schedule-info">
                    <h4>Ibadah Raya 1 (Minggu, 11 Mei 2026, 07:00 WIB)</h4>
                    <p>
                        📍 GBI Maranatha Pusat 
                        <span class="status-badge status-past">Selesai</span>
                        <span class="badge-laporan" style="color:#dc3545; border-color:#dc3545; background:#fef2f2;">Laporan Belum Ada</span>
                    </p>
                </div>
                <div class="action-btns">
                    <button class="btn-report" onclick="bukaModal('modalLaporan')">📝 Buat Laporan</button>
                    <button class="btn-edit" onclick="bukaModal('modalJadwal')">Edit</button>
                    <button class="btn-delete">Hapus</button>
                </div>
            </div>
            
            <!-- JADWAL SUDAH LEWAT (SUDAH DIBUAT LAPORAN) -->
            <div class="schedule-list past">
                <div class="schedule-info">
                    <h4>Ibadah Doa Malam (Jumat, 9 Mei 2026, 18:00 WIB)</h4>
                    <p>
                        📍 GBI Maranatha Pusat 
                        <span class="status-badge status-past">Selesai</span>
                        <span class="badge-laporan" style="color:#166534; border-color:#166534; background:#dcfce7;">Laporan Terkirim</span>
                    </p>
                </div>
                <div class="action-btns">
                    <button class="btn-report view-mode" onclick="bukaModal('modalLaporan')">📄 Lihat Laporan</button>
                    <button class="btn-edit" onclick="bukaModal('modalJadwal')">Edit</button>
                    <button class="btn-delete">Hapus</button>
                </div>
            </div>

            <!-- JADWAL MENDATANG -->
            <div class="schedule-list upcoming">
                <div class="schedule-info">
                    <h4>Ibadah Youth (Minggu, 18 Mei 2026, 17:00 WIB)</h4>
                    <p>
                        📍 GBI Maranatha Dago 
                        <span class="status-badge status-upcoming">Mendatang</span>
                    </p>
                </div>
                <div class="action-btns">
                    <button class="btn-report" disabled>⏳ Laporan Belum Dibuka</button>
                    <button class="btn-edit" onclick="bukaModal('modalJadwal')">Edit</button>
                    <button class="btn-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: TAMBAH / EDIT JADWAL -->
    <div id="modalJadwal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Jadwal Ibadah</h3>
            </div>
            <div class="form-group"><label>Nama Sesi Ibadah</label><input type="text" placeholder="Contoh: Ibadah Raya 1"></div>
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;"><label>Tanggal & Hari</label><input type="date"></div>
                <div class="form-group" style="flex: 1;"><label>Waktu Mulai</label><input type="time"></div>
            </div>
            <div class="form-group"><label>Lokasi Cabang</label>
                <select>
                    <option>GBI Maranatha Pusat</option>
                    <option>GBI Maranatha Dago</option>
                </select>
            </div>
            
            <!-- SECTION PELAYAN IBADAH -->
            <div class="form-group">
                <label>Penugasan Pelayan Ibadah</label>
                <div id="container-pelayan">
                    <div class="pelayan-row">
                        <input type="text" placeholder="Cari nama jemaat...">
                        <select>
                            <option value="">-- Pilih Peran --</option>
                            <option>Worship Leader (WL)</option>
                            <option>Singer</option>
                            <option>Pemusik</option>
                            <option>Penyambut Jemaat (Usher)</option>
                            <option>Multimedia</option>
                        </select>
                        <button type="button" class="btn-remove-row" onclick="hapusBaris(this)">✖</button>
                    </div>
                </div>
                <button type="button" class="btn-add-row" onclick="tambahBaris()">+ Tambah Pelayan Lainnya</button>
            </div>

            <div class="modal-actions">
                <button class="btn-cancel" onclick="tutupModal('modalJadwal')">Batal</button>
                <button class="btn-add">Simpan Jadwal</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: BUAT / CEK LAPORAN IBADAH -->
    <div id="modalLaporan" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Laporan Ibadah</h3>
            </div>
            
            <!-- Info Data Utama yang ditarik dari Jadwal -->
            <div style="background: #eef2f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; border-left: 4px solid var(--primary-blue);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div><strong>Sesi:</strong> Ibadah Raya 1</div>
                    <div><strong>Tanggal:</strong> Minggu, 11 Mei 2026</div>
                    <div><strong>Waktu:</strong> 07:00 WIB</div>
                    <div><strong>Cabang:</strong> GBI Maranatha Pusat</div>
                </div>
                
                <hr style="border: none; border-top: 1px dashed #cbd5e1; margin: 10px 0;">
                
                <!-- Rincian Nama & Peran Pelayan Terjadwal -->
                <strong>Daftar Pelayan Terjadwal:</strong>
                <div class="pelayan-list-summary">
                    <div class="pelayan-item">
                        <span class="pelayan-name">Budi Santoso</span>
                        <span class="pelayan-role">Worship Leader</span>
                    </div>
                    <div class="pelayan-item">
                        <span class="pelayan-name">Siti Aminah</span>
                        <span class="pelayan-role">Singer</span>
                    </div>
                    <div class="pelayan-item">
                        <span class="pelayan-name">Daniel Setiawan</span>
                        <span class="pelayan-role">Pemusik</span>
                    </div>
                    <div class="pelayan-item">
                        <span class="pelayan-name">Grace Natalia</span>
                        <span class="pelayan-role">Penyambut Jemaat</span>
                    </div>
                </div>
            </div>

            <!-- Form Input Laporan -->
            <div class="form-group"><label>Dilaporkan Oleh</label><input type="text" placeholder="Contoh: Pdt. Samuel Wibowo" value="Halan Walker"></div>
            <div class="form-group"><label>Total Kehadiran Jemaat</label><input type="number" placeholder="Masukkan total jiwa yang hadir"></div>
            
            <!-- SECTION KEUANGAN (Persembahan & Perpuluhan) -->
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;">
                    <label>Total Persembahan (Rp)</label>
                    <input type="number" placeholder="Contoh: 1500000">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Total Perpuluhan (Rp)</label>
                    <input type="number" placeholder="Contoh: 5000000">
                </div>
            </div>
            
            <div class="form-group"><label>Catatan / Ringkasan Evaluasi</label>
                <textarea rows="4" placeholder="Masukkan catatan tambahan ibadah, evaluasi, kesaksian, atau info lainnya..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="tutupModal('modalLaporan')">Batal</button>
                <button class="btn-add">Simpan Data Laporan</button>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Buka Tutup Modal
        function bukaModal(idModal) {
            document.getElementById(idModal).style.display = 'flex';
        }

        function tutupModal(idModal) {
            document.getElementById(idModal).style.display = 'none';
        }

        // Fungsi Tambah Baris Pelayan Secara Dinamis (Di Modal Jadwal)
        function tambahBaris() {
            const container = document.getElementById('container-pelayan');
            const rowBaru = document.createElement('div');
            rowBaru.className = 'pelayan-row';
            rowBaru.innerHTML = `
                <input type="text" placeholder="Cari nama jemaat...">
                <select>
                    <option value="">-- Pilih Peran --</option>
                    <option>Worship Leader (WL)</option>
                    <option>Singer</option>
                    <option>Pemusik</option>
                    <option>Penyambut Jemaat (Usher)</option>
                    <option>Multimedia</option>
                </select>
                <button type="button" class="btn-remove-row" onclick="hapusBaris(this)">✖</button>
            `;
            container.appendChild(rowBaru);
        }

        // Fungsi Hapus Baris Pelayan
        function hapusBaris(btn) {
            const container = document.getElementById('container-pelayan');
            if (container.children.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("Minimal harus ada satu baris input pelayan.");
            }
        }
    </script>
</body>

</html>