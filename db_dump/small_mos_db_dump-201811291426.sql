-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: mos
-- ------------------------------------------------------
-- Server version	5.6.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `learning_group_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_date` datetime NOT NULL,
  `last_access_date` datetime DEFAULT NULL,
  `living_area_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  KEY `IDX_8D93D64998260155` (`region_id`),
  KEY `IDX_8D93D649DA24DD59` (`learning_group_id`),
  CONSTRAINT `FK_8D93D64998260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  CONSTRAINT `FK_8D93D649DA24DD59` FOREIGN KEY (`learning_group_id`) REFERENCES `learning_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,NULL,NULL,'admin','a:1:{i:0;s:10:\"ROLE_ADMIN\";}','$2y$13$W0LIxZyUcTRbDm3CcAput.2.IxUAGow2YCcIV/kpaLWRIXXuRn09e','Administrator','Administrator',NULL,NULL,NULL,'admin@email.com','2018-11-29 12:25:26',NULL,NULL,NULL),(2,NULL,NULL,'teacher0','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$7wKT3R1FczI2OYxuwQvfF.kCcSNO7a5QXSGJg1qH.83sZYaJuNTWu','Teachername0','Teachersurname0',NULL,NULL,NULL,'teacher0@email.com','2018-11-29 12:25:26',NULL,NULL,NULL),(3,NULL,NULL,'teacher1','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$1j/IGKX7Y0ANs7sk8gzg6u04aaJ6j1qCe1cRWUDzd.UNGK/do5I96','Teachername1','Teachersurname1',NULL,NULL,NULL,'teacher1@email.com','2018-11-29 12:25:27',NULL,NULL,NULL),(4,NULL,NULL,'teacher2','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$jN0tUOC2LBNEkNxCz5UrhOT0cm6wHh1E46LyDed577AC4/dK6NZWi','Teachername2','Teachersurname2',NULL,NULL,NULL,'teacher2@email.com','2018-11-29 12:25:27',NULL,NULL,NULL),(5,NULL,NULL,'teacher3','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$v1dhl.vOOqostbqNv1HqdOqkcxzis6AGTCjrdm54Y5Jx12roJmjBu','Teachername3','Teachersurname3',NULL,NULL,NULL,'teacher3@email.com','2018-11-29 12:25:28',NULL,NULL,NULL),(6,4,1,'participant_sdksjjlm_0','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$STNOMM3T3J9o9htXcjLra.0g4ZeL1l4N4mJDhVwd.N9ju3.B4QZxW','Nasdksjjlm','Susdksjjlm','1974-02-19',NULL,NULL,'participantsdksjjlm@email.com','2018-11-29 12:25:28',NULL,'miestas','moteris'),(7,4,1,'participant_czusvmdh_0','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$fke7Klpv/Q3yPrmrkKoBrOOuWlWCG7EZr4JAxF2VrrMxH/JzNrwwa','Naczusvmdh','Suczusvmdh','1962-05-27',NULL,NULL,'participantczusvmdh@email.com','2018-11-29 12:25:29',NULL,'miestas','vyras'),(8,4,1,'participant_qjtvrbat_0','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$wajINE0RPS7JqhgfcL4seOlmzxq95Kt8Ud7rVCdTShOUA23cX7K8i','Naqjtvrbat','Suqjtvrbat','1970-09-09',NULL,NULL,'participantqjtvrbat@email.com','2018-11-29 12:25:29',NULL,'kaimas','vyras'),(9,4,2,'participant_mkckxvue_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$eOVAMqqambWb/PsYIFQJX.mnIPW1woF/qcHgBNFNRTPu.jw7CazVW','Namkckxvue','Sumkckxvue','1974-03-07',NULL,NULL,'participantmkckxvue@email.com','2018-11-29 12:25:29',NULL,'miestas','moteris'),(10,4,2,'participant_ufcnbxyi_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$NIi4A5Dr.pndApTBJaXl5ObmnqobIFrVNhv47ZrKcsm0CBF8c/s12','Naufcnbxyi','Suufcnbxyi','1973-03-01',NULL,NULL,'participantufcnbxyi@email.com','2018-11-29 12:25:30',NULL,'kaimas','moteris'),(11,4,2,'participant_lmgvywcq_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$J38hQ.C5axrZX/F39c6p/OjmMNZYY1OqsVAWUt1rLgx9CKBuftsBG','Nalmgvywcq','Sulmgvywcq','1945-06-26',NULL,NULL,'participantlmgvywcq@email.com','2018-11-29 12:25:30',NULL,'kaimas','vyras'),(12,4,3,'participant_pdgefqjr_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$SFCAkAIjPfHsqZKE7FWHZ.Ju1Ta2g4yQmoqHEvFqzhM5OuMLrVH4i','Napdgefqjr','Supdgefqjr','1948-03-07',NULL,NULL,'participantpdgefqjr@email.com','2018-11-29 12:25:31',NULL,'kaimas','vyras'),(13,4,3,'participant_flflmdjs_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$0XBYUxHFacYus2ZfSIgriuC/v7qHCNoz0B6Y/cLJ5CamUim3yuW0y','Naflflmdjs','Suflflmdjs','1961-05-17',NULL,NULL,'participantflflmdjs@email.com','2018-11-29 12:25:31',NULL,'miestas','moteris'),(14,4,4,'participant_auarmmcv_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$xYMikem6WKlqCmckv9SgKejtuty1/KHl5KfDUy.Sn2yCZ.n18vo0a','Naauarmmcv','Suauarmmcv','1958-03-14',NULL,NULL,'participantauarmmcv@email.com','2018-11-29 12:25:32',NULL,'kaimas','vyras'),(15,4,4,'participant_tlyrlnyh_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$ztcNHXHswh0kts9/cH1FO.U33D8YgDxojPVqSXaV0irS6HcrqPygm','Natlyrlnyh','Sutlyrlnyh','1954-11-18',NULL,NULL,'participanttlyrlnyh@email.com','2018-11-29 12:25:32',NULL,'miestas','moteris'),(16,4,4,'participant_nsqwthwo_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$rFRk53oMn2Ac8s6oAc3bgeIyvY.IJmBFJicatKYW3iy7e6FFoUj9K','Nansqwthwo','Sunsqwthwo','1959-12-13',NULL,NULL,'participantnsqwthwo@email.com','2018-11-29 12:25:33',NULL,'miestas','vyras');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning_group`
--

DROP TABLE IF EXISTS `learning_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `learning_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `address` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_93520FFF41807E1D` (`teacher_id`),
  CONSTRAINT `FK_93520FFF41807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_group`
--

LOCK TABLES `learning_group` WRITE;
/*!40000 ALTER TABLE `learning_group` DISABLE KEYS */;
INSERT INTO `learning_group` VALUES (1,2,'Savanorių pr. 0, Kaunas'),(2,4,'Savanorių pr. 1, Kaunas'),(3,3,'Savanorių pr. 2, Kaunas'),(4,4,'Savanorių pr. 3, Kaunas');
/*!40000 ALTER TABLE `learning_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_city` tinyint(1) NOT NULL,
  `is_problematic` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `region`
--

LOCK TABLES `region` WRITE;
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
INSERT INTO `region` VALUES (1,'Kaunas',1,1),(2,'Kauno raj.',0,1),(3,'Jonava',1,1),(4,'Jonavos raj.',0,1);
/*!40000 ALTER TABLE `region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_slot`
--

DROP TABLE IF EXISTS `time_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `learning_group_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B3294ADA24DD59` (`learning_group_id`),
  CONSTRAINT `FK_1B3294ADA24DD59` FOREIGN KEY (`learning_group_id`) REFERENCES `learning_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_slot`
--

LOCK TABLES `time_slot` WRITE;
/*!40000 ALTER TABLE `time_slot` DISABLE KEYS */;
INSERT INTO `time_slot` VALUES (1,1,'2018-11-25 00:00:00',90),(2,1,'2018-11-22 00:00:00',90),(3,1,'2018-11-01 00:00:00',90),(4,2,'2018-12-04 00:00:00',90),(5,2,'2018-12-17 00:00:00',90),(6,3,'2018-12-08 00:00:00',90),(7,4,'2018-12-07 00:00:00',90),(8,4,'2018-12-01 00:00:00',90),(9,4,'2018-12-26 00:00:00',90);
/*!40000 ALTER TABLE `time_slot` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-29 14:26:34
