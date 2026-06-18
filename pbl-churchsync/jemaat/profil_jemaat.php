<?php
session_start();

/** @var mysqli $conn */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jemaat') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include '../koneksi.php';

$id_jemaat = $_SESSION['id_jemaat'];

$query_current = mysqli_query($conn, "SELECT * FROM jemaat WHERE id_jemaat='$id_jemaat'");
$data = mysqli_fetch_assoc($query_current);

$error_msg = "";
$success_msg = "";

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    $update_password = false;

    if (!empty($password_lama) || !empty($password_baru) || !empty($konfirmasi_password)) {

        if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
            $error_msg = "Gagal: Harap lengkapi seluruh kolom kata sandi jika ingin mengubahnya!";
        } elseif ($password_lama != $data['password']) {
            $error_msg = "Gagal: Password saat ini salah!";
        } elseif ($password_baru != $konfirmasi_password) {
            $error_msg = "Gagal: Konfirmasi password tidak cocok!";
        }
        // Lolos uji
        else {
            $update_password = true;
        }
    }

    if (empty($error_msg)) {
        if ($update_password) {
            mysqli_query($conn, "
                UPDATE jemaat
                SET nama_lengkap='$nama', email='$email', password='$password_baru', no_telp='$no_telp', tanggal_lahir='$tanggal_lahir', alamat='$alamat'
                WHERE id_jemaat='$id_jemaat'
            ");
            $data['password'] = $password_baru;
        } else {
            mysqli_query($conn, "
                UPDATE jemaat
                SET nama_lengkap='$nama', email='$email', no_telp='$no_telp', tanggal_lahir='$tanggal_lahir', alamat='$alamat'
                WHERE id_jemaat='$id_jemaat'
            ");
        }

        $success_msg = "Profil berhasil diperbarui!";

        $data['nama_lengkap'] = $nama;
        $data['email'] = $email;
        $data['no_telp'] = $no_telp;
        $data['tanggal_lahir'] = $tanggal_lahir;
        $data['alamat'] = $alamat;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - ChurchSync</title>
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
            background-color: #e2e8f0;
            color: var(--text-dark);
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Form Grid Layout */
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

        .form-group input:focus {
            outline: 2px solid var(--primary-blue);
            background-color: #fff;
        }

        /* Actions Footer */
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

        .btn-submit:hover {
            background-color: #152449;
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
            <a href="dashboard_jemaat.php" class="nav-link">Dashboard</a>
            <a href="pengumuman_jemaat.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_jemaat.php" class="nav-link">Jadwal Ibadah</a>
            <a href="profil_jemaat.php" class="nav-link active">Profil Saya</a>
        </nav>
    </div>

    <div class="content-wrapper">

        <div class="top-navbar">
            <div class="navbar-right">
                <?php include '../widget_notif.php'; ?>
                <div class="user-profile-dropdown" onclick="toggleDropdown(event)">
                    <div class="nav-avatar">👨🏽</div>

                    <div class="nav-user-name">
                        <?= $_SESSION['nama_lengkap']; ?> ▼
                    </div>

                    <div class="dropdown-content" id="profileDropdown">
                        <a href="profil_jemaat.php">Profil Saya</a>
                        <a href="../logout.php" class="logout-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Profil Saya</h2>
                <p>Kelola Informasi Pribadi Anda</p>
            </div>

            <div class="profile-card">
                <div class="profile-user-info">
                    <div class="big-avatar">👨🏽</div>
                    <div class="user-meta">
                        <h3><?= $_SESSION['nama_lengkap']; ?></h3>
                        <p><?= $data['email']; ?></p>
                        <span class="role-badge">Jemaat</span>
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
                            <label>Email</label>
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
                        <div class="form-group full-width" style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                            <h4 style="color: var(--primary-blue); margin-bottom: 5px; font-size: 16px;">Ubah Kata Sandi (Opsional)</h4>
                            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 15px;">Kosongkan jika tidak ingin mengubah kata sandi.</p>

                            <?php if ($error_msg != ""): ?>
                                <div id="php_error_msg" style="background-color: #fef2f2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f87171; font-weight: bold;">
                                    ⚠️ <?= $error_msg; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($success_msg != ""): ?>
                                <div id="php_success_msg" style="background-color: #dcfce7; color: #16a34a; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #4ade80; font-weight: bold;">
                                    ✅ <?= $success_msg; ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-grid" style="margin-bottom: 0;">
                                <div class="form-group full-width">
                                    <label>Kata Sandi Saat Ini</label>
                                    <input type="password" name="password_lama" id="pass_lama" placeholder="Masukkan kata sandi lama Anda" onkeyup="cekAlurPassword()">

                                    <small id="pesan_pass_lama" style="color: #dc2626; margin-top: 5px; display: none; font-weight: bold;">❌ Kata sandi saat ini salah!</small>
                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi Baru</label>
                                    <input type="password" name="password_baru" id="pass_baru" placeholder="Masukkan kata sandi baru" disabled onkeyup="cekAlurPassword()">
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" name="konfirmasi_password" id="pass_konfirm" placeholder="Ketik ulang kata sandi baru" disabled onkeyup="cekAlurPassword()">
                                    <small id="pesan_match" style="color: #dc2626; margin-top: 5px; display: none; font-weight: bold;">❌ Kata sandi tidak cocok!</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="../logout.php" class="btn-logout">Logout</a>
                        <button type="submit"
                            name="simpan"
                            class="btn-submit">
                            Simpan Profil Jemaat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleDropdown(event) {
            event.stopPropagation();

            const profile = document.getElementById("profileDropdown");
            if (profile) {
                profile.classList.toggle("show");
            }

            const notif = document.getElementById("notifDropdown");
            if (notif) {
                notif.classList.remove("show");
            }
        }

        window.onclick = function(event) {

            if (!event.target.closest('.user-profile-dropdown')) {
                const profile = document.getElementById("profileDropdown");
                if (profile) {
                    profile.classList.remove("show");
                }
            }

            if (!event.target.closest('.noti-container')) {
                const notif = document.getElementById("notifDropdown");
                if (notif) {
                    notif.classList.remove("show");
                }
            }
        }
        const passwordAsli = "<?= $data['password']; ?>";

        function cekAlurPassword() {

            let phpSuccess = document.getElementById('php_success_msg');
            if (phpSuccess) {
                phpSuccess.style.display = "none";
            }

            let phpError = document.getElementById('php_error_msg');
            if (phpError) {
                phpError.style.display = "none";
            }

            let inputLama = document.getElementById('pass_lama');
            let inputBaru = document.getElementById('pass_baru');
            let inputKonfirm = document.getElementById('pass_konfirm');

            let pesanPassLama = document.getElementById('pesan_pass_lama');
            let pesanMatch = document.getElementById('pesan_match');

            if (inputLama.value.length > 0) {
                if (inputLama.value !== passwordAsli) {
                    // Jika SALAH
                    pesanPassLama.style.display = "block";
                    pesanPassLama.style.color = "#dc2626";
                    pesanPassLama.innerHTML = "❌ Kata sandi saat ini salah!";
                    inputLama.style.borderColor = "#dc2626";

                    inputBaru.disabled = true;
                    inputKonfirm.disabled = true;
                    inputBaru.value = "";
                    inputKonfirm.value = "";
                    pesanMatch.style.display = "none";
                } else {
                    // Jika BENAR
                    pesanPassLama.style.display = "block";
                    pesanPassLama.style.color = "#16a34a";
                    pesanPassLama.innerHTML = "✅ Kata sandi benar!";
                    inputLama.style.borderColor = "#4ade80";

                    inputBaru.disabled = false;
                }
            } else {
                pesanPassLama.style.display = "none";
                inputLama.style.borderColor = "#cbd5e1";

                inputBaru.disabled = true;
                inputKonfirm.disabled = true;
                inputBaru.value = "";
                inputKonfirm.value = "";
                pesanMatch.style.display = "none";
            }

            if (inputBaru.value.length > 0) {
                inputKonfirm.disabled = false;
            } else {
                inputKonfirm.disabled = true;
                inputKonfirm.value = "";
                pesanMatch.style.display = "none";
            }

            if (inputKonfirm.value.length > 0) {
                if (inputBaru.value !== inputKonfirm.value) {
                    // Jika SALAH
                    pesanMatch.style.display = "block";
                    pesanMatch.style.color = "#dc2626";
                    pesanMatch.innerHTML = "❌ Kata sandi tidak cocok!";
                    inputKonfirm.style.borderColor = "#dc2626";
                } else {
                    // Jika BENAR
                    pesanMatch.style.display = "block";
                    pesanMatch.style.color = "#16a34a";
                    pesanMatch.innerHTML = "✅ Kata sandi cocok!";
                    inputKonfirm.style.borderColor = "#4ade80";
                }
            } else {
                pesanMatch.style.display = "none";
                inputKonfirm.style.borderColor = "#cbd5e1";
            }
        }
    </script>
</body>

</html>