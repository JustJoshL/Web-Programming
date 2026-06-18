<?php
session_start();

/** @var mysqli $conn */


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jemaat') {
    header("location:../login.php?pesan=belum_login");
    exit();
}

include "../koneksi.php";

$id_cabang_user = $_SESSION['id_cabang'];
$query_cabang = mysqli_query($conn, "SELECT nama_cabang FROM cabang_gereja WHERE id_cabang = '$id_cabang_user'");
$data_cabang = mysqli_fetch_assoc($query_cabang);
$nama_cabang_asli = $data_cabang['nama_cabang'];
$bulan_angka = date('m');

$id_jemaat_login = $_SESSION['id_jemaat'] ?? 0;
$q_ultah = mysqli_query($conn, "
    SELECT j.id_jemaat, j.nama_lengkap, DAY(j.tanggal_lahir) as tgl, MONTH(j.tanggal_lahir) as bln, c.nama_cabang,
    (SELECT COUNT(*) FROM ucapan_ultah WHERE id_penerima = j.id_jemaat AND tahun = YEAR(NOW())) as total_ucapan
    FROM jemaat j
    LEFT JOIN cabang_gereja c ON j.id_cabang = c.id_cabang
    WHERE 
        (DATE_FORMAT(j.tanggal_lahir, '%m-%d') BETWEEN DATE_FORMAT(NOW(), '%m-%d') AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), '%m-%d'))
        OR 
        (DATE_FORMAT(NOW(), '%m-%d') > DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), '%m-%d') AND 
        (DATE_FORMAT(j.tanggal_lahir, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') OR DATE_FORMAT(j.tanggal_lahir, '%m-%d') <= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), '%m-%d')))
    ORDER BY 
        CASE WHEN DATE_FORMAT(j.tanggal_lahir, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') THEN 0 ELSE 1 END,
        DATE_FORMAT(j.tanggal_lahir, '%m-%d') ASC
    LIMIT 10
");
$bulan_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$bulan_nama = $bulan_indo[date('n') - 1];

$query_jadwal = mysqli_query($conn, "
    SELECT kategori_ibadah, waktu_pelaksanaan 
    FROM jadwal_ibadah 
    WHERE waktu_pelaksanaan >= NOW() 
    ORDER BY waktu_pelaksanaan ASC 
    LIMIT 3
");

$query_pengumuman_dash = mysqli_query($conn, "
    SELECT judul_pengumuman, tanggal_publikasi 
    FROM pengumuman 
    WHERE status_publikasi = 'Published' 
    AND (target_tipe = 'umum' OR id_cabang = '$id_cabang_user')
    ORDER BY tanggal_publikasi DESC 
    LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jemaat - ChurchSync</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .profile-banner {
            background-color: var(--primary-blue);
            border-radius: 12px;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            margin-bottom: 30px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            background-color: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-blue);
        }

        .profile-text h2 {
            margin-bottom: 5px;
        }

        .profile-text p {
            color: #d1d5db;
            font-size: 14px;
        }

        .btn-profile {
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: var(--primary-blue);
        }

        .btn-ucapan {
            margin-top: 10px;
            background-color: var(--primary-yellow);
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .pengumuman-list {
            list-style: none;
        }

        .pengumuman-list li {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-size: 14px;
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
            <a href="dashboard_jemaat.php" class="nav-link active">Dashboard</a>
            <a href="pengumuman_jemaat.php" class="nav-link">Pengumuman</a>
            <a href="jadwal_jemaat.php" class="nav-link">Jadwal Ibadah</a>
            <a href="profil_jemaat.php" class="nav-link">Profil Saya</a>
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

            <div class="profile-banner">
                <div class="profile-info">
                    <div class="avatar">👨🏽</div>
                    <div class="profile-text">
                        <h2><?= $_SESSION['nama_lengkap'] ?></h2>
                        <p>Jemaat <?= $nama_cabang_asli ?></p>
                    </div>
                </div>
                <button class="btn-profile" onclick="window.location.href='profil_jemaat.php'">Profile</button>
            </div>

            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3>Ulang Tahun Jemaat (7 Hari Mendatang)</h3>
                </div>
                <div class="birthday-list">
                    <?php if (mysqli_num_rows($q_ultah) > 0): ?>
                        <?php while ($row_ultah = mysqli_fetch_assoc($q_ultah)): ?>
                            <div class="birthday-item">
                                <div class="avatar">🧑🏽</div>
                                <p style="font-weight: bold; margin-bottom: 5px; color: var(--text-dark);"><?= htmlspecialchars($row_ultah['nama_lengkap']); ?></p>
                                <p style="font-size: 12px; color: #64748b; margin-top: 0; margin-bottom: 3px;"><?= $row_ultah['tgl'] . ' ' . $bulan_indo[$row_ultah['bln'] - 1]; ?></p>
                                <p style="font-size: 11px; color: var(--primary-blue); font-weight: 600; margin-top: 0; margin-bottom: 8px;">
                                    📍 <?= $row_ultah['nama_cabang'] ? htmlspecialchars($row_ultah['nama_cabang']) : 'Pusat'; ?>
                                </p>
                                <button class="btn-ucapan" onclick="kirimUcapanJemaat(<?= $row_ultah['id_jemaat'] ?>, this)">Kirim Ucapan</button>
                                <p style="font-size: 11px; margin-top: 8px; color: #f59e0b; font-weight: bold;">
                                    🎉 <?= $row_ultah['total_ucapan']; ?> orang mengucapkan
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #64748b; padding: 10px; width: 100%; text-align: center;">Tidak ada jemaat yang berulang tahun dalam 7 hari ke depan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid-container">
                <div class="card">
                    <div class="card-header" style="margin-bottom: 15px;">
                        <h3>Pengumuman</h3>
                        <a href="pengumuman_jemaat.php" style="font-size: 12px; text-decoration: none; color: var(--primary-blue); font-weight: 600;">Lihat Semua &rarr;</a>
                    </div>

                    <ul class="pengumuman-list">
                        <?php if (mysqli_num_rows($query_pengumuman_dash) > 0) : ?>

                            <?php while ($row_peng = mysqli_fetch_assoc($query_pengumuman_dash)) : ?>
                                <!-- Mesin fotokopi list pengumuman -->
                                <li style="display: flex; flex-direction: column; gap: 4px;">
                                    <strong style="color: var(--primary-blue); font-size: 14px;">
                                        <?= htmlspecialchars($row_peng['judul_pengumuman']); ?>
                                    </strong>
                                    <span style="font-size: 11px; color: #64748b;">
                                        📅 Dipublikasikan: <?= date('d M Y', strtotime($row_peng['tanggal_publikasi'])); ?>
                                    </span>
                                </li>
                            <?php endwhile; ?>

                        <?php else : ?>
                            <li style="text-align: center; color: #666; background: transparent; padding: 20px 0;">
                                Belum ada pengumuman terbaru.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card">

                    <div class="card-header" style="margin-bottom: 15px;">
                        <h3>Jadwal Mendatang</h3>
                        <a href="jadwal_jemaat.php" style="font-size: 12px; text-decoration: none; color: var(--primary-blue); font-weight: 600;">Lihat Semua &rarr;</a>
                    </div>

                    <div class="jadwal-list" style="display: flex; flex-direction: column; gap: 12px;">
                        <?php if (mysqli_num_rows($query_jadwal) == 0) : ?>
                            <div style="text-align: center; color: #666; padding: 40px 0; background: #f8fafc; border-radius: 8px; font-size: 14px;">
                                [ Tidak ada jadwal ibadah terdekat ]
                            </div>
                        <?php else : ?>

                            <?php while ($row_jdwal = mysqli_fetch_assoc($query_jadwal)) : ?>
                                <div style="display: flex; gap: 15px; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px; border-left: 4px solid var(--primary-yellow); transition: 0.2s;">

                                    <div style="background: white; padding: 8px 12px; border-radius: 6px; text-align: center; min-width: 50px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                        <span style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">
                                            <?= date('M', strtotime($row_jdwal['waktu_pelaksanaan'])); ?>
                                        </span>
                                        <span style="display: block; font-size: 18px; color: var(--primary-blue); font-weight: 800;">
                                            <?= date('d', strtotime($row_jdwal['waktu_pelaksanaan'])); ?>
                                        </span>
                                    </div>

                                    <div>
                                        <h4 style="margin: 0 0 4px 0; font-size: 14px; color: var(--text-dark);">
                                            <?= htmlspecialchars($row_jdwal['kategori_ibadah']); ?>
                                        </h4>
                                        <p style="margin: 0; font-size: 12px; color: #64748b;">
                                            🕒 <?= date('H:i', strtotime($row_jdwal['waktu_pelaksanaan'])); ?> WIB
                                        </p>
                                    </div>

                                </div>
                            <?php endwhile; ?>

                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        function toggleDropdown(event) {
            let profil = document.getElementById("profileDropdown");
            if (profil) profil.classList.toggle("show");

            let notif = document.getElementById("notifDropdown");
            if (notif) notif.classList.remove("show");

            event.stopPropagation();
        }

        function toggleDropdown(event) {
            event.stopPropagation();

            const profile = document.getElementById("profileDropdown");
            profile.classList.toggle("show");

            const notif = document.getElementById("notifDropdown");
            if (notif) notif.classList.remove("show");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-profile-dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            if (!event.target.closest('.noti-icon')) {
                let notif = document.getElementById("notifDropdown");
                if (notif) notif.classList.remove('show');
            }
        }

        function kirimUcapanJemaat(idPenerima, tombol) {
            let teksAsli = tombol.innerText;
            tombol.innerText = "Mengirim...";
            tombol.disabled = true;
            // Si kurir Fetch jalan keluar folder jemaat (../) menuju folder utama
            fetch('../proses_kirim_ucapan.php?id_penerima=' + idPenerima)
                .then(response => response.text())
                .then(hasil => {
                    if (hasil.trim() === 'sukses') {
                        alert('🎉 Ucapan selamat ulang tahun berhasil dikirim!');
                        location.reload(); // Refresh halaman biar data notifnya ke-update
                    } else if (hasil.trim() === 'udah_pernah') {
                        alert('Waduh, kamu udah ngirim ucapan ke jemaat ini tahun ini!');
                        tombol.innerText = "Sudah Terkirim";
                    } else {
                        alert('Error dari server: ' + hasil);
                        tombol.innerText = teksAsli;
                        tombol.disabled = false;
                    }
                });
        }
    </script>
</body>

</html>