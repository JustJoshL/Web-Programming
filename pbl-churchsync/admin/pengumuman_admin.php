<?php
session_start();

/** @var mysqli $conn */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$query_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY tanggal_publikasi DESC");

$data_edit = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
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

        .btn-draft {
            background: #e2e8f0;
            color: #475569;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-draft:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
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
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name"><?= $_SESSION['nama_lengkap']; ?> (Admin)</div>▼
                    <div class="dropdown-content">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="login.php" class="logout-item">Logout</a>
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

            <?php
            if (mysqli_num_rows($query_pengumuman) == 0) {
                echo "<p style='text-align:center; color:#666;'>Belum ada data pengumuman.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($query_pengumuman)) :
            ?>
                    <div class="list-card">
                        <div class="list-info">
                            <h4 onclick="bukaModalView('<?= addslashes($row['judul_pengumuman']); ?>', '<?= $row['kategori_pengumuman']; ?>', '<?= date('d M Y', strtotime($row['tanggal_publikasi'])); ?>', '<?= addslashes(str_replace(array("\r", "\n"), '<br>', $row['isi_pengumuman'])); ?>', '<?= $row['gambar_pendukung']; ?>')" style="cursor: pointer; hover: underline;">
                                👁️ <?= $row['judul_pengumuman']; ?>
                            </h4>
                            <p>
                                <span class="badge-kategori"><?= $row['kategori_pengumuman']; ?></span>
                                Dipublikasikan: <?= date('d M Y', strtotime($row['tanggal_publikasi'])); ?> • Status: <?= $row['status_publikasi']; ?>
                            </p>
                        </div>
                        <div class="action-btns">
                            <a href="pengumuman_admin.php?edit_id=<?= $row['id_pengumuman']; ?>" class="btn-edit" style="text-decoration: none; display: inline-block;">Edit</a>
                            <a href="hapus_pengumuman.php?id=<?= $row['id_pengumuman']; ?>" class="btn-delete" onclick="return confirm('Yakin mau hapus pengumuman ini?');">Hapus</a>
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
                <h3>Form Pengumuman</h3>
            </div>

            <form action="proses_tambah_pengumuman.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Judul Pengumuman</label>
                    <input type="text" name="judul_pengumuman" placeholder="Masukkan judul..." required>
                </div>

                <div class="form-group">
                    <label>Dibuat Oleh</label>
                    <input type="text" value="<?= $_SESSION['nama_lengkap']; ?>" readonly style="background-color: #f1f5f9;">
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Kategori</label>
                        <select name="kategori_pengumuman" required>
                            <option value="Penting">Penting</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Ibadah">Ibadah</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Publikasi</label>
                        <input type="date" name="tanggal_publikasi" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi_pengumuman" rows="4" placeholder="Tulis rincian pengumuman..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Pendukung (Opsional)</label>

                    <span id="namaFilePilihan" style="font-size: 13px; color: #666; margin-bottom: 8px; display: block;">
                        Belum ada gambar yang dipilih.
                    </span>

                    <button type="button" class="btn-upload" onclick="document.getElementById('uploadGambar').click()">
                        📷 Pilih Gambar untuk Diunggah
                    </button>

                    <input type="file" name="gambar_pendukung" id="uploadGambar" accept="image/*" style="display: none;" onchange="tampilkanNamaFile(this)">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalTambah').style.display='none'">Batal</button>
                    <button type="submit" name="status_publikasi" value="Draft" class="btn-draft">Simpan sebagai Draft</button>
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
                
                <div class="form-group">
                    <label>Dibuat Oleh</label>
                    <input type="text" value="<?= $_SESSION['nama_lengkap']; ?>" readonly style="background-color: #f1f5f9;">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Kategori</label>
                        <select name="kategori_pengumuman" required>
                            <option value="Penting" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Penting') ? 'selected' : ''; ?>>Penting</option>
                            <option value="Kegiatan" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                            <option value="Ibadah" <?= (isset($data_edit) && $data_edit['kategori_pengumuman'] == 'Ibadah') ? 'selected' : ''; ?>>Ibadah</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Publikasi</label>
                        <input type="date" name="tanggal_publikasi" value="<?= $data_edit['tanggal_publikasi'] ?? ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi_pengumuman" rows="4" placeholder="Tulis rincian pengumuman..." required><?= $data_edit['isi_pengumuman'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Ganti Gambar Pendukung (Kosongkan jika tidak ingin ganti)</label>
                    <span id="namaFileEditPilihan" style="font-size: 13px; color: #666; margin-bottom: 8px; display: block;">
                        <?= (!empty($data_edit['gambar_pendukung'])) ? "Gambar saat ini: <strong>".$data_edit['gambar_pendukung']."</strong>" : "Belum ada gambar yang dipilih."; ?>
                    </span>
                    <button type="button" class="btn-upload" onclick="document.getElementById('uploadGambarEdit').click()">
                        📷 Pilih Gambar Baru
                    </button>
                    <input type="file" name="gambar_baru" id="uploadGambarEdit" accept="image/*" style="display: none;" onchange="tampilkanNamaFileEdit(this)">
                </div>

                <div class="modal-actions">
                    <a href="pengumuman_admin.php" class="btn-cancel" style="text-decoration: none; text-align: center;">Batal</a>
                    <button type="submit" name="status_publikasi" value="Draft" class="btn-draft">
                        Simpan sebagai Draft
                    </button>
                    
                    <button type="submit" name="status_publikasi" value="Published" class="btn-add">
                        Publikasikan
                    </button>
                </div>

            </form>
        </div>
    </div>
    <div id="modalView" class="modal-overlay">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header" style="border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px;">
                <h3 id="viewJudul" style="color: var(--primary-blue); font-size: 22px;">Judul Pengumuman</h3>
            </div>

            <div style="margin-top: 15px; margin-bottom: 15px; font-size: 13px; color: #666;">
                <span class="badge-kategori" id="viewKategori" style="background-color: #1e3264;">Kategori</span> •
                <span>Tanggal Rilis: <strong id="viewTanggal">28 April 2026</strong></span>
            </div>

            <div id="boxViewGambar" style="text-align: center; margin-bottom: 20px; display: none;">
                <img id="viewGambar" src="" alt="Gambar Pendukung" style="max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            </div>

            <div style="border: 1px solid #e2e8f0; background: #f8fafc; padding: 15px; border-radius: 8px; max-height: 200px; overflow-y: auto;">
                <p id="viewIsi" style="line-height: 1.6; color: #333; font-size: 14px; white-space: pre-line;"></p>
            </div>

            <div class="modal-actions" style="margin-top: 20px;">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalView').style.display='none'">Tutup</button>
            </div>
        </div>
    </div>
    <script>
        function tampilkanNamaFile(input) {
            var textIndikator = document.getElementById('namaFilePilihan');

            if (input.files && input.files[0]) {
                textIndikator.innerHTML = "Gambar baru dipilih: <strong>" + input.files[0].name + "</strong>";
                textIndikator.style.color = "#166534";
            } else {
                textIndikator.innerHTML = "Belum ada gambar yang dipilih.";
                textIndikator.style.color = "#666";
            }
        }

        function bukaModalView(judul, kategori, tanggal, isi, gambar) {
            document.getElementById('viewJudul').innerText = judul;
            document.getElementById('viewKategori').innerText = kategori;
            document.getElementById('viewTanggal').innerText = tanggal;
            document.getElementById('viewIsi').innerHTML = isi;

            var boxGambar = document.getElementById('boxViewGambar');
            var imgTag = document.getElementById('viewGambar');

            if (gambar !== "") {
                imgTag.src = "../uploads/" + gambar;
                boxGambar.style.display = "block";
            } else {
                boxGambar.style.display = "none";
            }

            document.getElementById('modalView').style.display = 'flex';
        }

        function tampilkanNamaFileEdit(input) {
            var textIndikator = document.getElementById('namaFileEditPilihan');
            if (input.files && input.files[0]) {
                textIndikator.innerHTML = "Gambar baru dipilih: <strong>" + input.files[0].name + "</strong>";
                textIndikator.style.color = "#166534";
            }
        }
    </script>
</body>

</html>