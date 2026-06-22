<?php
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../index.php?pesan=belum_login");
    exit();
}
include '../koneksi.php';

$query_cabang = mysqli_query($conn, "SELECT COUNT(*) as total_cabang FROM cabang_gereja");
$data_cabang = mysqli_fetch_assoc($query_cabang);

$query_jemaat = mysqli_query($conn, "SELECT COUNT(*) as total_jemaat FROM jemaat");
$data_jemaat = mysqli_fetch_assoc($query_jemaat);

$query_laporan = mysqli_query($conn, "SELECT COUNT(*) as total_laporan FROM pendataan");
$data_laporan = mysqli_fetch_assoc($query_laporan);

$query_jadwal_terdekat = mysqli_query($conn, "
    SELECT kategori_ibadah, waktu_pelaksanaan 
    FROM jadwal_ibadah 
    WHERE waktu_pelaksanaan >= NOW() 
    ORDER BY waktu_pelaksanaan ASC 
    LIMIT 4
");

$query_aktivitas = mysqli_query($conn, "
    (SELECT 'laporan' AS tipe, waktu_pelaporan AS waktu, 'Gembala Cabang' AS aktor, 'mengirimkan laporan pendataan ibadah baru' AS aksi 
     FROM pendataan)
    UNION
    (SELECT 'update' AS tipe, temp_update_jemaat.tanggal_pengajuan AS waktu, jemaat.nama_lengkap AS aktor, 'mengajukan perubahan data profil' AS aksi 
     FROM temp_update_jemaat 
     JOIN jemaat ON temp_update_jemaat.id_jemaat = jemaat.id_jemaat)
    ORDER BY waktu DESC 
    LIMIT 3
");

$bulan_angka = date('m');
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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ChurchSync</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .admin-banner {
            background-color: var(--primary-blue);
            border-radius: 12px;
            padding: 25px 30px;
            color: white;
            margin-bottom: 25px;
        }

        .admin-banner h2 {
            margin-bottom: 5px;
        }

        .admin-banner p {
            color: #d1d5db;
            font-size: 14px;
        }

        .stat-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card-admin {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-admin ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .icon-box-admin {
            width: 50px;
            height: 50px;
            background-color: #eef2f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .data-box-admin p {
            font-size: 13px;
            color: #666;
            margin-bottom: 2px;
        }

        .data-box-admin h3 {
            font-size: 22px;
            color: var(--primary-blue);
        }

        .grid-two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card-admin {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-admin-header {
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .recent-activity-list {
            list-style: none;
        }

        .recent-activity-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: var(--text-dark);
            display: flex;
            justify-content: space-between;
        }

        .recent-activity-list li:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: #94a3b8;
            font-size: 12px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            background-color: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 10px;
            color: var(--primary-blue);
        }

        .btn-ucapan {
            margin-top: 10px;
            background-color: var(--primary-yellow);
            color: var(--primary-blue);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            width: 100%;
        }

        .user-profile-dropdown {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            z-index: 100;
            margin-top: 15px;
            border: 1px solid #e2e8f0;
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-content a {
            color: #334155;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-content a:hover {
            background-color: #f8fafc;
            color: var(--primary-blue);
        }

        .logout-item {
            color: #dc3545 !important;
            font-weight: bold;
        }

        .logout-item:hover {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
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
            <a href="dashboard_admin.php" class="nav-link active">Dashboard</a>
            <a href="pengumuman_admin.php" class="nav-link">Pengumuman</a>
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
            <div class="admin-banner">
                <h2>Selamat Datang Kembali, <?= $_SESSION['nama_lengkap']; ?>!</h2>
                <p>Sistem Informasi Administrasi Gereja Terpusat — Akun Admin</p>
            </div>

            <div class="stat-grid-3">
                <div class="stat-card-admin">
                    <div class="icon-box-admin">⛪</div>
                    <div class="data-box-admin">
                        <p>Total Cabang</p>
                        <h3><?= $data_cabang['total_cabang']; ?> Cabang</h3>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="icon-box-admin">👥</div>
                    <div class="data-box-admin">
                        <p>Total Jemaat Keseluruhan</p>
                        <h3><?= $data_jemaat['total_jemaat']; ?> Orang</h3>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="icon-box-admin">📄</div>
                    <div class="data-box-admin">
                        <p>Laporan Masuk</p>
                        <h3><?= $data_laporan['total_laporan']; ?> Laporan</h3>
                    </div>
                </div>
            </div>
            <div class="card-admin" style="margin-bottom: 25px;">
                <div class="card-admin-header">
                    <span>Ulang Tahun Jemaat (7 Hari Mendatang)</span>
                </div>
                <div class="birthday-list">
                    <?php if (mysqli_num_rows($q_ultah) > 0): ?>
                        <?php while ($ultah = mysqli_fetch_assoc($q_ultah)): ?>
                            <div class="birthday-item">
                                <div class="avatar">🧑🏽</div>
                                <p style="font-weight: bold; margin-bottom: 5px; color: var(--text-dark);"><?= htmlspecialchars($ultah['nama_lengkap']); ?></p>

                                <p style="font-size: 12px; color: #64748b; margin-top: 0; margin-bottom: 3px;"><?= $ultah['tgl'] . ' ' . $bulan_indo[$ultah['bln'] - 1]; ?></p>

                                <p style="font-size: 11px; color: var(--primary-blue); font-weight: 600; margin-top: 0; margin-bottom: 8px;">
                                    📍 <?= $ultah['nama_cabang'] ? htmlspecialchars($ultah['nama_cabang']) : 'Pusat'; ?>
                                </p>

                                <button class="btn-ucapan" onclick="kirimUcapanIni(<?= $ultah['id_jemaat']; ?>, this)">Kirim Ucapan</button>
                                <p style="font-size: 11px; margin-top: 8px; color: #f59e0b; font-weight: bold;">
                                    🎉 <?= $ultah['total_ucapan']; ?> orang mengucapkan
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #64748b; padding: 10px; width: 100%; text-align: center;">Tidak ada jemaat yang berulang tahun dalam 7 hari ke depan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid-two-column">
                <div class="card-admin">
                    <div class="card-admin-header">
                        <span>🔄 Aktivitas Sistem Terbaru</span>
                    </div>
                    <ul class="recent-activity-list">
                        <?php if (mysqli_num_rows($query_aktivitas) == 0) : ?>
                            <li style="justify-content: center; color: #94a3b8;">Belum ada aktivitas sistem terbaru.</li>
                        <?php else : ?>
                            <?php while ($row_act = mysqli_fetch_assoc($query_aktivitas)) : ?>
                                <li>
                                    <span>
                                        <?= $row_act['tipe'] == 'laporan' ? '📊' : '👤'; ?>
                                        <strong><?= $row_act['aktor']; ?></strong> <?= $row_act['aksi']; ?>
                                    </span>
                                    <span class="activity-time">
                                        <?= date('d M Y, H:i', strtotime($row_act['waktu'])); ?>
                                    </span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card-admin">
                    <div class="card-admin-header">
                        <span>📅 Jadwal Ibadah Mendatang</span>
                    </div>
                    <?php if (mysqli_num_rows($query_jadwal_terdekat) == 0) : ?>
                        <div style="text-align: center; color: #666; padding: 40px 0; background: #f8fafc; border-radius: 8px; font-size: 14px;">
                            [ Tidak ada jadwal ibadah terdekat ]
                        </div>
                    <?php else : ?>
                        <ul style="padding:0; margin:0;">
                            <?php while ($row_jdwal = mysqli_fetch_assoc($query_jadwal_terdekat)) : ?>
                                <li style="
                                        list-style: none;
                                        background: #f8fafc;
                                        margin-bottom: 8px;
                                        padding: 10px 15px;
                                        border-radius: 8px;
                                        border-left: 4px solid var(--primary-yellow);
                                    ">
                                    <div>
                                        <strong style="color: var(--primary-blue); display: block; margin-bottom: 3px;"><?= $row_jdwal['kategori_ibadah']; ?></strong>
                                        <div style="font-size: 12px; color: #666;">
                                            🕒 <?= date('l, d M Y - H:i', strtotime($row_jdwal['waktu_pelaksanaan'])); ?> WIB
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleDropdown(event) {
            document.getElementById("profileDropdown").classList.toggle("show");
            document.getElementById("notifDropdown").classList.remove("show"); // Otomatis tutup notif
            event.stopPropagation();
        }

        function toggleNotif(event) {
            document.getElementById("notifDropdown").classList.toggle("show");
            document.getElementById("profileDropdown").classList.remove("show"); // Otomatis tutup profil
            event.stopPropagation();
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
                document.getElementById("notifDropdown").classList.remove('show');
            }
        }

        function kirimUcapanIni(idPenerima, tombol) {
            let teksAsli = tombol.innerText;
            tombol.innerText = "Mengirim...";
            tombol.disabled = true;

            fetch('../proses_kirim_ucapan.php?id_penerima=' + idPenerima)
                .then(response => response.text())
                .then(hasil => {
                    if (hasil.trim() === 'sukses') {
                        alert('🎉 Ucapan selamat ulang tahun berhasil dikirim!');
                        location.reload(); // Refresh halaman biar angka notifnya nambah
                    } else if (hasil.trim() === 'udah_pernah') {
                        alert('Waduh, kamu udah ngirim ucapan ke jemaat ini tahun ini!');
                        tombol.innerText = "Sudah Terkirim";
                    } else {
                        alert('Error: Gagal mengirim ucapan.');
                        console.log(hasil);
                        tombol.innerText = teksAsli;
                        tombol.disabled = false;
                    }
                });
        }
    </script>
</body>

</html>