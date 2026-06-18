<?php
$nama_login_notif = $_SESSION['nama_lengkap'];

/** @var mysqli $conn */

$q_id_login = mysqli_query($conn, "SELECT id_jemaat FROM jemaat WHERE nama_lengkap = '$nama_login_notif'");
$data_login = mysqli_fetch_assoc($q_id_login);
$id_login_notif = $data_login['id_jemaat'] ?? 0;

$q_notif = mysqli_query($conn, "
    SELECT u.waktu_kirim, j.nama_lengkap AS nama_pengirim 
    FROM ucapan_ultah u
    JOIN jemaat j ON u.id_pengirim = j.id_jemaat
    WHERE u.id_penerima = '$id_login_notif' 
    ORDER BY u.waktu_kirim DESC 
    LIMIT 10
");
$jumlah_notif = mysqli_num_rows($q_notif);
?>

<style>
    .noti-container {
        position: relative;
        display: inline-block;
    }

    .noti-icon {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8fafc;
        transition: 0.3s;
        position: relative;
    }

    .noti-icon:hover {
        background: #e2e8f0;
    }

    .noti-badge {
        position: absolute;
        top: 10px;
        right: 0px;
        background: #ef4444;
        color: white;
        border-radius: 10px;
        font-size: 10px;
        padding: 2px 6px;
        font-weight: bold;
        border: 2px solid white;
        z-index: 10;
    }

    .noti-dropdown-content {
        display: none;
        position: absolute;
        top: 120%;
        right: 0;
        background-color: white;
        width: 320px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        z-index: 9999;
        border: 1px solid #e2e8f0;
        text-align: left;
        cursor: default;
    }

    .noti-dropdown-content.show {
        display: block;
        animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .noti-header {
        padding: 15px;
        font-weight: bold;
        border-bottom: 1px solid #f1f5f9;
        background: white;
        color: var(--text-dark);
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .noti-body {
        max-height: 350px;
        overflow-y: auto;
        background: #fafafa;
    }

    .noti-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        align-items: flex-start;
        transition: 0.2s;
        background: white;
    }

    .noti-item:hover {
        background: #f8fafc;
    }

    .noti-item:last-child {
        border-bottom: none;
    }

    .noti-avatar-box {
        font-size: 24px;
        background: #e0e7ff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .noti-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .noti-text strong {
        color: var(--primary-blue);
    }

    .noti-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 5px;
        font-weight: 500;
    }

    .noti-empty {
        padding: 30px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }
</style>

<div class="noti-container">
    <div class="noti-icon" onclick="toggleNotif(event)">
        🔔
        <?php if ($jumlah_notif > 0): ?>
            <span class="noti-badge"><?= $jumlah_notif; ?></span>
        <?php endif; ?>
    </div>
    <div class="noti-dropdown-content" id="notifDropdown" onclick="event.stopPropagation()">
        <div class="noti-header">
            <span>Notifikasi</span>
            <span style="font-size: 12px; color: var(--primary-blue); font-weight: 600;"><?= $jumlah_notif; ?> Baru</span>
        </div>
        <div class="noti-body">
            <?php if ($jumlah_notif > 0): ?>
                <?php while ($notif = mysqli_fetch_assoc($q_notif)): ?>
                    <div class="noti-item">
                        <div class="noti-avatar-box">🎉</div>
                        <div class="noti-text">
                            <strong><?= htmlspecialchars($notif['nama_pengirim']); ?></strong> mengirimkan ucapan selamat ulang tahun!
                            <div class="noti-time">🕒 <?= date('d M Y, H:i', strtotime($notif['waktu_kirim'])); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="noti-empty">
                    📭<br><br>Belum ada notifikasi baru untuk Anda.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleNotif(event) {
        document.getElementById("notifDropdown").classList.toggle("show");
        // Kalau kebetulan dropdown profil lagi kebuka, kita tutup
        let profile = document.getElementById("profileDropdown");
        if (profile) profile.classList.remove("show");
        event.stopPropagation();
    }
</script>