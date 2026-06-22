<?php
session_start();
include '../koneksi.php';

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../index.php");
    exit();
}

$id_jemaat = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM jemaat
    WHERE id_jemaat='$id_jemaat'
");

$data = mysqli_fetch_assoc($query);

$query_cabang = mysqli_query($conn, "
    SELECT *
    FROM cabang_gereja
    ORDER BY nama_cabang
");

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $id_cabang = $_POST['id_cabang'];
    $role = $_POST['role'];

    if ($role == 'gembala_cabang') {
        $cek_gembala = mysqli_query($conn, "SELECT id_jemaat FROM jemaat WHERE id_cabang = '$id_cabang' AND role = 'gembala_cabang' AND id_jemaat != '$id_jemaat'");
        
        if (mysqli_num_rows($cek_gembala) > 0) {
            header("Location: data_jemaat_admin.php?pesan=cabang_penuh");
            exit();
        }
    }

    mysqli_query($conn, "
        UPDATE jemaat
        SET
            nama_lengkap='$nama',
            email='$email',
            no_telp='$no_telp',
            tanggal_lahir='$tanggal_lahir',
            alamat='$alamat',
            role='$role',
            id_cabang='$id_cabang'
        WHERE id_jemaat='$id_jemaat'
    ");

    echo "
    <script>
        alert('Data jemaat berhasil diperbarui');
        window.location='data_jemaat_admin.php';
    </script>
    ";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Jemaat</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .05);
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-save {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-back {
            text-decoration: none;
            color: #dc3545;
            font-weight: bold;
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
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_admin_up.php" class="nav-link">Jadwal Ibadah</a>
            <a href="data_jemaat_admin.php" class="nav-link active">Data Jemaat</a>
            <a href="cabang_admin.php" class="nav-link">Cabang Gereja</a>
            <a href="profil_admin.php" class="nav-link">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="main-content">

            <h2 style="margin-bottom:20px;">
                Edit Data Jemaat
            </h2>

            <div class="card">

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            value="<?= $data['nama_lengkap']; ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value="<?= $data['email']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input
                            type="text"
                            name="no_telp"
                            value="<?= $data['no_telp']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input
                            type="date"
                            name="tanggal_lahir"
                            value="<?= $data['tanggal_lahir']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Cabang Gereja</label>

                        <select name="id_cabang">

                            <?php while ($cabang = mysqli_fetch_assoc($query_cabang)) : ?>

                                <option
                                    value="<?= $cabang['id_cabang']; ?>"
                                    <?= ($cabang['id_cabang'] == $data['id_cabang']) ? 'selected' : ''; ?>>
                                    <?= $cabang['nama_cabang']; ?>
                                </option>

                            <?php endwhile; ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Role</label>

                        <select name="role">

                            <option value="jemaat"
                                <?= ($data['role'] == 'jemaat') ? 'selected' : ''; ?>>
                                Jemaat
                            </option>

                            <option value="gembala_cabang"
                                <?= ($data['role'] == 'gembala_cabang') ? 'selected' : ''; ?>>
                                Gembala Cabang
                            </option>

                            <option value="admin"
                                <?= ($data['role'] == 'admin') ? 'selected' : ''; ?>>
                                Admin
                            </option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>

                        <textarea
                            name="alamat"
                            rows="4"><?= $data['alamat']; ?></textarea>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;">
                        <a href="data_jemaat_admin.php" class="btn-back">
                            ← Kembali
                        </a>

                        <button
                            type="submit"
                            name="simpan"
                            class="btn-save">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>