<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}
include '../koneksi.php';

$query_jadwal = mysqli_query($conn, "
    SELECT jadwal_ibadah.*, cabang_gereja.nama_cabang 
    FROM jadwal_ibadah 
    LEFT JOIN cabang_gereja ON jadwal_ibadah.id_cabang = cabang_gereja.id_cabang 
    ORDER BY jadwal_ibadah.waktu_pelaksanaan DESC
");
?>

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

        .schedule-list.past {
            border-left: 5px solid #166534;
        }

        .schedule-list.upcoming {
            border-left: 5px solid #f59e0b;
        }

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

        .status-past {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-upcoming {
            background-color: #fef3c7;
            color: #b45309;
        }

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

        .btn-edit {
            background-color: #eef2f6;
            color: var(--primary-blue);
        }

        .btn-delete {
            background-color: #fef2f2;
            color: #dc3545;
        }

        .btn-report {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #166534;
        }

        .btn-report:hover {
            background-color: #bbf7d0;
        }

        .btn-report.view-mode {
            background-color: var(--primary-blue);
            color: white;
            border: none;
        }

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

        .pelayan-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .pelayan-row input {
            flex: 2;
            padding-left: 10px;
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

        .btn-add-row:hover {
            background: #dbeafe;
        }

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
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin.php" class="nav-link active">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
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
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?> (Admin)</div>▼
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

            <?php
            if (mysqli_num_rows($query_jadwal) == 0) {
                echo "<p style='text-align:center; color:#666;'>Belum ada jadwal ibadah yang dibuat.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($query_jadwal)) :

                    // --- LOGIKA WAKTU PHP ---
                    $waktu_db = strtotime($row['waktu_pelaksanaan']);
                    $waktu_sekarang = time();
                    $is_past = ($waktu_db < $waktu_sekarang); // Ngecek apakah udah lewat

                    // Tentukan class border dan badge berdasarkan waktu
                    $border_class = $is_past ? 'past' : 'upcoming';
                    $badge_class = $is_past ? 'status-past' : 'status-upcoming';
                    $badge_text = $is_past ? 'Selesai' : 'Mendatang';

                    // CEK LAPORAN: Di sini kita cek apakah di tabel 'pendataan' sudah ada id_jadwal ini?
                    // (Sementara gw set dummy logic 'false' dulu sampai fitur Create Laporan kita bikin)
                    $ada_laporan = false;
            ?>

                    <div class="schedule-list <?= $border_class; ?>">
                        <div class="schedule-info">
                            <h4><?= $row['kategori_ibadah']; ?>
                                (<?= date('l, d M Y, H:i', strtotime($row['waktu_pelaksanaan'])); ?> WIB)
                            </h4>
                            <p>
                                📍 Cabang: <strong><?= $row['nama_cabang']; ?></strong> <span class="status-badge <?= $badge_class; ?>"><?= $badge_text; ?></span>

                                <?php if ($is_past): ?>
                                    <?php if ($ada_laporan): ?>
                                        <span class="badge-laporan" style="color:#166534; border-color:#166534; background:#dcfce7;">Laporan Terkirim</span>
                                    <?php else: ?>
                                        <span class="badge-laporan" style="color:#dc3545; border-color:#dc3545; background:#fef2f2;">Laporan Belum Ada</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="action-btns">
                            <?php if ($is_past): ?>
                                <?php if ($ada_laporan): ?>
                                    <button class="btn-report view-mode" onclick="bukaModal('modalLaporan')">📄 Lihat Laporan</button>
                                <?php else: ?>
                                    <button class="btn-report" onclick="bukaModal('modalLaporan')">📝 Buat Laporan</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn-report" disabled>⏳ Laporan Belum Dibuka</button>
                            <?php endif; ?>

                            <button class="btn-edit" onclick="bukaModal('modalJadwal')">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </div>
                    </div>

            <?php
                endwhile;
            }
            ?>
        </div>
    </div>

    <div id="modalJadwal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Jadwal Ibadah</h3>
            </div>
            <p>Fitur form menyusul di step berikutnya...</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="tutupModal('modalJadwal')">Batal</button>
            </div>
        </div>
    </div>

    <div id="modalLaporan" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Laporan Ibadah</h3>
            </div>
            <p>Fitur form menyusul di step berikutnya...</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="tutupModal('modalLaporan')">Batal</button>
            </div>
        </div>
    </div>
    <script>
        function bukaModal(idModal) {
            document.getElementById(idModal).style.display = 'flex';
        }

        function tutupModal(idModal) {
            document.getElementById(idModal).style.display = 'none';
        }

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

        function hapusBaris(btn) {
            const container = document.getElementById('container-pelayan');
            if (container.children.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("Minimal harus ada satu baris input pelayan.");
            }
        }

        function bukaModal(idModal) {
            document.getElementById(idModal).style.display = 'flex';
        }

        function tutupModal(idModal) {
            document.getElementById(idModal).style.display = 'none';
        }
    </script>
</body>

</html>