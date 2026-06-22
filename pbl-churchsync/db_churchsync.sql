/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - db_churchsync
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_churchsync` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_churchsync`;

/*Table structure for table `cabang_gereja` */

DROP TABLE IF EXISTS `cabang_gereja`;

CREATE TABLE `cabang_gereja` (
  `id_cabang` int(5) NOT NULL AUTO_INCREMENT,
  `nama_cabang` varchar(100) NOT NULL,
  `alamat_cabang` varchar(255) NOT NULL,
  `id_gembala` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cabang_gereja` */

insert  into `cabang_gereja`(`id_cabang`,`nama_cabang`,`alamat_cabang`,`id_gembala`) values 
(1,'GBI Maranatha Pusat','Jl. Merdeka No. 1, Bandung',2),
(2,'GBI Maranatha Dago','Jl. Ir. H. Juanda No. 100, Bandung',3),
(3,'GBI Maranatha Pasteur','Jl. Dr. Djunjunan No. 50, Bandung',NULL),
(4,'GBI Maranatha Buah Batu','Jl. Buah Batu No. 75, Bandung',NULL),
(5,'GBI Maranatha Cimahi I','Jl. Amir Machmud No. 30, Cimahi',NULL);

/*Table structure for table `jadwal_ibadah` */

DROP TABLE IF EXISTS `jadwal_ibadah`;

CREATE TABLE `jadwal_ibadah` (
  `id_jadwal` int(10) NOT NULL AUTO_INCREMENT,
  `kategori_ibadah` varchar(255) NOT NULL,
  `waktu_pelaksanaan` datetime(6) NOT NULL,
  `id_cabang` int(11) NOT NULL,
  PRIMARY KEY (`id_jadwal`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jadwal_ibadah` */

insert  into `jadwal_ibadah`(`id_jadwal`,`kategori_ibadah`,`waktu_pelaksanaan`,`id_cabang`) values 
(1,'Ibadah Raya 1 Pusat','2026-06-07 07:00:00.000000',1),
(2,'Ibadah Raya 2 Dago','2026-06-07 09:30:00.000000',2),
(3,'Ibadah Youth Pasteur','2026-06-06 17:00:00.000000',3),
(4,'Ibadah Raya 1 Buah Batu','2026-06-07 08:00:00.000000',4),
(5,'Ibadah Tengah Minggu Cimahi','2026-06-10 18:30:00.000000',5),
(11,'Ibadah Kenaikan Tuhan Yesus','2026-06-14 11:00:00.000000',1),
(12,'Ibadah Paskah Part 2','2026-06-07 10:00:00.000000',1),
(13,'Ibadah Raya Youth Gabungan 1','2026-06-07 11:00:00.000000',3),
(15,'Ibadah Raya Youth 1','2026-06-21 11:00:00.000000',1),
(16,'Ibadah Raya Youth 2','2026-06-21 13:00:00.000000',1),
(17,'Ibadah Raya Pusat','2026-06-21 07:00:00.000000',1),
(18,'Ibadah Raya Dago','2026-06-21 09:30:00.000000',2),
(19,'Ibadah Raya Pasteur','2026-06-21 08:00:00.000000',3),
(20,'Ibadah Raya Buah Batu','2026-06-21 10:00:00.000000',4),
(21,'Ibadah Raya Cimahi I','2026-06-21 08:30:00.000000',5),
(24,'Ibadah Raya Youth 1','2026-06-28 11:00:00.000000',1);

/*Table structure for table `jemaat` */

DROP TABLE IF EXISTS `jemaat`;

CREATE TABLE `jemaat` (
  `id_jemaat` int(20) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_telp` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('jemaat','gembala_cabang','admin') NOT NULL,
  `id_cabang` int(11) NOT NULL,
  PRIMARY KEY (`id_jemaat`),
  KEY `fk_jemaat_cabang` (`id_cabang`),
  CONSTRAINT `fk_jemaat_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang_gereja` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jemaat` */

insert  into `jemaat`(`id_jemaat`,`nama_lengkap`,`tanggal_lahir`,`no_telp`,`alamat`,`email`,`password`,`role`,`id_cabang`) values 
(1,'Halan W','1985-10-12','081112223334444','Jl. Setiabudi No. 15','halan@churchsync.com','admin123','admin',1),
(2,'Pdt. Samuel','1970-05-20','0822233344455','Jl. Cihampelas No. 8','samuel@churchsync.com','gembala123','gembala_cabang',1),
(3,'Pdt. Andreas','1975-08-15','08333444555','Jl. Dipatiukur No. 45','andreas@churchsync.com','gembala123','gembala_cabang',3),
(4,'Justin Bieber','1994-03-01','08444555666','Jl. Dago Asri No. 2','justin@gmail.com','churchsync123','jemaat',2),
(5,'Vanessa Felicia','2005-11-25','08555666777','Jl. Lengkong No. 9, Bandung','vanessa@gmail.com','jemaat123','jemaat',1),
(6,'Henokh Pangaribuan','2026-06-07','081234567890','Jl. Prof. drg. Soeria Soemantri','henokhribuan@gmail.om','churchsync123','jemaat',4),
(8,'Kevin Saputra','2005-11-11','085120391283','Jl. Cimahi Lama No. 5, Bandung','kevinsap@gmail.com','churchsync123','gembala_cabang',4),
(9,'Joshua Siahaan','2005-06-22','081234571293','Jl. Supratman No. 15, Bandung','joshuashaan@gmail.com','churchsync123','jemaat',3),
(12,'Joshua Lewi','2005-05-10','081234571293','Jl. Supratman No. 14, Bandung','joshua@gmail.com','churchsync123','admin',3),
(49,'Budi Santoso','1990-01-15','08110000001','Jl. Merdeka No. 10, Bandung','budi.santoso@gmail.com','churchsync123','jemaat',1),
(50,'Siti Aminah','1985-04-20','08110000002','Jl. Braga No. 5, Bandung','siti.aminah@gmail.com','churchsync123','jemaat',1),
(51,'Andi Wijaya','2000-08-30','08110000003','Jl. Veteran No. 12, Bandung','andi.wijaya@gmail.com','churchsync123','jemaat',1),
(52,'Rina Melati','1995-02-14','08120000001','Jl. Dago No. 20, Bandung','rina.melati@gmail.com','churchsync123','jemaat',2),
(53,'Hendra Gunawan','1988-11-05','08120000002','Jl. Dipatiukur No. 15, Bandung','hendra.gunawan@gmail.com','churchsync123','jemaat',2),
(54,'Maya Sari','2001-12-25','08120000003','Jl. Tubagus Ismail No. 8, Bandung','maya.sari@gmail.com','churchsync123','jemaat',2),
(55,'Anton Pratama','1992-07-07','08130000001','Jl. Pasteur No. 30, Bandung','anton.pratama@gmail.com','churchsync123','jemaat',3),
(56,'Siska Indah','1998-06-25','08130000002','Jl. Sukajadi No. 45, Bandung','siska.indah@gmail.com','churchsync123','jemaat',3),
(58,'Agus Setiawan','1975-06-21','08140000001','Jl. Buah Batu No. 50, Bandung','agus.setiawan@gmail.com','churchsync123','jemaat',4),
(59,'Dewi Lestari','1982-06-20','08140000002','Jl. Turangga No. 22, Bandung','dewi.lestari@gmail.com','churchsync123','jemaat',4),
(60,'Rizky Maulana','2003-01-05','08140000003','Jl. Kiaracondong No. 80, Bandung','rizky.maulana@gmail.com','churchsync123','jemaat',4),
(61,'Yudi Hermawan','1991-05-15','08150000001','Jl. Amir Machmud No. 100, Cimahi','yudi.hermawan@gmail.com','churchsync123','jemaat',5),
(62,'Lina Marlina','1989-08-08','08150000002','Jl. Cihanjuang No. 33, Cimahi','lina.marlina@gmail.com','churchsync123','jemaat',5),
(63,'Fajar Nugroho','1997-06-22','08150000003','Jl. Cibabat No. 15, Cimahi','fajar.nugroho@gmail.com','churchsync123','gembala_cabang',5);

/*Table structure for table `pendataan` */

DROP TABLE IF EXISTS `pendataan`;

CREATE TABLE `pendataan` (
  `id_pendataan` int(20) NOT NULL AUTO_INCREMENT,
  `id_jadwal` int(11) NOT NULL,
  `jumlah_kehadiran` int(5) NOT NULL,
  `total_persembahan` bigint(15) NOT NULL,
  `total_perpuluhan` bigint(15) NOT NULL,
  `catatan` text DEFAULT NULL,
  `waktu_pelaporan` datetime(6) NOT NULL,
  PRIMARY KEY (`id_pendataan`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pendataan` */

insert  into `pendataan`(`id_pendataan`,`id_jadwal`,`jumlah_kehadiran`,`total_persembahan`,`total_perpuluhan`,`catatan`,`waktu_pelaporan`) values 
(1,1,250,5000000,15000000,NULL,'2026-06-07 10:00:00.000000'),
(2,2,300,6500000,20000000,NULL,'2026-06-07 12:30:00.000000'),
(3,3,120,2000000,5000000,NULL,'2026-06-06 19:00:00.000000'),
(4,4,80,800000,1000000,NULL,'2026-06-07 11:00:00.000000'),
(5,5,45,500000,0,NULL,'2026-06-10 21:00:00.000000'),
(12,12,100,1000000,1000000,'Detail kehadiran:\r\nPria: 50\r\nWanita: 50\r\n\r\nKesaksian: Ibadahnya oke, terberkati sekali dengan firmannya.','2026-06-08 21:52:43.000000'),
(13,13,170,1500000,1000000,'Ibadahnya asik..\r\nIbadahnya keren','2026-06-08 17:22:42.000000'),
(15,11,150,500000,500000,'asdklajslkfjasd','2026-06-19 02:44:01.000000');

/*Table structure for table `pengumuman` */

DROP TABLE IF EXISTS `pengumuman`;

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(20) NOT NULL AUTO_INCREMENT,
  `judul_pengumuman` varchar(255) NOT NULL,
  `isi_pengumuman` text NOT NULL,
  `tanggal_publikasi` date NOT NULL,
  `status_publikasi` enum('Draft','Published') NOT NULL,
  `kategori_pengumuman` varchar(50) NOT NULL,
  `gambar_pendukung` varchar(255) NOT NULL,
  `target_tipe` enum('umum','cabang') NOT NULL DEFAULT 'umum',
  `id_cabang` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pengumuman`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengumuman` */

insert  into `pengumuman`(`id_pengumuman`,`judul_pengumuman`,`isi_pengumuman`,`tanggal_publikasi`,`status_publikasi`,`kategori_pengumuman`,`gambar_pendukung`,`target_tipe`,`id_cabang`) values 
(2,'Latihan Musik','Latihan musik dipindah ke hari Jumat.','2026-06-02','Published','Kegiatan','musik.jpg','umum',NULL),
(3,'Kerja Bakti','Mohon kehadiran jemaat untuk kerja bakti.','2026-06-03','Published','Kegiatan','kerja_bakti.jpg','umum',NULL),
(8,'Jadwal Baptisan Air','Hari: Senin\r\nTanggal: 14 Juni 2026\r\nWaktu: 08:00 - 10:00\r\nTempat: Kolam Renang Surya Sport','2026-06-07','Published','Kegiatan','','umum',NULL),
(14,'Berita Videotron','Videotron di Maranatha Pusat rusak\r\nDengan demikian ibadah akan dialihkan ke ruang B GBI Maranatha Pusat','2026-06-16','Published','Penting','','cabang',1),
(20,'Jadwal Ibadah Paskah Gabungan 1','Ibadah akan diadakan di GBI Maranatha Pusat pukul 11.00','2026-04-05','Published','Ibadah','','umum',NULL),
(21,'Ibadah KKR Youth Se-Rayon','Syalom Youth! Mari hadir dalam Kebaktian Kebangunan Rohani (KKR) gabungan yang akan diadakan pada hari Sabtu ini. Jangan lupa ajak teman-teman kalian untuk memuji dan menyembah Tuhan bersama!','2026-06-18','Published','Ibadah','kkr_youth_2026.jpg','umum',NULL),
(22,'Pendaftaran Kelas Pra-Nikah Gelombang 2','Pendaftaran kelas Bina Pra-Nikah (BPN) gelombang kedua telah dibuka. Pendaftaran terakhir adalah akhir bulan ini. Silakan hubungi sekretariat gereja untuk mengambil formulir fisik.','2026-06-15','Published','Kegiatan','','umum',NULL),
(23,'Rapat Evaluasi Panitia Paskah','Diingatkan kepada seluruh panitia Paskah lokal untuk hadir dalam rapat pembubaran dan evaluasi panitia pada hari Minggu setelah ibadah raya siang selesai.\r\n\r\nHARAP HADIR TEPAT WAKTU!','2026-06-25','Draft','Penting','1781801075_6a34207348fef.jpg','cabang',1),
(24,'Perubahan Jam Ibadah Tengah Minggu','Perhatian untuk seluruh jemaat, khusus untuk minggu ini, Ibadah Tengah Minggu diundur menjadi pukul 19:00 WIB dikarenakan adanya pemeliharaan rutin kelistrikan pada gedung gereja.','2026-06-20','Published','Ibadah','','cabang',1),
(25,'Pokok Doa Jemaat Sakit','Mari kita bersatu hati mendukung dalam doa untuk kesembuhan Bapak Antonius yang saat ini sedang dirawat di Rumah Sakit. Kiranya Tuhan Yesus menjamah dan memulihkan beliau.','2026-06-19','Published','Penting','doa_bersama.jpg','umum',NULL),
(26,'Jadwal Ibadah Paskah Gabungan 1','Akan diakan di GBI Maranatha Pusat','2026-04-05','Published','Penting','1781708250_6a32b5dabf64c.jpg','umum',NULL),
(29,'Jadwal Ibadah Paskah Gabungan 2','asdlgkasldjaslfj','2026-06-19','Published','Ibadah','','umum',NULL);

/*Table structure for table `penugasan_pelayan` */

DROP TABLE IF EXISTS `penugasan_pelayan`;

CREATE TABLE `penugasan_pelayan` (
  `peran_pelayanan` varchar(255) NOT NULL,
  `id_penugasan` int(10) NOT NULL AUTO_INCREMENT,
  `id_jadwal` int(11) NOT NULL,
  `nama_pelayan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_penugasan`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `penugasan_pelayan` */

insert  into `penugasan_pelayan`(`peran_pelayanan`,`id_penugasan`,`id_jadwal`,`nama_pelayan`) values 
('Worship Leader',1,0,''),
('Pemusik',2,0,''),
('Singer',3,0,''),
('Usher',4,0,''),
('Pemusik',5,0,''),
('Pelayan Firman',12,12,'Vanessa Felicia'),
('Multimedia',13,12,'Justin Bieber'),
('Worship Leader',14,12,'Pdt. Samuel'),
('Pelayan Firman',18,13,'Vanessa Felicia'),
('Worship Leader',19,13,'Justin Bieber'),
('Multimedia',20,13,'Pdt. Samuel'),
('Multimedia',24,11,'Halan Walker'),
('Pelayan Firman',25,11,'Pdt. Samuel'),
('Worship Leader',26,11,'Vanessa Felicia'),
('Pelayan Firman',27,11,'Pdt. Samuel'),
('Pemusik',28,11,'Yudi Hermawan'),
('Worship Leader',29,11,'Justin Bieber');

/*Table structure for table `temp_update_jemaat` */

DROP TABLE IF EXISTS `temp_update_jemaat`;

CREATE TABLE `temp_update_jemaat` (
  `id_pengajuan` int(10) NOT NULL AUTO_INCREMENT,
  `id_jemaat` int(20) NOT NULL,
  `no_hp_baru` varchar(255) NOT NULL,
  `alamat_baru` text NOT NULL,
  `tanggal_pengajuan` datetime(6) NOT NULL,
  `status_pengajuan` enum('pending','disetujui','ditolak') NOT NULL,
  PRIMARY KEY (`id_pengajuan`),
  KEY `fk_temp_jemaat` (`id_jemaat`),
  CONSTRAINT `fk_temp_jemaat` FOREIGN KEY (`id_jemaat`) REFERENCES `jemaat` (`id_jemaat`) ON DELETE CASCADE,
  CONSTRAINT `temp_update_jemaat_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `jemaat` (`id_jemaat`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `temp_update_jemaat` */

insert  into `temp_update_jemaat`(`id_pengajuan`,`id_jemaat`,`no_hp_baru`,`alamat_baru`,`tanggal_pengajuan`,`status_pengajuan`) values 
(1,4,'08444999999','Jl. Dago Atas No. 15','2026-06-01 10:00:00.000000','pending'),
(2,5,'08555111222','Jl. Burangrang No. 8','2026-06-02 14:30:00.000000','disetujui'),
(3,1,'08111999888','Jl. Setiabudi No. 12A','2026-06-03 09:15:00.000000','ditolak'),
(4,4,'08123456789','Jl. Dago Asri No. 10','2026-06-04 11:45:00.000000','pending'),
(5,5,'08198765432','Jl. Asia Afrika No. 5','2026-06-04 16:20:00.000000','pending');

/*Table structure for table `ucapan_ultah` */

DROP TABLE IF EXISTS `ucapan_ultah`;

CREATE TABLE `ucapan_ultah` (
  `id_ucapan` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengirim` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `waktu_kirim` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_ucapan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `ucapan_ultah` */

insert  into `ucapan_ultah`(`id_ucapan`,`id_pengirim`,`id_penerima`,`tahun`,`waktu_kirim`) values 
(1,1,7,2026,'2026-06-16 22:21:28'),
(2,1,9,2026,'2026-06-18 13:59:27'),
(3,5,9,2026,'2026-06-18 14:08:51'),
(4,9,9,2026,'2026-06-18 14:29:20'),
(5,2,9,2026,'2026-06-18 20:21:52'),
(6,1,59,2026,'2026-06-19 07:33:30'),
(7,1,58,2026,'2026-06-19 07:34:00'),
(8,9,58,2026,'2026-06-19 07:44:56'),
(9,2,57,2026,'2026-06-22 21:29:18');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
