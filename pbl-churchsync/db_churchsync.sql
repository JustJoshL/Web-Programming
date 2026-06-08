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
  PRIMARY KEY (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cabang_gereja` */

insert  into `cabang_gereja`(`id_cabang`,`nama_cabang`,`alamat_cabang`) values 
(1,'GBI Maranatha Pusat','Jl. Merdeka No. 1, Bandung'),
(2,'GBI Maranatha Dago','Jl. Ir. H. Juanda No. 100, Bandung'),
(3,'GBI Maranatha Pasteur','Jl. Dr. Djunjunan No. 50, Bandung'),
(4,'GBI Maranatha Buah Batu','Jl. Buah Batu No. 75, Bandung'),
(5,'GBI Maranatha Cimahi','Jl. Amir Machmud No. 20, Cimahi');

/*Table structure for table `jadwal_ibadah` */

DROP TABLE IF EXISTS `jadwal_ibadah`;

CREATE TABLE `jadwal_ibadah` (
  `id_jadwal` int(10) NOT NULL AUTO_INCREMENT,
  `kategori_ibadah` varchar(255) NOT NULL,
  `waktu_pelaksanaan` datetime(6) NOT NULL,
  `id_cabang` int(11) NOT NULL,
  PRIMARY KEY (`id_jadwal`),
  CONSTRAINT `kd_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `cabang_gereja` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jadwal_ibadah` */

insert  into `jadwal_ibadah`(`id_jadwal`,`kategori_ibadah`,`waktu_pelaksanaan`,`id_cabang`) values 
(1,'Ibadah Raya 1 Pusat','2026-06-07 07:00:00.000000',1),
(2,'Ibadah Raya 2 Dago','2026-06-07 09:30:00.000000',2),
(3,'Ibadah Youth Pasteur','2026-06-06 17:00:00.000000',3),
(4,'Ibadah Raya 1 Buah Batu','2026-06-07 08:00:00.000000',4),
(5,'Ibadah Tengah Minggu Cimahi','2026-06-10 18:30:00.000000',5);

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
  PRIMARY KEY (`id_jemaat`),
  CONSTRAINT `jemaat_ibfk_1` FOREIGN KEY (`id_jemaat`) REFERENCES `cabang_gereja` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jemaat` */

insert  into `jemaat`(`id_jemaat`,`nama_lengkap`,`tanggal_lahir`,`no_telp`,`alamat`,`email`,`password`,`role`) values 
(1,'Halan Walker','1985-10-12','08111222333','Jl. Setiabudi No. 12','halan@churchsync.com','admin123','admin'),
(2,'Pdt. Samuel','1970-05-20','08222333444','Jl. Cihampelas No. 8','samuel@churchsync.com','gembala123','gembala_cabang'),
(3,'Pdt. Andreas','1975-08-15','08333444555','Jl. Dipatiukur No. 45','andreas@churchsync.com','gembala123','gembala_cabang'),
(4,'Justin Bieber','1994-03-01','08444555666','Jl. Dago Asri No. 2','justin@gmail.com','churchsync123','jemaat'),
(5,'Vanessa Felicia','1998-11-25','08555666777','Jl. Lengkong No. 9','vanessa@gmail.com','churchsync123','jemaat');

/*Table structure for table `pendataan` */

DROP TABLE IF EXISTS `pendataan`;

CREATE TABLE `pendataan` (
  `id_pendataan` int(20) NOT NULL AUTO_INCREMENT,
  `jumlah_kehadiran` int(5) NOT NULL,
  `total_persembahan` bigint(15) NOT NULL,
  `total_perpuluhan` bigint(15) NOT NULL,
  `waktu_pelaporan` datetime(6) NOT NULL,
  PRIMARY KEY (`id_pendataan`),
  CONSTRAINT `pendataan_ibfk_1` FOREIGN KEY (`id_pendataan`) REFERENCES `jadwal_ibadah` (`id_jadwal`),
  CONSTRAINT `pendataan_ibfk_2` FOREIGN KEY (`id_pendataan`) REFERENCES `jemaat` (`id_jemaat`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pendataan` */

insert  into `pendataan`(`id_pendataan`,`jumlah_kehadiran`,`total_persembahan`,`total_perpuluhan`,`waktu_pelaporan`) values 
(1,250,5000000,15000000,'2026-06-07 10:00:00.000000'),
(2,300,6500000,20000000,'2026-06-07 12:30:00.000000'),
(3,120,2000000,5000000,'2026-06-06 19:00:00.000000'),
(4,80,800000,1000000,'2026-06-07 11:00:00.000000'),
(5,45,500000,0,'2026-06-10 21:00:00.000000');

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
  PRIMARY KEY (`id_pengumuman`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengumuman` */

insert  into `pengumuman`(`id_pengumuman`,`judul_pengumuman`,`isi_pengumuman`,`tanggal_publikasi`,`status_publikasi`,`kategori_pengumuman`,`gambar_pendukung`) values 
(1,'Retreat Tahunan','Akan diadakan retreat pada bulan depan.','2026-06-01','Published','Penting','retreat.jpg'),
(2,'Latihan Musik','Latihan musik dipindah ke hari Jumat.','2026-06-02','Published','Kegiatan','musik.jpg'),
(3,'Kerja Bakti','Mohon kehadiran jemaat untuk kerja bakti.','2026-06-03','Draft','Kegiatan','kerja_bakti.jpg'),
(4,'Kelas Baptisan','Pendaftaran kelas baptisan sudah dibuka.','2026-06-04','Published','Penting','baptisan.jpg'),
(5,'Ibadah Padang','Ibadah padang dilaksanakan di Lembang.','2026-06-05','Draft','Kegiatan','ibadah_padang.jpg'),
(8,'Jadwal Baptisan Air','Hari: Senin\r\nTanggal: 14 Juni 2026\r\nWaktu: 08:00 - 10:00\r\nTempat: Kolam Renang Surya Sport','2026-06-07','Published','Kegiatan',''),
(11,'Berita aja','Bla blabla ablablablablabala','2026-06-08','Published','Kegiatan','805862927115434280.jpg');

/*Table structure for table `penugasan_pelayan` */

DROP TABLE IF EXISTS `penugasan_pelayan`;

CREATE TABLE `penugasan_pelayan` (
  `peran_pelayanan` varchar(255) NOT NULL,
  `id_penugasan` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_penugasan`),
  CONSTRAINT `penugasan_pelayan_ibfk_3` FOREIGN KEY (`id_penugasan`) REFERENCES `jadwal_ibadah` (`id_jadwal`),
  CONSTRAINT `penugasan_pelayan_ibfk_4` FOREIGN KEY (`id_penugasan`) REFERENCES `jemaat` (`id_jemaat`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `penugasan_pelayan` */

insert  into `penugasan_pelayan`(`peran_pelayanan`,`id_penugasan`) values 
('Worship Leader',1),
('Pemusik',2),
('Singer',3),
('Usher',4),
('Pemusik',5);

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

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


ALTER TABLE jemaat
ADD id_cabang INT NOT NULL;

UPDATE jemaat
SET id_cabang = 1
WHERE id_cabang = 0 OR id_cabang IS NULL;

UPDATE jemaat SET id_cabang = 1 WHERE id_jemaat = 1;
UPDATE jemaat SET id_cabang = 1 WHERE id_jemaat = 2;
UPDATE jemaat SET id_cabang = 2 WHERE id_jemaat = 3;
UPDATE jemaat SET id_cabang = 2 WHERE id_jemaat = 4;
UPDATE jemaat SET id_cabang = 1 WHERE id_jemaat = 5;

ALTER TABLE jemaat
ADD CONSTRAINT fk_jemaat_cabang
FOREIGN KEY (id_cabang)
REFERENCES cabang_gereja(id_cabang);

ALTER TABLE jemaat
DROP FOREIGN KEY jemaat_ibfk_1;