<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

if (isset($_POST['tambah'])) {

    $nama = $_POST['nama_lengkap'];
    $no_telp = $_POST['no_telp'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'] ?? '';
    $id_cabang = $_POST['id_cabang'];

    mysqli_query($conn, "
        INSERT INTO jemaat (
            nama_lengkap,
            tanggal_lahir,
            no_telp,
            alamat,
            email,
            password,
            role,
            id_cabang
        )
        VALUES (
            '$nama',
            '$tanggal_lahir',
            '$no_telp',
            '$alamat',
            '$email',
            'churchsync123',
            'jemaat',
            '$id_cabang'
        )
    ");

    header("Location: data_jemaat_admin.php?status=sukses");
    exit();
}

$id_cabang_filter = $_GET['id_cabang'] ?? '';

if ($id_cabang_filter != '') {

    $query_jemaat = mysqli_query($conn, "
        SELECT j.*, c.nama_cabang
        FROM jemaat j
        LEFT JOIN cabang_gereja c
        ON j.id_cabang = c.id_cabang
        WHERE j.id_cabang = '$id_cabang_filter'
        ORDER BY j.role, j.nama_lengkap
    ");
} else {

    $query_jemaat = mysqli_query($conn, "
        SELECT j.*, c.nama_cabang
        FROM jemaat j
        LEFT JOIN cabang_gereja c
        ON j.id_cabang = c.id_cabang
        ORDER BY j.role, j.nama_lengkap
    ");
}

$query_cabang = mysqli_query($conn, "
    SELECT *
    FROM cabang_gereja
    ORDER BY nama_cabang
");
?>

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
            margin-top: 15px;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .akun-wrapper.aktif {
            display: block !important;
            /* Paksa muncul */
        }

        .modal-content {
            background: white;
            width: 500px;
            border-radius: 12px;
            padding: 30px;

            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'sukses') {
        echo "<script>alert('Data jemaat berhasil ditambahkan');</script>";
    }
    ?>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link active">Data Jemaat</a>
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
                        <?= $_SESSION['nama_lengkap']; ?> (Admin)
                    </div>
                    <div class="dropdown-content">
                        <a href="profil_admin.php">Profil Saya</a>
                        <a href="../login.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="header-toolbar">
                <div class="page-title">
                    <h2>Data Jemaat</h2>
                    <div class="toolbar-actions">
                        <input type="text" class="search-box" placeholder="Cari jemaat...">
                        <form method="GET">

                            <select
                                name="id_cabang"
                                class="filter-box"
                                onchange="this.form.submit()">

                                <option value="">Semua Cabang</option>

                                <?php
                                $filter_cabang = mysqli_query($conn, "
                                    SELECT *
                                    FROM cabang_gereja
                                    ORDER BY nama_cabang
                                ");

                                while ($cabang = mysqli_fetch_assoc($filter_cabang)) :
                                ?>

                                    <option
                                        value="<?= $cabang['id_cabang']; ?>"
                                        <?= ($id_cabang_filter == $cabang['id_cabang']) ? 'selected' : ''; ?>>

                                        <?= $cabang['nama_cabang']; ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </form>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="btn-add"
                        onclick="document.getElementById('modalTambahDataAdmin').style.display='flex'">
                        + Tambah Data Jemaat
                    </button>

                    <div id="modalTambahDataAdmin" class="modal-overlay">
                        <div class="modal-content">

                            <form method="POST">

                                <div class="modal-header">
                                    <h3>Tambah Data Jemaat</h3>
                                </div>

                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text"
                                        name="nama_lengkap"
                                        placeholder="Masukkan nama jemaat..."
                                        required>
                                </div>

                                <div class="form-group">
                                    <label>Cabang Penempatan</label>

                                    <select name="id_cabang" required>
                                        <?php while ($cabang = mysqli_fetch_assoc($query_cabang)) : ?>
                                            <option value="<?= $cabang['id_cabang']; ?>">
                                                <?= $cabang['nama_cabang']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text"
                                            name="no_telp"
                                            placeholder="0812xxxx">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date"
                                            name="tanggal_lahir">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Domisili</label>
                                    <input type="text"
                                        name="alamat"
                                        placeholder="Masukkan alamat lengkap...">
                                </div>

                                <div style="margin-top:15px;padding:15px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:8px;">

                                    <div style="padding:10px;background:#eef2f6;border-radius:8px;">
                                        <input type="checkbox"
                                            id="toggleAkun"
                                            onchange="toggleAkun(this)">

                                        <label for="toggleAkun">
                                            Buatkan Akun Login Web (Opsional)
                                        </label>
                                    </div>

                                    <div id="akunFields" class="akun-wrapper">

                                        <div class="form-group">
                                            <label>Email Login</label>
                                            <input type="email"
                                                name="email"
                                                placeholder="Masukkan email login">
                                        </div>

                                        <div class="form-group">
                                            <label>Password Default</label>
                                            <input type="text"
                                                value="churchsync123"
                                                readonly>
                                        </div>

                                    </div>

                                </div>

                                <div class="modal-actions">
                                    <button type="button"
                                        class="btn-cancel"
                                        onclick="document.getElementById('modalTambahDataAdmin').style.display='none'">
                                        Batal
                                    </button>

                                    <button type="submit"
                                        name="tambah"
                                        class="btn-add"
                                        style="background-color:var(--primary-blue);color:white;">
                                        Simpan Data
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    <script>
                        function toggleAkun(checkbox) {
                            const fields = document.getElementById('akunFields');

                            if (checkbox.checked) {
                                fields.style.display = "block";
                                fields.style.visibility = "visible";
                            } else {
                                fields.style.display = "none";
                            }
                        }
                    </script>

                </div>
            </div>

            <?php while ($row = mysqli_fetch_assoc($query_jemaat)) : ?>

                <div class="list-card">
                    <div class="item-info">
                        <div class="avatar">👤</div>

                        <div class="item-text">
                            <h4><?= $row['nama_lengkap']; ?></h4>

                            <p>
                                <?= $row['email']; ?>
                                • <?= $row['no_telp']; ?>
                            </p>

                            <?php
                            $role = $row['role'];

                            if ($role == 'admin') {
                                $bg = '#fee2e2';
                                $color = '#dc2626';
                            } elseif ($role == 'gembala_cabang') {
                                $bg = '#dbeafe';
                                $color = '#2563eb';
                            } else {
                                $bg = '#dcfce7';
                                $color = '#16a34a';
                            }
                            ?>

                            <span style="
                                display:inline-block;
                                margin-top:5px;
                                background:<?= $bg ?>;
                                color:<?= $color ?>;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:11px;
                                font-weight:600;
                            ">
                                <?= ucwords(str_replace('_', ' ', strtolower($role))); ?>
                            </span>
                        </div>
                    </div>

                    <div class="action-btns">

                        <button
                            class="btn-edit"
                            onclick="viewJemaat(
                                '<?= addslashes($row['nama_lengkap']); ?>',
                                '<?= htmlspecialchars($row['email']); ?>',
                                '<?= htmlspecialchars($row['no_telp']); ?>',
                                '<?= htmlspecialchars($row['tanggal_lahir']); ?>',
                                '<?= addslashes($row['alamat']); ?>',
                                '<?= addslashes($row['nama_cabang']); ?>',
                                '<?= htmlspecialchars($row['role']); ?>'
                            )">
                            👁️
                        </button>

                        <a href="edit_jemaat.php?id=<?= $row['id_jemaat']; ?>"
                            class="btn-edit"
                            style="text-decoration:none;padding:8px 12px;border-radius:6px;">
                            Edit Data
                        </a>

                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>

    <div id="modalViewData" class="modal-overlay">
        <div class="modal-content">

            <div class="modal-header">
                <h3>Detail Pengguna</h3>
            </div>

            <p><b>Nama:</b> <span id="view_nama"></span></p>
            <p><b>Email:</b> <span id="view_email"></span></p>
            <p><b>No Telepon:</b> <span id="view_telp"></span></p>
            <p><b>Tanggal Lahir:</b> <span id="view_tgl"></span></p>
            <p><b>Alamat:</b> <span id="view_alamat"></span></p>
            <p><b>Cabang:</b> <span id="view_cabang"></span></p>
            <p><b>Role:</b> <span id="view_role"></span></p>

            <div class="modal-actions">
                <button
                    class="btn-cancel"
                    onclick="document.getElementById('modalViewData').style.display='none'">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <script>
        function viewJemaat(nama, email, telp, tgl, alamat, cabang, role) {

            document.getElementById('view_nama').innerText = nama;
            document.getElementById('view_email').innerText = email;
            document.getElementById('view_telp').innerText = telp;
            document.getElementById('view_tgl').innerText = tgl;
            document.getElementById('view_alamat').innerText = alamat;
            document.getElementById('view_cabang').innerText = cabang;
            document.getElementById('view_role').innerText = role;

            document.getElementById('modalViewData').style.display = 'flex';
        }
    </script>
</body>

</html>