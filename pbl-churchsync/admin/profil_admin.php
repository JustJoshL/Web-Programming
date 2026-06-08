<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_jemaat = $_SESSION['id_jemaat'];

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password_baru = $_POST['password_baru'];

    $no_telp = $_POST['no_telp'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    if (!empty($password_baru)) {

        mysqli_query($conn, "
            UPDATE jemaat
            SET nama_lengkap='$nama',
                email='$email',
                password='$password_baru',
                no_telp='$no_telp',
                tanggal_lahir='$tanggal_lahir',
                alamat='$alamat'
            WHERE id_jemaat='$id_jemaat'
        ");
    } else {

        mysqli_query($conn, "
            UPDATE jemaat
            SET nama_lengkap='$nama',
                email='$email',
                no_telp='$no_telp',
                tanggal_lahir='$tanggal_lahir',
                alamat='$alamat'
            WHERE id_jemaat='$id_jemaat'
        ");
    }

    echo "<script>
            alert('Profil berhasil diperbarui');
            window.location='profil_admin.php';
          </script>";
    exit();
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM jemaat WHERE id_jemaat='$id_jemaat'"
);

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Admin ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .profile-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .profile-user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .big-avatar {
            width: 80px;
            height: 80px;
            background-color: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
        }

        .user-meta h3 {
            color: var(--primary-blue);
            font-size: 22px;
            margin-bottom: 2px;
        }

        .user-meta p {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #dc3545;
            color: white;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            font-size: 14px;
            color: var(--text-dark);
        }

        .form-group textarea {
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            font-size: 14px;
            color: var(--text-dark);
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .btn-logout {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
        }

        .btn-submit {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">ChurchSync<span>ALL ABOUT OUR CHURCH</span></div>
        <nav>
            <a href="dashboard_admin.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link active">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="top-navbar">
            <div class="navbar-right">
                <div class="noti-icon">🔔<span class="noti-badge"></span></div>
                <div class="user-profile-dropdown">
                    <div class="nav-avatar">⚡</div>
                    <div class="nav-user-name">
                        <?= $data['nama_lengkap']; ?> (<?= ucfirst($data['role']); ?>)
                    </div>
                    <div class="dropdown-content"><a href="login.php">Logout</a></div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-toolbar" style="margin-bottom: 20px;">
                <h2>Profil Sistem Admin</h2>
            </div>

            <div class="profile-card">
                <div class="profile-user-info">
                    <div class="big-avatar">⚡</div>
                    <div class="user-meta">
                        <h3><?= $data['nama_lengkap']; ?></h3>
                        <p><?= $data['email']; ?></p>
                        <span class="role-badge"><?= ucfirst($data['role']); ?></span>
                    </div>
                </div>

                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text"
                                name="nama_lengkap"
                                value="<?= $data['nama_lengkap']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email Admin</label>
                            <input type="email"
                                name="email"
                                value="<?= $data['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text"
                                name="no_telp"
                                value="<?= $data['no_telp']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date"
                                name="tanggal_lahir"
                                value="<?= $data['tanggal_lahir']; ?>">
                        </div>
                        <div class="form-group full-width">
                            <label>Alamat</label>
                            <textarea name="alamat"
                                rows="4"><?= $data['alamat']; ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Kata Sandi Baru (Opsional)</label>
                            <input type="password"
                                name="password_baru"
                                placeholder="Masukkan password baru">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="../login.php" class="btn-logout">Logout</a>
                        <button type="submit"
                            name="simpan"
                            class="btn-submit">
                            Simpan Profil Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>