-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: ecf2_juan
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `absences`
--

DROP TABLE IF EXISTS `absences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absences` (
  `absence_id` int NOT NULL AUTO_INCREMENT,
  `absence_date` date NOT NULL,
  `reason` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `justification_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trainee_id` int NOT NULL,
  PRIMARY KEY (`absence_id`),
  KEY `trainee_id` (`trainee_id`),
  CONSTRAINT `absences_ibfk_1` FOREIGN KEY (`trainee_id`) REFERENCES `trainees` (`trainee_id`),
  CONSTRAINT `absences_chk_1` CHECK ((`reason` in (_utf8mb4'maladie',_utf8mb4'sans motif',_utf8mb4'absence légale',_utf8mb4'accident du travail')))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absences`
--

LOCK TABLES `absences` WRITE;
/*!40000 ALTER TABLE `absences` DISABLE KEYS */;
INSERT INTO `absences` VALUES (3,'2026-09-09','sans motif',NULL,4),(5,'2026-09-07','sans motif',NULL,4),(6,'2026-09-03','sans motif',NULL,4),(7,'2026-09-02','sans motif',NULL,4),(8,'2026-09-02','sans motif',NULL,4),(9,'2026-09-10','sans motif','storage/uploads/justifications/6b89f91fc08f75e76950acf4e6c99ffa.pdf',4),(10,'2026-09-10','accident du travail','storage/uploads/justifications/fd50439e8ab145e60d296c97717d732e.pdf',3),(11,'2026-09-11','maladie',NULL,7);
/*!40000 ALTER TABLE `absences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'ADMINAFPA','$2y$10$F/v7xcc5JZ.eQwictAP/G.mJNq2oHrGlDuRm3MekzWdVA3v.cT9mm');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainees`
--

DROP TABLE IF EXISTS `trainees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainees` (
  `trainee_id` int NOT NULL AUTO_INCREMENT,
  `afpa_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `personal_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `professional_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `professional_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `residence` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`trainee_id`),
  UNIQUE KEY `afpa_id` (`afpa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trainees`
--

LOCK TABLES `trainees` WRITE;
/*!40000 ALTER TABLE `trainees` DISABLE KEYS */;
INSERT INTO `trainees` VALUES (1,'22116576','Adila','Kehlaoui','adi.kehlaoui@gmail.com','0645557195','https://adila-k.fr/index.php','hello@adila-k.fr','Bordeaux','1990-12-18','assets/images/trainees/165abb66d281846e77ac9e9dc016336b.webp'),(2,'26020093','Mohammed','Benerroua','benerrouamohammed@gmail.com','0767250170','https://mohammed-benerroua.fr',NULL,'Bordeaux','1990-04-01','assets/images/trainees/1dd7ee46db35e4c23e23ef6235a02579.webp'),(3,'26020095','Ghislène','Bellia','ghislenebellia@gmail.com','0662877894',NULL,NULL,NULL,'2005-08-25','assets/images/trainees/2a69f4478090d1e7fe722dfe755ab5db.webp'),(4,'26020096','Aurèle','Camps','campsaurele@gmail.com','0668368996','https://campsa.fr/','contact@campsa.fr',NULL,'1995-11-26','assets/images/trainees/c26c0d3859147dffd6e331d2d41b9e97.webp'),(5,'26020097','Nelly','Fabre','nelly.fabre@hotmail.fr','0627154096','https://nelly-fabre.fr/','contact@nelly-fabre.fr','Cussac Fort Médoc','1983-10-02','assets/images/trainees/28249226abf9e9f72d2ef2542b86d0d9.webp'),(6,'26020141','Sarah','Casabianca','sarah.casabianca@gmail.com','0683049749',NULL,NULL,NULL,'1996-06-10','assets/images/trainees/6ea38796c0c330e15fd8aee4084a3c1f.webp'),(7,'26020143','Juan','Rojas Cuicas','rjuan3683@gmail.com','0635902566','https://juanrojas.fr/','hello@juanrojas.fr','Bordeaux','2000-08-04','assets/images/trainees/juan-rojas-cuicas.webp'),(8,'26020156','Lucas','Merlet','merletlucas2@gmail.com','0788697051',NULL,'contact@lucas-merlet.fr',NULL,'2002-09-12','assets/images/trainees/267b9827160e470a606ecc80096044dc.webp'),(9,'26020263','Faten','Bannani','belmahriafatenn@gmail.com','0602568388',NULL,NULL,NULL,'1997-05-04','assets/images/trainees/92a49127b023b35c791b968b73eac0e4.webp'),(10,'26020268','Nathanael','Kenzey','nathanael.kenzey@gmail.com','0745165819','https://nathanaelk.fr','contact@nathanaelk.fr','Paris','1998-10-22','assets/images/trainees/b42c8008310169f67609a741b3444e19.webp'),(11,'26020916','Anthony','Lutard','anthony.lutard33@gmail.com','0750862760',NULL,NULL,NULL,'2003-12-03','assets/images/trainees/43dd72b2fc5cd212c9e130b5b202d910.webp'),(12,'26028145','Mélanie','Saez','emel.saez@gmail.com','0760227763',NULL,NULL,NULL,'1986-02-16','assets/images/trainees/8870cceb6497ac82aeb295dcc6a07149.webp');
/*!40000 ALTER TABLE `trainees` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-11 13:09:12
