-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dsssaw
CREATE DATABASE IF NOT EXISTS `dsssaw` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dsssaw`;

-- Dumping structure for table dsssaw.alternatif
CREATE TABLE IF NOT EXISTS `alternatif` (
  `id_alternatif` int NOT NULL AUTO_INCREMENT,
  `nama_alternatif` varchar(35) NOT NULL,
  `hasil_alternatif` double NOT NULL,
  PRIMARY KEY (`id_alternatif`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table dsssaw.alternatif: ~4 rows (approximately)
INSERT IGNORE INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `hasil_alternatif`) VALUES
	(1, 'Bonbin', 69.545),
	(2, 'LawangSewu', 52.665),
	(3, 'Puri Maerokoco', 80.335),
	(6, 'Taman Lele', 66.335);

-- Dumping structure for table dsssaw.dimensi_servqual
CREATE TABLE IF NOT EXISTS `dimensi_servqual` (
  `id_dimensi` int NOT NULL AUTO_INCREMENT,
  `nama_dimensi` varchar(100) NOT NULL,
  PRIMARY KEY (`id_dimensi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dsssaw.dimensi_servqual: ~5 rows (approximately)
INSERT IGNORE INTO `dimensi_servqual` (`id_dimensi`, `nama_dimensi`) VALUES
	(1, 'Tangibles'),
	(2, 'Reliability'),
	(3, 'Responsiveness'),
	(4, 'Assurance'),
	(5, 'Empathy');

-- Dumping structure for table dsssaw.kriteria
CREATE TABLE IF NOT EXISTS `kriteria` (
  `id_kriteria` int NOT NULL AUTO_INCREMENT,
  `nama_kriteria` varchar(35) NOT NULL,
  `tipe_kriteria` varchar(35) NOT NULL,
  `bobot_kriteria` double NOT NULL,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- Dumping data for table dsssaw.kriteria: ~3 rows (approximately)
INSERT IGNORE INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `tipe_kriteria`, `bobot_kriteria`) VALUES
	(16, 'Biaya', 'cost', 15),
	(17, 'Jarak', 'cost', 35),
	(21, 'Fasilitas', 'benefit', 50);

-- Dumping structure for table dsssaw.nilai_alternatif
CREATE TABLE IF NOT EXISTS `nilai_alternatif` (
  `id_nilai_alternatif` int NOT NULL AUTO_INCREMENT,
  `ket_nilai_alternatif` varchar(35) NOT NULL,
  `jum_nilai_alternatif` double NOT NULL,
  PRIMARY KEY (`id_nilai_alternatif`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Dumping data for table dsssaw.nilai_alternatif: ~12 rows (approximately)
INSERT IGNORE INTO `nilai_alternatif` (`id_nilai_alternatif`, `ket_nilai_alternatif`, `jum_nilai_alternatif`) VALUES
	(14, 'BiayaBonbin', 60000),
	(15, 'JarakBonbin', 11),
	(16, 'FasilitasBonbin', 3),
	(18, 'BiayaLawangSewu', 40000),
	(19, 'JarakLawangSewu', 5),
	(20, 'FasilitasLawangSewu', 1),
	(21, 'BiayaPuriMaerokoco', 50000),
	(22, 'JarakPuriMaerokoco', 3),
	(23, 'FasilitasPuriMaerokoco', 2),
	(24, 'BiayaTamanLele', 50000),
	(25, 'JarakTamanLele', 5),
	(26, 'FasilitasTamanLele', 2);

-- Dumping structure for table dsssaw.nilai_kriteria
CREATE TABLE IF NOT EXISTS `nilai_kriteria` (
  `id_nilai_kriteria` int NOT NULL AUTO_INCREMENT,
  `ket_nilai_kriteria` varchar(35) NOT NULL,
  `jum_nilai_kriteria` double NOT NULL,
  PRIMARY KEY (`id_nilai_kriteria`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table dsssaw.nilai_kriteria: ~2 rows (approximately)
INSERT IGNORE INTO `nilai_kriteria` (`id_nilai_kriteria`, `ket_nilai_kriteria`, `jum_nilai_kriteria`) VALUES
	(6, 'Biaya', 15),
	(7, 'Jarak', 35),
	(11, 'Fasilitas', 50);



-- Dumping structure for table dsssaw.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_telp` varchar(50) DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenis_kelamin` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dsssaw.users: ~0 rows (approximately)
INSERT IGNORE INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `no_telp`, `alamat`, `role`) VALUES
	(10, 'afg2002', '$2a$12$20iR8GyHjeVO3tdHCwYrBOS9YgFk7CFXtQTSnotexAtQci9SKvXYW', 'afghan', 'yaya@gmail.com', '2', 'test', 'admin'),
	(26, 'test', '$2y$10$ePsSWQf/Och4f364I8.Q0uo5m1kPkmbK6ZuJvzZBegFRMPMaacLV.', 'test', 'test@gmail.com', 'test', 'test', 'admin');


-- Dumping structure for table dsssaw.pertanyaan_servqual
CREATE TABLE IF NOT EXISTS `pertanyaan_servqual` (
  `id_pertanyaan` int NOT NULL AUTO_INCREMENT,
  `id_dimensi` int NOT NULL,
  `pertanyaan` text NOT NULL,
  PRIMARY KEY (`id_pertanyaan`),
  KEY `id_dimensi` (`id_dimensi`),
  CONSTRAINT `pertanyaan_servqual_ibfk_1` FOREIGN KEY (`id_dimensi`) REFERENCES `dimensi_servqual` (`id_dimensi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pertanyaan_servqual` (`id_dimensi`, `pertanyaan`) VALUES
(1, 'Kebersihan dan kerapian fisik barang yang digunakan atau tempat pelayanan'),
(1, 'Ketersediaan peralatan yang diperlukan'),
(1, 'Penampilan fisik karyawan dan bagian peralatan'),
(2, 'Keandalan dalam menyelesaikan masalah pelanggan'),
(2, 'Kemampuan memberikan pelayanan sesuai janji'),
(3, 'Responsif terhadap kebutuhan pelanggan'),
(3, 'Waktu yang dibutuhkan untuk melayani pelanggan'),
(4, 'Pengetahuan dan keterampilan karyawan untuk memberikan pelayanan'),
(4, 'Karyawan kami sopan dan menghormati pelanggan'),
(5, 'Perhatian individu terhadap pelanggan'),
(5, 'Kemampuan untuk memberikan perhatian yang individualis');


-- Dumping structure for table dsssaw.penilaian_servqual
CREATE TABLE IF NOT EXISTS `penilaian_servqual` (
  `id_penilaian` int NOT NULL AUTO_INCREMENT,
  `id_konsumen` int NOT NULL,
  `id_pertanyaan` int NOT NULL,
  `nilai_persepsi` int NOT NULL,
  `nilai_harapan` int NOT NULL,
  PRIMARY KEY (`id_penilaian`),
  KEY `id_konsumen` (`id_konsumen`),
  KEY `id_pertanyaan` (`id_pertanyaan`),
  CONSTRAINT `penilaian_servqual_ibfk_1` FOREIGN KEY (`id_konsumen`) REFERENCES `users` (`id`),
  CONSTRAINT `penilaian_servqual_ibfk_2` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan_servqual` (`id_pertanyaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dsssaw.penilaian_servqual: ~0 rows (approximately)

-- Dumping structure for table dsssaw.rangking
CREATE TABLE IF NOT EXISTS `rangking` (
  `id_alternatif` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `nilai_rangking` double DEFAULT NULL,
  `nilai_normalisasi` double DEFAULT NULL,
  `bobot_normalisasi` double DEFAULT NULL,
  PRIMARY KEY (`id_alternatif`,`id_kriteria`),
  KEY `foreign2` (`id_kriteria`),
  CONSTRAINT `foreign1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`),
  CONSTRAINT `foreign2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table dsssaw.rangking: ~12 rows (approximately)
INSERT IGNORE INTO `rangking` (`id_alternatif`, `id_kriteria`, `nilai_rangking`, `nilai_normalisasi`, `bobot_normalisasi`) VALUES
	(1, 16, 60000, 0.6667, 10.0005),
	(1, 17, 11, 0.2727, 9.5445),
	(1, 21, 3, 1, 50),
	(2, 16, 40000, 1, 15),
	(2, 17, 5, 0.6, 21),
	(2, 21, 1, 0.3333, 16.665),
	(3, 16, 50000, 0.8, 12),
	(3, 17, 3, 1, 35),
	(3, 21, 2, 0.6667, 33.335),
	(6, 16, 50000, 0.8, 12),
	(6, 17, 5, 0.6, 21),
	(6, 21, 2, 0.6667, 33.335);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
