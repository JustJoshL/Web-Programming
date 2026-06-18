<?php
session_start();

/** @var mysqli $conn */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

mysqli_query($conn, "
    UPDATE pengumuman
    SET status_publikasi = 'Published'
    WHERE status_publikasi = 'Draft'
    AND tanggal_publikasi <= CURDATE()
");

$query_pengumuman = mysqli_query($conn, "
    SELECT p.*, c.nama_cabang 
    FROM pengumuman p
    LEFT JOIN cabang_gereja c ON p.id_cabang = c.id_cabang
    ORDER BY p.tanggal_publikasi DESC
");

$data_edit = null;
if (isset($_GET['edit_id'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
    $query_edit = mysqli_query($conn, "SELECT * FROM pengumuman WHERE id_pengumuman = '$edit_id'");
    $data_edit = mysqli_fetch_assoc($query_edit);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 15px;
        }

        .sidebar-logo img {
            width: 55px;
            height: auto;
            flex-shrink: 0;
        }

        .logo-text-wrapper {
            font-family: Georgia, serif;
            font-size: 24px;
            font-weight: bold;
            color: #ffc107;
        }

        .logo-text-wrapper span {
            margin-top: 6px;
            font-family: Arial, sans-serif;
            font-size: 8px;
            font-weight: 600;
            color: white;
            letter-spacing: 2.1px;
            text-transform: uppercase;
            margin-left: 6px;
        }

        .header-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .page-title h2 {
            color: var(--primary-blue);
            font-size: 28px;
            margin-bottom: 5px;
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
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border-left: 5px solid var(--primary-blue);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 15px;
        }

        .list-info h4 {
            color: var(--primary-blue);
            margin-bottom: 8px;
            font-size: 20px;
        }

        .list-info p {
            color: var(--text-gray);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .badge-kategori {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            margin-right: 10px;
            background-color: #17a2b8;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 10px;
        }

        .status-Published {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-Draft {
            background-color: #fef3c7;
            color: #b45309;
        }

        .pengumuman-body {
            font-size: 15px;
            color: var(--text-dark);
            line-height: 1.6;
            white-space: pre-line;
        }

        .pengumuman-img {
            margin-top: 15px;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background: #fef3c7;
            color: #b45309;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

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
            width: 450px;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            margin-bottom: 15px;
            color: var(--primary-blue);
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: bold;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: inherit;
            font-size: 13px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .btn-upload {
            background: #f8fafc;
            color: var(--primary-blue);
            border: 2px dashed #cbd5e1;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            transition: all 0.2s ease;
            font-size: 13px;
        }

        .btn-upload:hover {
            background: #f1f5f9;
            border-color: var(--primary-blue);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-cancel {
            background: black;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
        }

        .btn-draft {
            background: #e2e8f0;
            color: #475569;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .btn-draft:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../uploads/churchsync-logo.png" alt="Logo ChurchSync">
            <div class="logo-text-wrapper">
                ChurchSync
                <span>
                    ALL ABOUT OUR CHURCH
                </span>
            </div>
        </div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link active">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>

                <div class="user-profile-dropdown" onclick="toggleDropdown(event)">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?> (Admin) ▼</div>
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
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
                <button class="btn-add" onclick="document.getElementById('modalTambah').style.display='flex'">+ Buat Pengumuman Baru</button>
            </div>

            <?php
            if (mysqli_num_rows($query_pengumuman) == 0) {
                echo "<p style='text-align:center; color: var(--text-gray); padding: 40px; background: white; border-radius: 12px;'>Belum ada data pengumuman.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($query_pengumuman)) :
            ?>
                    <div class="list-card">
                        <div class="list-header">
                            <div class="list-info">
                                <h4><?= htmlspecialchars($row['judul_pengumuman']); ?></h4>
                                <p>
                                    <span class="badge-kategori"><?= htmlspecialchars($row['kategori_pengumuman']); ?></span>

                                    <?php if ($row['target_tipe'] == 'umum') : ?>
                                        <span class="badge-status" style="background:#e0e7ff; color:#3730a3;">UMUM (SEMUA CABANG)</span>
                                    <?php else : ?>
                                        <span class="badge-status" style="background:#fae8ff; color:#86198f;">CABANG (<?= htmlspecialchars($row['nama_cabang'] ?? 'Tidak Diketahui'); ?>)</span>
                                    <?php endif; ?>

                                    <span class="badge-status <?= $row['status_publikasi'] == 'Published' ? 'status-Published' : 'status-Draft'; ?>">

                                        <?php
                                        if (
                                            $row['status_publikasi'] == 'Draft' &&
                                            $row['tanggal_publikasi'] > date('Y-m-d')
                                        ) {
                                            echo 'TERJADWAL';
                                        } else {
                                            echo strtoupper($row['status_publikasi']);
                                        }
                                        ?>

                                    </span>

                                    • Dipublikasikan: <?= date('d M Y', strtotime($row['tanggal_publikasi'])); ?>
                                </p>
                            </div>
                            <div class="action-btns">
                                <a href="pengumuman_admin.php?edit_id=<?= $row['id_pengumuman']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="hapus_pengumuman.php?id=<?= $row['id_pengumuman']; ?>" class="btn-delete" onclick="return confirm('Yakin mau hapus pengumuman ini secara permanen?');">🗑️ Hapus</a>
                            </div>
                        </div>

                        <div class="pengumuman-body">
                            <?= htmlspecialchars($row['isi_pengumuman']); ?>

                            <?php if (!empty($row['gambar_pendukung'])) { ?>
                                <br>
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_pendukung']); ?>" alt="Gambar Pengumuman" class="pengumuman-img">
                            <?php } ?>
                        </div>
                    </div>
            <?php
                endwhile;
            }
            ?>
        </div>
    </div>

    <div id="modalTambah" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Pengumuman Baru</h3>
            </div>

            <form action="proses_tambah_pengumuman.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input type="text" name="judul_pengumuman" placeholder="Masukkan judul..." required>
                </div>

                <div class="form-group">
                    <label>Target Pengumuman</label>
                    <select name="target_tipe" id="targetTipe" onchange="toggleCabangPengumuman()" required>
                        <option value="umum">Untuk Semua Cabang</option>
                        <option value="cabang">Cabang Tertentu</option>
                    </select>
                </div>

                <div id="pilihanCabang" class="form-group" style="display:none;">
                    <label>Pilih Cabang Target</label>
                    <select name="id_cabang">
                        <?php
                        $qcabang = mysqli_query($conn, "SELECT * FROM cabang_gereja ORDER BY nama_cabang");
                        while ($cabang = mysqli_fetch_assoc($qcabang)):
                        ?>
                            <option value="<?= $cabang['id_cabang']; ?>"><?= $cabang['nama_cabang']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_pengumuman" required>
                        <option value="Penting">Penting</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Ibadah">Ibadah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Publikasi</label>
                    <input type="date" name="tanggal_publikasi" required>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi_pengumuman" rows="4" placeholder="Tulis rincian pengumuman..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Pendukung (Opsional)</label>
                    <input type="file" name="gambar_pendukung" id="uploadGambar" accept="image/*" style="display: none;" onchange="updateFileName('uploadGambar', 'btnUploadText')">
                    <div class="btn-upload" onclick="document.getElementById('uploadGambar').click()">
                        📷 <span id="btnUploadText">Pilih Gambar...</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalTambah').style.display='none'">Batal</button>
                    <button type="submit" name="status_publikasi" value="Draft" class="btn-draft">Draft</button>
                    <button type="submit" name="status_publikasi" value="Published" class="btn-add">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal-overlay" style="display: <?= isset($_GET['edit_id']) ? 'flex' : 'none'; ?>;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Edit Pengumuman</h3>
            </div>

            <form action="proses_edit_pengumuman.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_pengumuman" value="<?= $data_edit['id_pengumuman'] ?? ''; ?>">
                <input type="hidden" name="gambar_lama" value="<?= $data_edit['gambar_pendukung'] ?? ''; ?>">

                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input type="text" name="judul_pengumuman" value="<?= $data_edit['judul_pengumuman'] ?? ''; ?>" placeholder="Masukkan judul..." required>
                </div>

                <div id="pilihanCabangEdit" class="form-group" style="display: <?= (isset($data_edit) && $data_edit['target_tipe'] == 'cabang') ? 'block' : 'none'; ?>;">
                    <label>Pilih Cabang Target</label>
                    <select name="id_cabang">
                        <?php
                        $qcabang_edit = mysqli_query($conn, "SELECT * FROM cabang_gereja ORDER BY nama_cabang");
                        while ($cabang_edit = mysqli_fetch_assoc($qcabang_edit)):
                        ?>
                            <option value="<?= $cabang_edit['id_cabang']; ?>" <?= (isset($data_edit) && $data_edit['id_cabang'] == $cabang_edit['id_cabang']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cabang_edit['nama_cabang']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_pengumuman" required>
                        <option value="Penting" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Penting') ? 'selected' : ''; ?>>Penting</option>
                        <option value="Kegiatan" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                        <option value="Ibadah" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Ibadah') ? 'selected' : ''; ?>>Ibadah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Publikasi</label>
                    <input type="date" name="tanggal_publikasi" value="<?= $data_edit['tanggal_publikasi'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi_pengumuman" rows="4" placeholder="Tulis rincian pengumuman..." required><?= $data_edit['isi_pengumuman'] ?? ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar_baru" id="uploadGambarEdit" accept="image/*" style="display: none;" onchange="updateFileName('uploadGambarEdit', 'btnUploadTextEdit')">
                    <div class="btn-upload" onclick="document.getElementById('uploadGambarEdit').click()">
                        📷 <span id="btnUploadTextEdit"><?= (!empty($data_edit['gambar_pendukung'])) ? "Ganti: " . $data_edit['gambar_pendukung'] : "Pilih Gambar Baru..."; ?></span>
                    </div>
                </div>

                <div class="modal-actions">
                    <a href="pengumuman_admin.php" class="btn-cancel" style="text-decoration: none; text-align: center; line-height: 20px;">Batal</a>
                    <button type="submit" name="status_publikasi" value="Draft" class="btn-draft">Simpan Draft</button>
                    <button type="submit" name="status_publikasi" value="Published" class="btn-add">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(inputId, textId) {
            let input = document.getElementById(inputId);
            let text = document.getElementById(textId);
            if (input.files.length > 0) {
                text.innerText = "File: " + input.files[0].name;
                text.style.color = "#16a34a"; // Warna hijau tanda sukses
            }
        }

        function toggleCabangPengumuman() {
            let tipe = document.getElementById('targetTipe').value;
            let cabang = document.getElementById('pilihanCabang');
            if (tipe === 'cabang') {
                cabang.style.display = 'block';
            } else {
                cabang.style.display = 'none';
            }
        }

        function toggleDropdown(event) {
            let profil = document.getElementById("profileDropdown");
            if (profil) profil.classList.toggle("show");

            let notif = document.getElementById("notifDropdown");
            if (notif) notif.classList.remove("show");

            event.stopPropagation();
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-profile-dropdown')) {
                let dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
            if (!event.target.closest('.noti-container')) {
                let notifs = document.getElementsByClassName("noti-dropdown-content");
                for (let i = 0; i < notifs.length; i++) {
                    notifs[i].classList.remove('show');
                }
            }
        }
    </script>
</body>

</html>