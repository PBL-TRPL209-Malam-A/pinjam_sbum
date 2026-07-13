-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: db_pinjam_sbum
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barang` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stok_tersedia` int DEFAULT '0',
  `foto_barang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `pic_id` int DEFAULT NULL,
  PRIMARY KEY (`id_barang`),
  UNIQUE KEY `kode_barang` (`kode_barang`),
  KEY `barang_pic_id_foreign` (`pic_id`),
  CONSTRAINT `barang_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` VALUES (1,'Proyektor','PRY001',10,'uploads/barang/1783865605_proyektor.jpg','Gudang Sbum',4),(2,'Laptop Pink','LP001',9,'uploads/barang/1783177238_laptop gaming pink.jpg','Gedung Depan',4);
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_peminjaman_barang`
--

DROP TABLE IF EXISTS `detail_peminjaman_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_peminjaman_barang` (
  `id_detail_peminjaman_barang` int NOT NULL AUTO_INCREMENT,
  `peminjaman_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_detail_peminjaman_barang`),
  KEY `fk_detail_barang_peminjaman` (`peminjaman_id`),
  KEY `fk_detail_barang_id` (`barang_id`),
  CONSTRAINT `fk_detail_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `fk_detail_barang_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_peminjaman_barang`
--

LOCK TABLES `detail_peminjaman_barang` WRITE;
/*!40000 ALTER TABLE `detail_peminjaman_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_peminjaman_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_peminjaman_ruangan`
--

DROP TABLE IF EXISTS `detail_peminjaman_ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_peminjaman_ruangan` (
  `id_detail_peminjaman_ruangan` int NOT NULL AUTO_INCREMENT,
  `peminjaman_id` int NOT NULL,
  `ruangan_id` int NOT NULL,
  PRIMARY KEY (`id_detail_peminjaman_ruangan`),
  KEY `fk_detail_ruangan_peminjaman` (`peminjaman_id`),
  KEY `fk_detail_ruangan_id` (`ruangan_id`),
  CONSTRAINT `fk_detail_ruangan_id` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id_ruangan`),
  CONSTRAINT `fk_detail_ruangan_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_peminjaman_ruangan`
--

LOCK TABLES `detail_peminjaman_ruangan` WRITE;
/*!40000 ALTER TABLE `detail_peminjaman_ruangan` DISABLE KEYS */;
INSERT INTO `detail_peminjaman_ruangan` VALUES (1,1,1),(2,2,1),(3,3,1);
/*!40000 ALTER TABLE `detail_peminjaman_ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_pengembalian_barang`
--

DROP TABLE IF EXISTS `detail_pengembalian_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_pengembalian_barang` (
  `id_detail_pengembalian_barang` int NOT NULL AUTO_INCREMENT,
  `id_pengembalian_barang` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah_barang_dikembalikan` int NOT NULL DEFAULT '1',
  `kondisi_barang` enum('baik','rusak ringan','rusak berat','hilang') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_detail_pengembalian_barang`),
  KEY `fk_detail_pengembalian_barang_header` (`id_pengembalian_barang`),
  KEY `fk_detail_pengembalian_barang_id` (`id_barang`),
  CONSTRAINT `fk_detail_pengembalian_barang_header` FOREIGN KEY (`id_pengembalian_barang`) REFERENCES `pengembalian_barang` (`id_pengembalian_barang`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_pengembalian_barang_id` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_pengembalian_barang`
--

LOCK TABLES `detail_pengembalian_barang` WRITE;
/*!40000 ALTER TABLE `detail_pengembalian_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_pengembalian_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_pengembalian_ruangan`
--

DROP TABLE IF EXISTS `detail_pengembalian_ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_pengembalian_ruangan` (
  `id_detail_pengembalian_ruangan` int NOT NULL AUTO_INCREMENT,
  `id_pengembalian_ruangan` int NOT NULL,
  `id_ruangan` int NOT NULL,
  `kondisi_ruangan` enum('baik','rusak ringan','rusak berat') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_detail_pengembalian_ruangan`),
  KEY `fk_detail_pengembalian_ruangan` (`id_pengembalian_ruangan`),
  KEY `fk_detail_pengembalian_ruangan_id` (`id_ruangan`),
  CONSTRAINT `fk_detail_pengembalian_ruangan` FOREIGN KEY (`id_pengembalian_ruangan`) REFERENCES `pengembalian_ruangan` (`id_pengembalian_ruangan`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_pengembalian_ruangan_id` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_pengembalian_ruangan`
--

LOCK TABLES `detail_pengembalian_ruangan` WRITE;
/*!40000 ALTER TABLE `detail_pengembalian_ruangan` DISABLE KEYS */;
INSERT INTO `detail_pengembalian_ruangan` VALUES (1,1,1,'baik',NULL),(2,1,1,'baik',NULL),(3,1,1,'baik',NULL),(4,2,1,'baik',NULL);
/*!40000 ALTER TABLE `detail_pengembalian_ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fasilitas_ruangan`
--

DROP TABLE IF EXISTS `fasilitas_ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fasilitas_ruangan` (
  `id_fasilitas` int NOT NULL AUTO_INCREMENT,
  `id_ruangan` int NOT NULL,
  `nama_fasilitas` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int DEFAULT '1',
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_fasilitas`),
  KEY `fk_fasilitas_ruangan` (`id_ruangan`),
  CONSTRAINT `fk_fasilitas_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fasilitas_ruangan`
--

LOCK TABLES `fasilitas_ruangan` WRITE;
/*!40000 ALTER TABLE `fasilitas_ruangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `fasilitas_ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_06_14_000002_add_deskripsi_ruangan_to_ruangan_table',1),(2,'0001_01_01_000000_create_users_table',1),(3,'0001_01_01_000001_create_cache_table',1),(4,'0001_01_01_000002_create_jobs_table',1),(5,'2026_01_01_000010_create_ruangan_dan_barang_tables',1),(6,'2026_01_01_000011_create_peminjaman_dan_detail_tables',1),(7,'2026_01_01_000012_create_pengembalian_dan_detail_tables',1),(8,'2026_01_01_000013_create_verifikasi_tables',1),(9,'2026_06_23_000001_create_schedules_table',2),(10,'2026_06_26_000001_update_id_pic_to_pic_id_in_ruangan_dan_barang',3),(11,'2026_06_27_000001_update_status_in_peminjaman_table',4),(12,'2026_06_27_000002_add_jam_mulai_dan_jam_selesai_to_peminjaman_table',5),(13,'2026_06_28_000001_add_foto_dan_dokumen_to_pengembalian_tables',6),(14,'2026_06_28_000002_add_tanggal_dan_jam_aktual_to_pengembalian_tables',7),(15,'2026_06_28_000003_remove_redundant_date_from_pengembalian_tables',8),(16,'2026_06_28_000004_add_status_to_pengembalian_tables',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `dosen_id` int DEFAULT NULL,
  `nama_kegiatan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_peserta` int DEFAULT NULL,
  `jenis_peminjaman` enum('barang','ruangan','keduanya') COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengajuan` datetime DEFAULT CURRENT_TIMESTAMP,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu_dosen',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `pic_id` int DEFAULT NULL,
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_user` (`user_id`),
  KEY `fk_peminjaman_dosen` (`dosen_id`),
  KEY `peminjaman_pic_id_foreign` (`pic_id`),
  CONSTRAINT `fk_peminjaman_dosen` FOREIGN KEY (`dosen_id`) REFERENCES `user` (`id_user`),
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  CONSTRAINT `peminjaman_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `user` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (1,3,8,'Seminar Kendaraan',120,'ruangan','2026-07-11 08:00:00','08:00:00','11:00:00','selesai','Perkenalan kendaraan lapis baja',4),(2,13,8,'Seminar Peminjam Baru',180,'ruangan','2026-07-12 08:00:00','08:00:00','12:00:00','menunggu_pic','ACC Peminjama Ruangan dong',4),(3,14,8,'Belajar Bareng mengenai hosting',15,'ruangan','2026-07-13 10:00:00','10:00:00','12:00:00','selesai','belajar mengenai vercel,ruangweb,dan hosting hosting lain',4);
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengembalian_barang`
--

DROP TABLE IF EXISTS `pengembalian_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengembalian_barang` (
  `id_pengembalian_barang` int NOT NULL AUTO_INCREMENT,
  `peminjaman_id` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu_pic',
  `foto_kondisi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dokumen_administrasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jam_selesai_aktual` time DEFAULT NULL,
  PRIMARY KEY (`id_pengembalian_barang`),
  KEY `fk_pengembalian_barang_peminjaman` (`peminjaman_id`),
  CONSTRAINT `fk_pengembalian_barang_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengembalian_barang`
--

LOCK TABLES `pengembalian_barang` WRITE;
/*!40000 ALTER TABLE `pengembalian_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengembalian_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengembalian_ruangan`
--

DROP TABLE IF EXISTS `pengembalian_ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengembalian_ruangan` (
  `id_pengembalian_ruangan` int NOT NULL AUTO_INCREMENT,
  `peminjaman_id` int NOT NULL,
  `tanggal_pengembalian` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu_pic',
  `foto_kondisi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dokumen_administrasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jam_selesai_aktual` time DEFAULT NULL,
  PRIMARY KEY (`id_pengembalian_ruangan`),
  KEY `fk_pengembalian_ruangan_peminjaman` (`peminjaman_id`),
  CONSTRAINT `fk_pengembalian_ruangan_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengembalian_ruangan`
--

LOCK TABLES `pengembalian_ruangan` WRITE;
/*!40000 ALTER TABLE `pengembalian_ruangan` DISABLE KEYS */;
INSERT INTO `pengembalian_ruangan` VALUES (1,1,'2026-07-10',NULL,'selesai','uploads/pengembalian/foto/1783697882_6a5111da3ca1c.jpg','uploads/pengembalian/dokumen/1783697882_6a5111da3d0d6.pdf','22:36:00'),(2,3,'2026-07-12',NULL,'selesai','uploads/pengembalian/foto/1783868923_6a53adfbbc2e4.jpg','uploads/pengembalian/dokumen/1783868923_6a53adfbbc99d.pdf','22:07:00');
/*!40000 ALTER TABLE `pengembalian_ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Peminjam'),(2,'Dosen'),(3,'Admin SBUM'),(4,'Kepala SBUM'),(5,'PIC Fasilitas'),(6,'Pamdal');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruangan` (
  `id_ruangan` int NOT NULL AUTO_INCREMENT,
  `nama_ruangan` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_gedung` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_ruangan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `lantai` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_ruangan` enum('tersedia','tidak tersedia','maintenance') COLLATE utf8mb4_general_ci DEFAULT 'tersedia',
  `foto_ruangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi_ruangan` text COLLATE utf8mb4_general_ci,
  `pic_id` int DEFAULT NULL,
  PRIMARY KEY (`id_ruangan`),
  UNIQUE KEY `kode_ruangan` (`kode_ruangan`),
  KEY `ruangan_pic_id_foreign` (`pic_id`),
  CONSTRAINT `ruangan_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruangan`
--

LOCK TABLES `ruangan` WRITE;
/*!40000 ALTER TABLE `ruangan` DISABLE KEYS */;
INSERT INTO `ruangan` VALUES (1,'ruangan TA 12.4','Gedung Tower A','RTA001',20,'lantai 12','tersedia','uploads/ruangan/1783866197_lap komputer lain.jpg','Ruangan komputer dan meeting',4),(2,'Gudang','Gedung Utama','GDS001',0,'2','tersedia','uploads/ruangan/1783177166_gudang anjay.jpg','Ini gudang',4);
/*!40000 ALTER TABLE `ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ruangan_id` int DEFAULT NULL,
  `barang_id` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('tersedia','dipinjam','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `peminjaman_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_ruangan_id_foreign` (`ruangan_id`),
  KEY `schedules_peminjaman_id_foreign` (`peminjaman_id`),
  CONSTRAINT `schedules_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE SET NULL,
  CONSTRAINT `schedules_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nim` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nim` (`nim`),
  UNIQUE KEY `nik` (`nik`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Kepala SBUM','K001',NULL,'kepala@sbum.ac.id','$2y$12$g92wLuWJkHt73rXPxKpROOjA15ekTAadBPuT9OhxqgiXlA.a6ueJ.','2026-04-21 20:15:16'),(2,'jamal kopling',NULL,'123232','adminjamal@gmail.com','$2y$12$sHFsWj633KGzwya.4Al.defGHllILQ9EvidVjixwUE3cxFpdVFg3S','2026-06-30 19:22:19'),(3,'Irvan Pasya Ramadhan','4342511030',NULL,'irvanghuforn@gmail.com','$2y$12$R1lti5mBF3OD35LC8AxxtuToKsmxHQymxJlGRukDXnyowzTiaYh0m','2026-06-30 19:24:30'),(4,'Udin Midin',NULL,'2121212','picudin@gmail.com','$2y$12$dx/x4VeTgyQ0C2AW58fkd.dE3igHH3B.1.ON0KWy9BzCd9DACa3mO','2026-06-30 19:36:00'),(5,'Jon Cecep',NULL,'99999','dosencecep@gmail.com','$2y$12$275UVPKoxkjVhdtLPvAQ6uEj.33H.WYKTFhM4Nko4omgrYV4EoAi2','2026-06-30 19:48:03'),(7,'Udin Satoru',NULL,'1212','dosenudin@gmailcom','$2y$12$rz29x.G.5j4dbQjYSsxgX.eDZQEguLBOz.u.HXVTwRmuur9nlMroa','2026-06-30 20:35:16'),(8,'Aham',NULL,'12112','dosenaham@gmail.com','$2y$12$0HRlSHhwbJAgxs5AFcTH0OFCzMYNPZKVdIt3TXzZA5W.WO56nXtaK','2026-06-30 21:03:54'),(9,'Abdul Khodir',NULL,'1122112112','abdulpamdal@gmail.com','$2y$12$8ezkmnDjXPF0/fHUWdgzqOsm9/C5TzPmmkNgHtmcwooZBKp90XwFe','2026-07-01 00:09:34'),(12,'danu','4342511021',NULL,'danudenta@gmail.com','$2y$12$nagE1splakMs2HK7yAcwNukDuAX7APZYYDIoqs7trEjNPhbNsofzy','2026-07-03 21:12:33'),(13,'ayudia','4342511020',NULL,'ayu@gmail.com','$2y$12$XJusvEFZwlZBmA5cxQqtpe3C1jSfY.Z2domiEfdivY9KYPaMxMttm','2026-07-03 21:15:20'),(14,'Moch Azmi Aris Sandita','4342511024',NULL,'masandyta@gmail.com','$2y$12$KhNi98pgCAd2zgD/XUvqxOcADNtbUTFJlLfluVCHQwr1fvp50n3eW','2026-07-05 22:11:38'),(15,'Frishta Riris Annesa Tambunan','4342511025',NULL,'frishtatamb.0686@gmail.com','$2y$12$ktws12xlIxM5Pnei78yykeGXa94Uag6XzlQ9Ybj0ijsSmBCdEpjMy','2026-07-12 21:08:10');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role` (
  `id_user_role` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id_user_role`),
  UNIQUE KEY `user_id` (`user_id`,`role_id`),
  KEY `fk_user_role_role` (`role_id`),
  CONSTRAINT `fk_user_role_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id_role`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_role_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,1,4),(2,2,3),(3,3,1),(4,4,5),(5,5,2),(7,7,2),(8,8,2),(9,9,6),(12,12,1),(13,13,1),(14,14,1),(15,15,1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verifikasi_peminjaman`
--

DROP TABLE IF EXISTS `verifikasi_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verifikasi_peminjaman` (
  `id_verifikasi_peminjaman` int NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int NOT NULL,
  `id_verifikator` int NOT NULL,
  `peran_verifikasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_verifikasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_verifikasi_peminjaman`),
  KEY `fk_verif_peminjaman` (`id_peminjaman`),
  KEY `fk_verif_verifikator` (`id_verifikator`),
  CONSTRAINT `fk_verif_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  CONSTRAINT `fk_verif_verifikator` FOREIGN KEY (`id_verifikator`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verifikasi_peminjaman`
--

LOCK TABLES `verifikasi_peminjaman` WRITE;
/*!40000 ALTER TABLE `verifikasi_peminjaman` DISABLE KEYS */;
INSERT INTO `verifikasi_peminjaman` VALUES (1,1,8,'Dosen','Persetujuan Akademik','disetujui','Diverifikasi oleh Dosen','2026-07-10 22:32:28'),(2,1,4,'PIC Fasilitas','Pemeriksaan Kesiapan','disetujui','Pemeriksaan Kesiapan oleh PIC','2026-07-10 22:33:01'),(3,1,2,'Admin SBUM','Verifikasi Operasional (Atas Nama Kepala)','disetujui','sudah diverifikasi melalui whatsapp','2026-07-10 22:34:47'),(4,1,2,'Kepala SBUM','Persetujuan Akhir (Bypass Admin)','disetujui','Disetujui atas nama Kepala SBUM oleh Admin: sudah diverifikasi melalui whatsapp','2026-07-10 22:34:47'),(5,2,8,'Dosen','Persetujuan Akademik','disetujui','Diverifikasi oleh Dosen','2026-07-12 21:30:14'),(6,3,8,'Dosen','Persetujuan Akademik','disetujui','Diverifikasi oleh Dosen','2026-07-12 21:51:21'),(7,3,4,'PIC Fasilitas','Pemeriksaan Kesiapan','disetujui','Pemeriksaan Kesiapan oleh PIC','2026-07-12 21:52:54'),(8,3,2,'Admin SBUM','Verifikasi Operasional (Atas Nama Kepala)','disetujui','Sudah di acc melalui Whatsapp','2026-07-12 21:55:46'),(9,3,2,'Kepala SBUM','Persetujuan Akhir (Bypass Admin)','disetujui','Disetujui atas nama Kepala SBUM oleh Admin: Sudah di acc melalui Whatsapp','2026-07-12 21:55:46');
/*!40000 ALTER TABLE `verifikasi_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verifikasi_pengembalian`
--

DROP TABLE IF EXISTS `verifikasi_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verifikasi_pengembalian` (
  `id_verifikasi_pengembalian` int NOT NULL AUTO_INCREMENT,
  `id_pengembalian_ruangan` int DEFAULT NULL,
  `id_pengembalian_barang` int DEFAULT NULL,
  `id_verifikator` int NOT NULL,
  `peran_verifikasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `catatan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_verifikasi_pengembalian`),
  KEY `fk_verif_pengembalian_user` (`id_verifikator`),
  KEY `fk_verif_pengembalian_ruangan` (`id_pengembalian_ruangan`),
  KEY `fk_verif_pengembalian_barang` (`id_pengembalian_barang`),
  CONSTRAINT `fk_verif_pengembalian_barang` FOREIGN KEY (`id_pengembalian_barang`) REFERENCES `pengembalian_barang` (`id_pengembalian_barang`),
  CONSTRAINT `fk_verif_pengembalian_ruangan` FOREIGN KEY (`id_pengembalian_ruangan`) REFERENCES `pengembalian_ruangan` (`id_pengembalian_ruangan`),
  CONSTRAINT `fk_verif_pengembalian_user` FOREIGN KEY (`id_verifikator`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verifikasi_pengembalian`
--

LOCK TABLES `verifikasi_pengembalian` WRITE;
/*!40000 ALTER TABLE `verifikasi_pengembalian` DISABLE KEYS */;
INSERT INTO `verifikasi_pengembalian` VALUES (1,1,NULL,4,'PIC Fasilitas','2026-07-10 22:38:50','disetujui','Verifikasi Pengembalian oleh PIC'),(2,1,NULL,2,'Admin SBUM','2026-07-10 22:40:20','disetujui','fasilitan dan ruangan lengkap'),(3,2,NULL,4,'PIC Fasilitas','2026-07-12 22:09:46','disetujui','Verifikasi Pengembalian oleh PIC'),(5,2,NULL,2,'Admin SBUM','2026-07-12 22:15:28','disetujui',NULL);
/*!40000 ALTER TABLE `verifikasi_pengembalian` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-13 13:06:18
