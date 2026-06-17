<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'gembala_cabang') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_cabang = $_SESSION['id_cabang'];

$filter = $_GET['filter'] ?? 'semua';
$cari   = $_GET['cari'] ?? '';

// Tarik data pengumuman cabang ini + pengumuman umum (published)
$where = "
(
    (target_tipe='umum' AND status_publikasi='Published')
    OR (id_cabang='$id_cabang')
)
";

if ($filter == 'umum') {
    $where .= " AND target_tipe='umum' AND status_publikasi='Published'";
}

if ($filter == 'cabang') {
    $where .= " AND id_cabang='$id_cabang'";
}

if (!empty($cari)) {
    $where .= "
    AND (
        judul_pengumuman LIKE '%$cari%'
        OR isi_pengumuman LIKE '%$cari%'
    )";
}

$query_pengumuman = mysqli_query($conn, "
    SELECT *
    FROM pengumuman
    WHERE $where
    ORDER BY tanggal_publikasi DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - ChurchSync</title>

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
            margin-bottom: 5px;
        }

        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }

        /* CSS Card Pengumuman */
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

        /* CSS Tombol Aksi */
        .action-buttons {
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
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
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
            background: #fca5a5;
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

        /* CSS Modal Aesthetic */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
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
        }

        .modal-header {
            margin-bottom: 15px;
            color: var(--primary-blue);
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
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
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_gembala.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_gembala.php" class="nav-link active">Pengumuman</a>
            <a href="../admin/jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_gembala.php" class="nav-link">Data Jemaat</a>
            <a href="profil_gembala.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>

                <div class="user-profile-dropdown" onclick="toggleDropdown(event)">
                    <div class="nav-avatar">👨🏽‍💼</div>
                    <div class="nav-user-name">
                        <?= $_SESSION['nama_lengkap']; ?> (Gembala) ▼
                    </div>
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="profil_gembala.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Informasi & Pengumuman</h2>
                    <p>Pusat informasi kegiatan dan ibadah gereja cabang Anda</p>
                </div>
                <button class="btn-add" onclick="document.getElementById('modalTambah').style.display='flex'">
                    + Buat Pengumuman
                </button>
            </div>

            <form id="formFilter" method="GET" style="display:flex; gap:10px; margin-bottom:25px; flex-wrap:wrap; align-items:center;">
                <input type="text" name="cari" placeholder="Cari pengumuman..." value="<?= htmlspecialchars($cari); ?>" style="padding:10px; border:1px solid #ccc; border-radius:6px; min-width:250px;">
                <button type="submit" class="btn-add">Cari</button>
                <select name="filter" onchange="document.getElementById('formFilter').submit()" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="semua" <?= $filter == 'semua' ? 'selected' : ''; ?>>Semua</option>
                    <option value="umum" <?= $filter == 'umum' ? 'selected' : ''; ?>>Pengumuman Pusat (Umum)</option>
                    <option value="cabang" <?= $filter == 'cabang' ? 'selected' : ''; ?>>Cabang Saya</option>
                </select>
            </form>

            <?php
            if (mysqli_num_rows($query_pengumuman) > 0) {
                while ($row = mysqli_fetch_assoc($query_pengumuman)) {
            ?>
                    <div class="list-card">
                        <div class="list-header">
                            <div class="list-info">
                                <h4><?= htmlspecialchars($row['judul_pengumuman']); ?></h4>
                                <p>
                                    <span class="badge-kategori"><?= htmlspecialchars($row['kategori_pengumuman']); ?></span>

                                    <?php if ($row['target_tipe'] == 'umum') : ?>
                                        <span class="badge-status" style="background:#e0e7ff; color:#3730a3;">UMUM (PUSAT)</span>
                                    <?php else : ?>
                                        <span class="badge-status <?= $row['status_publikasi'] == 'Published' ? 'status-Published' : 'status-Draft'; ?>">
                                            CABANG (<?= strtoupper($row['status_publikasi']); ?>)
                                        </span>
                                    <?php endif; ?>

                                    Dipublikasikan pada: <?= date('d F Y', strtotime($row['tanggal_publikasi'])); ?>
                                </p>
                            </div>

                            <?php if ($row['target_tipe'] == 'cabang' && $row['id_cabang'] == $id_cabang) : ?>
                                <div class="action-buttons">
                                    <button class="btn-edit"
                                        onclick="bukaModalEdit(
                                        '<?= $row['id_pengumuman']; ?>',
                                        '<?= htmlspecialchars(addslashes($row['judul_pengumuman'])); ?>',
                                        '<?= $row['kategori_pengumuman']; ?>',
                                        '<?= $row['tanggal_publikasi']; ?>',
                                        '<?= htmlspecialchars(addslashes($row['isi_pengumuman'])); ?>',
                                        '<?= $row['status_publikasi']; ?>'
                                    )">
                                        ✏️ Edit
                                    </button>
                                    <a href="proses_hapus_pengumuman_gembala.php?id=<?= $row['id_pengumuman']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus pengumuman ini secara permanen?')">
                                        🗑️ Hapus
                                    </a>
                                </div>
                            <?php endif; ?>

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
                }
            } else {
                echo "<p style='text-align: center; color: var(--text-gray); padding: 40px; background: white; border-radius: 12px;'>Belum ada informasi atau pengumuman saat ini.</p>";
            }
            ?>
        </div>
    </div>

    <div id="modalTambah" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buat Pengumuman Cabang</h3>
            </div>
            <form action="proses_tambah_pengumuman_gembala.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="target_tipe" value="cabang">

                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input type="text" name="judul_pengumuman" required>
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
                    <label>Tanggal & Status</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="date" name="tanggal_publikasi" required style="flex: 1;">
                        <select name="status_publikasi" required style="flex: 1;">
                            <option value="Published">Published</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi_pengumuman" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar (Opsional)</label>
                    <input type="file" id="uploadGambar" name="gambar_pendukung" style="display:none" onchange="updateFileName('uploadGambar', 'btnUploadText')">
                    <div class="btn-upload" onclick="document.getElementById('uploadGambar').click()">
                        📷 <span id="btnUploadText">Pilih Gambar...</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalTambah').style.display='none'">Batal</button>
                    <button type="submit" class="btn-add">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Pengumuman</h3>
            </div>
            <form action="proses_edit_pengumuman_gembala.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" id="edit_id" name="id_pengumuman">

                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input type="text" id="edit_judul" name="judul_pengumuman" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select id="edit_kategori" name="kategori_pengumuman" required>
                        <option value="Penting">Penting</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Ibadah">Ibadah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal & Status</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="date" id="edit_tanggal" name="tanggal_publikasi" required style="flex: 1;">
                        <select id="edit_status" name="status_publikasi" required style="flex: 1;">
                            <option value="Published">Published</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea id="edit_isi" name="isi_pengumuman" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Ganti Gambar (Opsional)</label>
                    <input type="file" id="uploadGambarEdit" name="gambar_pendukung" style="display:none" onchange="updateFileName('uploadGambarEdit', 'btnUploadTextEdit')">
                    <div class="btn-upload" onclick="document.getElementById('uploadGambarEdit').click()">
                        📷 <span id="btnUploadTextEdit">Pilih Gambar Baru...</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalEdit').style.display='none'">Batal</button>
                    <button type="submit" class="btn-add">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Buka modal edit & isi datanya otomatis
        function bukaModalEdit(id, judul, kategori, tanggal, isi, status) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_isi').value = isi;
            document.getElementById('edit_status').value = status;

            document.getElementById('modalEdit').style.display = 'flex';
        }

        // Ubah teks tombol upload kalo file udah dipilih
        function updateFileName(inputId, textId) {
            let input = document.getElementById(inputId);
            let text = document.getElementById(textId);
            if (input.files.length > 0) {
                text.innerText = "File terpilih: " + input.files[0].name;
                text.style.color = "#16a34a"; // Warna hijau
            } else {
                text.innerText = "Pilih Gambar untuk Diunggah";
                text.style.color = "var(--primary-blue)";
            }
        }

        // Script Dropdown Profil (Harus ada biar tetep jalan)
        function toggleDropdown(event) {
            let profil = document.getElementById("profileDropdown");
            if (profil) profil.classList.toggle("show");

            // Tutup notif kalau ada
            if (typeof toggleNotif === 'function') {
                let notif = document.getElementById("notifDropdown");
                if (notif) notif.classList.remove("show");
            }
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