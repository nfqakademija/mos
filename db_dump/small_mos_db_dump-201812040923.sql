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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_group`
--

LOCK TABLES `learning_group` WRITE;
/*!40000 ALTER TABLE `learning_group` DISABLE KEYS */;
INSERT INTO `learning_group` VALUES (1,3,'Savanorių pr. 0, Kaunas');
INSERT INTO `learning_group` VALUES (2,6,'Savanorių pr. 1, Kaunas');
INSERT INTO `learning_group` VALUES (3,3,'Savanorių pr. 2, Kaunas');
INSERT INTO `learning_group` VALUES (4,5,'Savanorių pr. 3, Kaunas');
INSERT INTO `learning_group` VALUES (5,6,'Savanorių pr. 4, Kaunas');
INSERT INTO `learning_group` VALUES (6,3,'Savanorių pr. 5, Kaunas');
/*!40000 ALTER TABLE `learning_group` ENABLE KEYS */;
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
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B3294ADA24DD59` (`learning_group_id`),
  CONSTRAINT `FK_1B3294ADA24DD59` FOREIGN KEY (`learning_group_id`) REFERENCES `learning_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_slot`
--

LOCK TABLES `time_slot` WRITE;
/*!40000 ALTER TABLE `time_slot` DISABLE KEYS */;
INSERT INTO `time_slot` VALUES (1,1,'2018-11-03','10:30:00',90);
INSERT INTO `time_slot` VALUES (2,1,'2018-11-29','10:30:00',90);
INSERT INTO `time_slot` VALUES (3,1,'2018-11-03','10:30:00',90);
INSERT INTO `time_slot` VALUES (4,2,'2018-12-03','10:30:00',90);
INSERT INTO `time_slot` VALUES (5,2,'2018-12-21','10:30:00',90);
INSERT INTO `time_slot` VALUES (6,2,'2018-12-12','10:30:00',90);
INSERT INTO `time_slot` VALUES (7,3,'2018-12-06','10:30:00',90);
INSERT INTO `time_slot` VALUES (8,3,'2018-12-07','10:30:00',90);
INSERT INTO `time_slot` VALUES (9,3,'2018-12-21','10:30:00',90);
INSERT INTO `time_slot` VALUES (10,4,'2018-12-01','10:30:00',90);
INSERT INTO `time_slot` VALUES (11,5,'2018-11-11','10:30:00',90);
INSERT INTO `time_slot` VALUES (12,5,'2018-11-05','10:30:00',90);
INSERT INTO `time_slot` VALUES (13,5,'2018-11-03','10:30:00',90);
INSERT INTO `time_slot` VALUES (14,6,'2018-12-24','10:30:00',90);
INSERT INTO `time_slot` VALUES (15,6,'2018-12-08','10:30:00',90);
INSERT INTO `time_slot` VALUES (16,6,'2018-12-21','10:30:00',90);
/*!40000 ALTER TABLE `time_slot` ENABLE KEYS */;
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
  `is_problematic` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `region`
--

LOCK TABLES `region` WRITE;
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
INSERT INTO `region` VALUES (1,'Akmenės raj.',0);
INSERT INTO `region` VALUES (2,'Alytaus m.',0);
INSERT INTO `region` VALUES (3,'Alytaus raj.',1);
INSERT INTO `region` VALUES (4,'Anykščių raj.',0);
INSERT INTO `region` VALUES (5,'Birštono m.',0);
INSERT INTO `region` VALUES (6,'Biržų raj.',0);
INSERT INTO `region` VALUES (7,'Druskininkų m.',0);
INSERT INTO `region` VALUES (8,'Elektrėnų m.',0);
/*!40000 ALTER TABLE `region` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,NULL,NULL,'manager','a:1:{i:0;s:10:\"ROLE_ADMIN\";}','$2y$13$8AYwiGxF6fTtU4SmGwZbWOQR36vL/hpDo1guwv8acCD6XLYi7vou6','Project','Manager',NULL,NULL,NULL,'manager@email.com','2018-12-04 07:20:44',NULL,NULL,NULL);
INSERT INTO `user` VALUES (2,NULL,NULL,'supervisor','a:1:{i:0;s:15:\"ROLE_SUPERVISOR\";}','$2y$13$ZpNSYKkmgt6bdmqMZiJ9Le/1.qYsCi/1N4Yr25Os.BIY4b3xKoJZ.','Project','Supervisor',NULL,NULL,NULL,'supervisor@email.com','2018-12-04 07:20:44',NULL,NULL,NULL);
INSERT INTO `user` VALUES (3,NULL,NULL,'teacher0','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$/9TtAwjgw/j7SoVMquQl4u6T4hAaenoykhkEvVUOOUzh0GuedaODq','Teachername0','Teachersurname0',NULL,NULL,NULL,'teacher0@email.com','2018-12-04 07:20:45',NULL,NULL,NULL);
INSERT INTO `user` VALUES (4,NULL,NULL,'teacher1','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$SLGt2XzOHortGgO0cg7bP.3uQITVy4VeUSSAuIp3R33FHcdgMGcEm','Teachername1','Teachersurname1',NULL,NULL,NULL,'teacher1@email.com','2018-12-04 07:20:45',NULL,NULL,NULL);
INSERT INTO `user` VALUES (5,NULL,NULL,'teacher2','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$1kbxEwhWOSDhrwBtrggd4OnjtSStzAPoXSvOT5HP8v.u.hJbiqPHG','Teachername2','Teachersurname2',NULL,NULL,NULL,'teacher2@email.com','2018-12-04 07:20:46',NULL,NULL,NULL);
INSERT INTO `user` VALUES (6,NULL,NULL,'teacher3','a:1:{i:0;s:12:\"ROLE_TEACHER\";}','$2y$13$rFI55qvZ5adZf/2qN3l1FuOsZ0HcncOD2z1rA5fEQoLBpVJbB6qua','Teachername3','Teachersurname3',NULL,NULL,NULL,'teacher3@email.com','2018-12-04 07:20:46',NULL,NULL,NULL);
INSERT INTO `user` VALUES (7,7,1,'participant_iefgeypr_0','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$RbliGG/wLldzdqFXoL/GVuSiD3/bsfSSQXeEE.wa5j31pLGqixsOG','Iefgeypr','Jshba','1968-07-15','Liepų g. 0','81707373','participantiefgeypr@email.com','2018-12-04 07:20:47',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (8,7,1,'participant_plovncij_0','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$F.zcMdK/4pyXWh0FbANL5epRMwSENatTlNmCS86zwM31uvJ5xyNlK','Plovncij','Tmmco','1945-09-13','Liepų g. 0','80885526','participantplovncij@email.com','2018-12-04 07:20:47',NULL,'kaimas','vyras');
INSERT INTO `user` VALUES (9,4,2,'participant_gbdihafy_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$PIjx4pXjExtPc1j1uIXmDOJMvO0G7voYoLYY5Z7p4htYkiAVsrkhS','Gbdihafy','Zvjjh','1948-06-04','Liepų g. 1','88550615','participantgbdihafy@email.com','2018-12-04 07:20:48',NULL,'kaimas','moteris');
INSERT INTO `user` VALUES (10,4,2,'participant_jxqcqxga_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$Pz7CbInu4o1BJZyw5IRXXeGEJIyEZGwAsx93b62vfMBjn1xa54iB2','Jxqcqxga','Tflls','1954-05-15','Liepų g. 1','82323789','participantjxqcqxga@email.com','2018-12-04 07:20:48',NULL,'kaimas','moteris');
INSERT INTO `user` VALUES (11,4,2,'participant_oveiaksa_1','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$LuZ/TeDxz/gqpSR89Wfb9euMH5P9Qi7npa2UcG85sPjKEOLFE0HrG','Oveiaksa','Ialfe','1973-02-03','Liepų g. 1','83752016','participantoveiaksa@email.com','2018-12-04 07:20:48',NULL,'kaimas','moteris');
INSERT INTO `user` VALUES (12,8,3,'participant_famfmlkn_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$PS4g4.y2ZsaTzoiMNn0/2uULWtm5SE/duok5MtUHi7RqnCOvcZD6S','Famfmlkn','Rhhii','1956-01-03','Liepų g. 2','81141360','participantfamfmlkn@email.com','2018-12-04 07:20:49',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (13,8,3,'participant_imjybmcd_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$HP7u.C8tTywptZPnBgyHg.yP5C5GqODA6kF1LHCS6Hye25QLTy9Se','Imjybmcd','Xdnjq','1945-05-06','Liepų g. 2','82017092','participantimjybmcd@email.com','2018-12-04 07:20:49',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (14,8,3,'participant_udyzalnb_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$zI118B6fNLMHTMeAjBCEPej3mKeZqFpMvl6kdkVZ7S1/Pqch2WCFK','Udyzalnb','Gtzpb','1959-07-02','Liepų g. 2','84002203','participantudyzalnb@email.com','2018-12-04 07:20:50',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (15,8,3,'participant_miljercw_2','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$SDSuoHGCj.et4f8oLDA05.k/zsaOYCRG/eb7GJRXWb/gGrwo26HYa','Miljercw','Vgayq','1947-01-03','Liepų g. 2','80689085','participantmiljercw@email.com','2018-12-04 07:20:50',NULL,'miestas','vyras');
INSERT INTO `user` VALUES (16,6,4,'participant_kahykhjr_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$e.oe7Nb/NcfFP.WJsBcsr.f7ugswPPMkmRhrNSjnPs6dqnqteXMvG','Kahykhjr','Ezpox','1975-11-17','Liepų g. 3','81760416','participantkahykhjr@email.com','2018-12-04 07:20:51',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (17,6,4,'participant_florqlpo_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$22DukUiYNotkFac19CvIa.Z.RBOJdZ7Q3reRLAXPK4rbedUMjt0jG','Florqlpo','Tsnpd','1968-10-23','Liepų g. 3','82449403','participantflorqlpo@email.com','2018-12-04 07:20:51',NULL,'miestas','vyras');
INSERT INTO `user` VALUES (18,6,4,'participant_uvkapwyn_3','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$6tfocPD0mmc9XZZjEWX4Y.Y18J8z9gC5BuIpUd6RGzsIoythBQ6i2','Uvkapwyn','Ubvhf','1970-01-08','Liepų g. 3','84756754','participantuvkapwyn@email.com','2018-12-04 07:20:52',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (19,5,5,'participant_gttddrxa_4','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$riTKEEtEFUXD9BBOIh8Rh.FVxh5BML.J2ou.y5fUjORvs4abhz2tm','Gttddrxa','Pgoab','1953-09-23','Liepų g. 4','81471885','participantgttddrxa@email.com','2018-12-04 07:20:52',NULL,'kaimas','moteris');
INSERT INTO `user` VALUES (20,5,5,'participant_mjgenkta_4','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$KRcSAZ5Cf2m4vUg8jpP5vODGVOwD7gyjn18u.fJjP.lI/lomzwZ2G','Mjgenkta','Ktpob','1945-09-25','Liepų g. 4','85298870','participantmjgenkta@email.com','2018-12-04 07:20:52',NULL,'miestas','moteris');
INSERT INTO `user` VALUES (21,5,6,'participant_dibmnyie_5','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$PFFMVJTuRmYpGOyZo35QC.eyfDYKMQiS2yjFHYsoKFKJZoGAlzucy','Dibmnyie','Crmnw','1958-03-18','Liepų g. 5','80587808','participantdibmnyie@email.com','2018-12-04 07:20:53',NULL,'miestas','vyras');
INSERT INTO `user` VALUES (22,5,6,'participant_clhyjcvp_5','a:1:{i:0;s:16:\"ROLE_PARTICIPANT\";}','$2y$13$adegmTOkIqAF4V4MBBrEUOgtxexWRQS4d9bGUxKVAyMrsrqgeTz5q','Clhyjcvp','Brwky','1975-03-07','Liepų g. 5','83134387','participantclhyjcvp@email.com','2018-12-04 07:20:53',NULL,'miestas','moteris');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-04  9:23:31
