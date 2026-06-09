<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'gembala_cabang')) {
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
$is_admin = ($_SESSION['role'] == 'admin');

$data_edit = null;
if ($is_admin && isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query_edit = mysqli_query($conn, "SELECT * FROM jadwal_ibadah WHERE id_jadwal = '$edit_id'");
    $data_edit = mysqli_fetch_assoc($query_edit);
}
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
            align-items: center;
        }

        .action-btns button,
        .action-btns a {
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
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
                    <div class="nav-user-name">
                        <?= $_SESSION['nama_lengkap']; ?> (<?= $is_admin ? 'Admin' : 'Gembala'; ?>)
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Kelola Jadwal & Laporan Ibadah</h2>
                    <p>Atur jadwal ibadah serta kelola laporan kehadiran untuk setiap sesinya</p>
                </div>
                <?php if ($is_admin): ?>
                    <button class="btn-add" onclick="bukaModal('modalJadwal')">+ Tambah Jadwal</button>
                <?php endif; ?>
<!-- Masih SAlah -->
                <!-- <?php if ($is_admin): ?>
                    <a href="jadwal_admin_up.php?edit_id=<?= $row['id_jadwal']; ?>" class="btn-edit">Edit</a>
                    <a href="hapus_jadwal.php?id=<?= $row['id_jadwal']; ?>" class="btn-delete">Hapus</a>
                <?php endif; ?> -->
            </div>
            <datalist id="list-jemaat">
                <?php
                $q_jemaat = mysqli_query($conn, "SELECT nama_lengkap FROM jemaat");
                while ($jem = mysqli_fetch_assoc($q_jemaat)) {
                    echo "<option value='" . $jem['nama_lengkap'] . "'>";
                }
                ?>
            </datalist>

            <?php
            if (mysqli_num_rows($query_jadwal) == 0) {
                echo "<p style='text-align:center; color:#666;'>Belum ada jadwal ibadah yang dibuat.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($query_jadwal)) :

                    $waktu_db = strtotime($row['waktu_pelaksanaan']);
                    $waktu_sekarang = time();
                    $is_past = ($waktu_db < $waktu_sekarang);

                    $border_class = $is_past ? 'past' : 'upcoming';
                    $badge_class = $is_past ? 'status-past' : 'status-upcoming';
                    $badge_text = $is_past ? 'Selesai' : 'Mendatang';

                    $cek_laporan = mysqli_query($conn, "SELECT * FROM pendataan WHERE id_jadwal = '{$row['id_jadwal']}'");
                    $ada_laporan = (mysqli_num_rows($cek_laporan) > 0);
                    $data_laporan = mysqli_fetch_assoc($cek_laporan);
            ?>
                    <div class="schedule-list <?= $border_class; ?>">
                        <div class="schedule-info">
                            <h4><?= $row['kategori_ibadah']; ?> (<?= date('l, d M Y, H:i', strtotime($row['waktu_pelaksanaan'])); ?> WIB)</h4>
                            <p>📍 Cabang: <strong><?= $row['nama_cabang']; ?></strong> <span class="status-badge <?= $badge_class; ?>"><?= $badge_text; ?></span></p>
                        </div>

                        <div class="action-btns">
                            <?php if ($is_past): ?>
                                <?php if ($ada_laporan): ?>
                                    <button class="btn-report view-mode" onclick="bukaModal('modalDetail_<?= $row['id_jadwal']; ?>')">📄 Lihat Laporan</button>
                                <?php else: ?>
                                    <button class="btn-report" onclick="bukaModalLaporan('modalLaporan', <?= $row['id_jadwal']; ?>)">📝 Buat Laporan</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn-report" disabled>⏳ Laporan Belum Dibuka</button>
                            <?php endif; ?>

                            <a href="jadwal_admin_up.php?edit_id=<?= $row['id_jadwal']; ?>" class="btn-edit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Edit</a>
                            <a href="hapus_jadwal.php?id=<?= $row['id_jadwal']; ?>" class="btn-delete" onclick="return confirm('Yakin mau hapus jadwal ini?');" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Hapus</a>
                        </div>
                    </div>

                    <!-- Modal Detail Laporan -->
                    <?php if ($ada_laporan):
                        $q_pelayan = mysqli_query($conn, "SELECT * FROM penugasan_pelayan WHERE id_jadwal = '{$row['id_jadwal']}'");
                    ?>
                        <div id="modalDetail_<?= $row['id_jadwal']; ?>" class="modal-overlay">
                            <div class="modal-content" style="max-width: 550px;">
                                <div class="modal-header">
                                    <h3>Detail Laporan Ibadah</h3>
                                </div>

                                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                                        <span style="color: #64748b;">Kehadiran:</span>
                                        <strong><?= $data_laporan['jumlah_kehadiran']; ?> Jiwa</strong>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                                        <span style="color: #64748b;">Persembahan:</span>
                                        <strong style="color: #16a34a;">Rp <?= number_format($data_laporan['total_persembahan'], 0, ',', '.'); ?></strong>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                                        <span style="color: #64748b;">Perpuluhan:</span>
                                        <strong style="color: #16a34a;">Rp <?= number_format($data_laporan['total_perpuluhan'], 0, ',', '.'); ?></strong>
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="color: #64748b; margin-bottom: 5px;">Catatan:</span>
                                        <div style="background: white; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; color: #334155; font-size: 14px;">
                                            <?= !empty($data_laporan['catatan']) ? nl2br($data_laporan['catatan']) : '<i>Tidak ada catatan</i>'; ?>
                                        </div>
                                    </div>
                                </div>

                                <h4 style="margin-bottom: 10px; color: var(--primary-blue);">Daftar Pelayan</h4>
                                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
                                    <?php if (mysqli_num_rows($q_pelayan) > 0): ?>
                                        <?php while ($p = mysqli_fetch_assoc($q_pelayan)): ?>
                                            <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f1f5f9;">
                                                <span style="font-size: 14px; font-weight: 500;"><?= $p['nama_pelayan']; ?></span>
                                                <span style="font-size: 11px; background: #eef2f6; color: var(--primary-blue); padding: 2px 8px; border-radius: 12px; font-weight: bold;"><?= $p['peran_pelayanan']; ?></span>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <span style="font-size: 13px; color: #94a3b8;">Belum ada pelayan diinput.</span>
                                    <?php endif; ?>
                                </div>
<!-- Masih SALAH -->
                                <div class="modal-actions">
                                    <?php if ($is_admin): ?>
                                        <a href="hapus_laporan.php?id_jadwal=..." class="btn-delete">🗑 Hapus</a>
                                        <button onclick="bukaModal('modalEditLaporan')">✏️ Edit Laporan</button>
                                        <button class="btn-cancel" onclick="tutupModal(...)">Tutup</button>
                                    <?php else: ?>
                                        <button class="btn-cancel" onclick="tutupModal(...)">Tutup</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit Laporan -->
                        <div id="modalEditLaporan_<?= $row['id_jadwal']; ?>" class="modal-overlay">
                            <div class="modal-content" style="max-width: 600px;">
                                <div class="modal-header">
                                    <h3>Form Edit Laporan Ibadah</h3>
                                </div>

                                <form action="proses_edit_laporan.php" method="POST">
                                    <input type="hidden" name="id_jadwal" value="<?= $row['id_jadwal']; ?>">

                                    <div style="display: flex; gap: 10px;">
                                        <div class="form-group" style="flex: 1;">
                                            <label>Total Kehadiran Jemaat</label>
                                            <input type="number" name="kehadiran" value="<?= $data_laporan['jumlah_kehadiran']; ?>" required>
                                        </div>
                                        <div class="form-group" style="flex: 1;">
                                            <label>Total Persembahan (Rp)</label>
                                            <input type="number" name="persembahan" value="<?= $data_laporan['total_persembahan']; ?>" required>
                                        </div>
                                        <div class="form-group" style="flex: 1;">
                                            <label>Total Perpuluhan (Rp)</label>
                                            <input type="number" name="perpuluhan" value="<?= $data_laporan['total_perpuluhan']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan / Evaluasi Ibadah</label>
                                        <textarea name="catatan" rows="3"><?= $data_laporan['catatan']; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Penugasan Pelayan Ibadah</label>
                                        <div id="container-pelayan-edit-<?= $row['id_jadwal']; ?>">
                                            <?php
                                            $q_pelayan_edit = mysqli_query($conn, "SELECT * FROM penugasan_pelayan WHERE id_jadwal = '{$row['id_jadwal']}'");
                                            if (mysqli_num_rows($q_pelayan_edit) > 0):
                                                while ($pe = mysqli_fetch_assoc($q_pelayan_edit)):
                                            ?>
                                                    <div class="pelayan-row">
                                                        <input type="text" name="nama_pelayan[]" list="list-jemaat" value="<?= $pe['nama_pelayan']; ?>" placeholder="Cari nama jemaat..." autocomplete="off">
                                                        <select name="peran_pelayan[]">
                                                            <option value="">-- Pilih Peran --</option>
                                                            <option value="Worship Leader" <?= $pe['peran_pelayanan'] == 'Worship Leader' ? 'selected' : ''; ?>>Worship Leader (WL)</option>
                                                            <option value="Singer" <?= $pe['peran_pelayanan'] == 'Singer' ? 'selected' : ''; ?>>Singer</option>
                                                            <option value="Pemusik" <?= $pe['peran_pelayanan'] == 'Pemusik' ? 'selected' : ''; ?>>Pemusik</option>
                                                            <option value="Usher" <?= $pe['peran_pelayanan'] == 'Usher' ? 'selected' : ''; ?>>Penyambut Jemaat (Usher)</option>
                                                            <option value="Multimedia" <?= $pe['peran_pelayanan'] == 'Multimedia' ? 'selected' : ''; ?>>Multimedia</option>
                                                            <option value="Pelayan Firman" <?= $pe['peran_pelayanan'] == 'Pelayan Firman' ? 'selected' : ''; ?>>Pelayan Firman</option>
                                                        </select>
                                                        <button type="button" class="btn-remove-row" onclick="hapusBarisEdit(this, 'container-pelayan-edit-<?= $row['id_jadwal']; ?>')">✖</button>
                                                    </div>
                                                <?php
                                                endwhile;
                                            else:
                                                ?>
                                                <div class="pelayan-row">
                                                    <input type="text" name="nama_pelayan[]" list="list-jemaat" placeholder="Cari nama jemaat..." autocomplete="off">
                                                    <select name="peran_pelayan[]">
                                                        <option value="">-- Pilih Peran --</option>
                                                        <option value="Worship Leader">Worship Leader (WL)</option>
                                                        <option value="Singer">Singer</option>
                                                        <option value="Pemusik">Pemusik</option>
                                                        <option value="Usher">Penyambut Jemaat (Usher)</option>
                                                        <option value="Multimedia">Multimedia</option>
                                                        <option value="Pelayan Firman">Pelayan Firman</option>
                                                    </select>
                                                    <button type="button" class="btn-remove-row" onclick="hapusBarisEdit(this, 'container-pelayan-edit-<?= $row['id_jadwal']; ?>')">✖</button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn-add-row" onclick="tambahBarisEdit('container-pelayan-edit-<?= $row['id_jadwal']; ?>')">+ Tambah Pelayan Lainnya</button>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="btn-cancel" onclick="tutupModal('modalEditLaporan_<?= $row['id_jadwal']; ?>')">Batal</button>
                                        <button type="submit" class="btn-add" style="background-color: #f59e0b; color: white;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
            <?php
                endwhile;
            }
            ?>
        </div>
    </div>

    <!-- Tambah jadwal ibadah -->
    <div id="modalJadwal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Form Jadwal Ibadah</h3>
            </div>

            <form action="proses_tambah_jadwal.php" method="POST">
                <div class="form-group">
                    <label>Nama Sesi Ibadah</label>
                    <input type="text" name="kategori_ibadah" placeholder="Contoh: Ibadah Raya 1" required>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Waktu Mulai</label>
                        <input type="time" name="waktu" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi Cabang</label>
                    <select name="id_cabang" required>
                        <option value="">-- Pilih Cabang --</option>
                        <?php
                        $q_cabang = mysqli_query($conn, "SELECT * FROM cabang_gereja");
                        while ($cab = mysqli_fetch_assoc($q_cabang)) {
                            echo "<option value='" . $cab['id_cabang'] . "'>" . $cab['nama_cabang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="tutupModal('modalJadwal')">Batal</button>
                    <button type="submit" class="btn-add">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tambah laporan ibadah -->
    <div id="modalLaporan" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Form Laporan Ibadah</h3>
            </div>

            <form action="proses_tambah_laporan.php" method="POST">

                <input type="hidden" name="id_jadwal" id="laporan_id_jadwal" value="">

                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Total Kehadiran Jemaat</label>
                        <input type="number" name="kehadiran" placeholder="Contoh: 150" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Total Persembahan (Rp)</label>
                        <input type="number" name="persembahan" placeholder="Contoh: 500000" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Total Perpuluhan (Rp)</label>
                        <input type="number" name="perpuluhan" placeholder="Contoh: 5000000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan / Evaluasi Ibadah</label>
                    <textarea name="catatan" rows="3" placeholder="Masukkan ringkasan kesaksian atau kendala selama ibadah..."></textarea>
                </div>

                <div class="form-group">
                    <label>Penugasan Pelayan Ibadah</label>
                    <div id="container-pelayan-laporan">
                        <div class="pelayan-row">
                            <input type="text" name="nama_pelayan[]" list="list-jemaat" placeholder="Cari nama jemaat..." autocomplete="off">
                            <select name="peran_pelayan[]">
                                <option value="">-- Pilih Peran --</option>
                                <option value="Worship Leader">Worship Leader (WL)</option>
                                <option value="Singer">Singer</option>
                                <option value="Pemusik">Pemusik</option>
                                <option value="Usher">Penyambut Jemaat (Usher)</option>
                                <option value="Multimedia">Multimedia</option>
                                <option value="Pelayan Firman">Pelayan Firman</option>
                            </select>
                            <button type="button" class="btn-remove-row" onclick="hapusBarisLaporan(this)">✖</button>
                        </div>
                    </div>
                    <button type="button" class="btn-add-row" onclick="tambahBarisLaporan()">+ Tambah Pelayan Lainnya</button>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="tutupModal('modalLaporan')">Batal</button>
                    <button type="submit" class="btn-add">Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($data_edit)):
        $tgl_edit = date('Y-m-d', strtotime($data_edit['waktu_pelaksanaan']));
        $jam_edit = date('H:i', strtotime($data_edit['waktu_pelaksanaan']));
    ?>
        <div id="modalEditJadwal" class="modal-overlay" style="display: flex;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Edit Jadwal Ibadah</h3>
                </div>

                <form action="proses_edit_jadwal.php" method="POST">
                    <input type="hidden" name="id_jadwal" value="<?= $data_edit['id_jadwal']; ?>">

                    <div class="form-group">
                        <label>Nama Sesi Ibadah</label>
                        <input type="text" name="kategori_ibadah" value="<?= $data_edit['kategori_ibadah']; ?>" required>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal" value="<?= $tgl_edit; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Waktu Mulai</label>
                            <input type="time" name="waktu" value="<?= $jam_edit; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Cabang</label>
                        <select name="id_cabang" required>
                            <?php
                            $q_cabang = mysqli_query($conn, "SELECT * FROM cabang_gereja");
                            while ($cab = mysqli_fetch_assoc($q_cabang)) {
                                $selected = ($cab['id_cabang'] == $data_edit['id_cabang']) ? 'selected' : '';
                                echo "<option value='" . $cab['id_cabang'] . "' $selected>" . $cab['nama_cabang'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="modal-actions">
                        <a href="jadwal_admin_up.php" class="btn-cancel" onclick="tutupModal('modalJadwal')" style="text-decoration: none;">Batal</a>
                        <button type="submit" class="btn-add">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
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
                <input type="text" name="nama_pelayan[]" list="list-jemaat" placeholder="Ketik nama jemaat..." autocomplete="off">
                <select name="peran_pelayan[]">
                    <option value="">-- Pilih Peran --</option>
                    <option value="Worship Leader">Worship Leader (WL)</option>
                    <option value="Singer">Singer</option>
                    <option value="Pemusik">Pemusik</option>
                    <option value="Usher">Penyambut Jemaat (Usher)</option>
                    <option value="Multimedia">Multimedia</option>
                    <option value="Pelayan Firman">Pelayan Firman</option>
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

        function tambahBarisLaporan() {
            const container = document.getElementById('container-pelayan-laporan');
            const rowBaru = document.createElement('div');
            rowBaru.className = 'pelayan-row';
            rowBaru.innerHTML = `
                <input type="text" name="nama_pelayan[]" list="list-jemaat" placeholder="Ketik nama jemaat..." autocomplete="off">
                <select name="peran_pelayan[]">
                    <option value="">-- Pilih Peran --</option>
                    <option value="Worship Leader">Worship Leader (WL)</option>
                    <option value="Singer">Singer</option>
                    <option value="Pemusik">Pemusik</option>
                    <option value="Usher">Penyambut Jemaat (Usher)</option>
                    <option value="Multimedia">Multimedia</option>
                    <option value="Pelayan Firman">Pelayan Firman</option>
                </select>
                <button type="button" class="btn-remove-row" onclick="hapusBarisLaporan(this)">✖</button>
            `;
            container.appendChild(rowBaru);
        }

        function hapusBarisLaporan(btn) {
            const container = document.getElementById('container-pelayan-laporan');
            if (container.children.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("Minimal harus ada satu baris input pelayan.");
            }
        }

        function bukaModalLaporan(idModal, idJadwal) {
            document.getElementById('laporan_id_jadwal').value = idJadwal;
            document.getElementById(idModal).style.display = 'flex';
        }

        function tambahBarisEdit(containerId) {
            const container = document.getElementById(containerId);
            const rowBaru = document.createElement('div');
            rowBaru.className = 'pelayan-row';
            rowBaru.innerHTML = `
                <input type="text" name="nama_pelayan[]" list="list-jemaat" placeholder="Ketik nama jemaat..." autocomplete="off">
                <select name="peran_pelayan[]">
                    <option value="">-- Pilih Peran --</option>
                    <option value="Worship Leader">Worship Leader (WL)</option>
                    <option value="Singer">Singer</option>
                    <option value="Pemusik">Pemusik</option>
                    <option value="Usher">Penyambut Jemaat (Usher)</option>
                    <option value="Multimedia">Multimedia</option>
                    <option value="Pelayan Firman">Pelayan Firman</option>
                </select>
                <button type="button" class="btn-remove-row" onclick="hapusBarisEdit(this, '${containerId}')">✖</button>
            `;
            container.appendChild(rowBaru);
        }

        function hapusBarisEdit(btn, containerId) {
            const container = document.getElementById(containerId);
            if (container.children.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("Minimal harus ada satu baris input pelayan.");
            }
        }
    </script>
</body>

</html>