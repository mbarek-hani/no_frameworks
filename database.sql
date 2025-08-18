/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: web_project
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES
(1,'CreateUser','Add new user'),
(2,'ReadUser','Retrieve user information'),
(3,'UpdateUser','Edit user information'),
(4,'DeleteUser','Mark user as deleted'),
(5,'ListUsers','Retrieve list of all users'),
(6,'CreateRole','Add new role'),
(7,'ReadRole','Retrieve role information'),
(8,'UpdateRole','Edit role information'),
(9,'DeleteRole','Mark role as deleted'),
(10,'ListRoles','Retrieve list of all roles'),
(11,'CreateAction','Add new action'),
(12,'ReadAction','Retrieve action information'),
(13,'UpdateAction','Edit action information'),
(14,'DeleteAction','Mark action as deleted'),
(15,'ListActions','Retrieve list of all actions');
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Administrator','Administrator role with all permissions'),
(2,'User','Read only role for regular users'),
(4,'Guest','Login only role');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `first_name` (`first_name`,`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(3,'acrighton','Ashlen','Crighton','acrighton2@nationalgeographic.com'),
(4,'fclaibourn3','Frederik','Claibourn','fclaibourn3@goo.ne.jp'),
(5,'lescale4','Luciano','Escale','lescale4@ft.com'),
(6,'viking15','Viking','Hani','hani-viking@email.com'),
(8,'kmorris7','Kain','Morris','kmorris7@kickstarter.com'),
(9,'mianetti8','Mellisa','Ianetti','mianetti8@answers.com'),
(11,'rlubea','Ritchie','Lube','rlubea@elpais.com'),
(12,'dcianellib','Diane-marie','Cianelli','dcianellib@archive.org'),
(13,'astortonc','Aleda','Storton','astortonc@hp.com'),
(14,'rbetoniad','Roberto','Betonia','rbetoniad@w3.org'),
(15,'lodorane','Lenore','O\'Doran','lodorane@fastcompany.com'),
(16,'mpriestmanf','Mindy','Priestman','mpriestmanf@google.fr'),
(17,'liong','Leta','Ion','liong@wunderground.com'),
(18,'jmckaneh','Jeddy','McKane','jmckaneh@so-net.ne.jp'),
(19,'bdewhursti','Barrie','Dewhurst','bdewhursti@weather.com'),
(20,'bhavercroftj','Britta','Havercroft','bhavercroftj@google.ca'),
(21,'bewensonk','Bondon','Ewenson','bewensonk@dell.com'),
(22,'solynnl','Sharlene','O\'Lynn','solynnl@about.com'),
(23,'iibbittm','Ibbie','Ibbitt','iibbittm@nbcnews.com'),
(24,'nmarcussenn','Nichols','Marcussen','nmarcussenn@wufoo.com'),
(25,'ccartmelo','Casar','Cartmel','ccartmelo@bloglines.com'),
(26,'usapsonp','Uriel','Sapson','usapsonp@slashdot.org'),
(27,'lranniganq','Leoine','Rannigan','lranniganq@mtv.com'),
(28,'prushtonr','Patrice','Rushton','prushtonr@illinois.edu'),
(29,'fkenns','Fay','Kenn','fkenns@rediff.com'),
(30,'cbielfeltt','Carson','Bielfelt','cbielfeltt@exblog.jp'),
(31,'bletchfordu','Bibby','Letchford','bletchfordu@desdev.cn'),
(32,'jbethellv','Jacynth','Bethell','jbethellv@reference.com'),
(33,'avenardw','Abagail','Venard','avenardw@wikimedia.org'),
(34,'mnationx','Mikkel','Nation','mnationx@cnbc.com'),
(35,'dmitroy','Dianne','Mitro','dmitroy@pinterest.com'),
(36,'cdowmanz','Conni','Dowman','cdowmanz@drupal.org'),
(37,'pgutridge10','Pablo','Gutridge','pgutridge10@last.fm'),
(38,'gshire11','Gretna','Shire','gshire11@answers.com'),
(39,'bsandiland12','Broderick','Sandiland','bsandiland12@360.cn'),
(40,'lnelles13','Lina','Nelles','lnelles13@furl.net'),
(41,'rverma14','Rosemonde','Verma','rverma14@soundcloud.com'),
(42,'nwaterstone15','Nita','Waterstone','nwaterstone15@slashdot.org'),
(43,'rescritt16','Richmond','Escritt','rescritt16@ucoz.com'),
(44,'rbarwell17','Roberta','Barwell','rbarwell17@japanpost.jp'),
(45,'gfrankema18','Ginnie','Frankema','gfrankema18@techcrunch.com'),
(46,'amcgaw19','Amalea','McGaw','amcgaw19@tinypic.com'),
(47,'fkermeen1a','Frannie','Kermeen','fkermeen1a@cbslocal.com'),
(48,'htysack1b','Herve','Tysack','htysack1b@ifeng.com'),
(49,'cmcmenamin1c','Carlie','McMenamin','cmcmenamin1c@google.de'),
(50,'adalessio1d','Amalle','D\'Alessio','adalessio1d@exblog.jp'),
(51,'ptwiddy1e','Phineas','Twiddy','ptwiddy1e@ask.com'),
(52,'fjewson1f','Felicle','Jewson','fjewson1f@ucoz.ru'),
(53,'plidstone1g','Parker','Lidstone','plidstone1g@bbc.co.uk'),
(54,'dfairbourn1h','Dill','Fairbourn','dfairbourn1h@issuu.com'),
(55,'grutley1i','Giacopo','Rutley','grutley1i@jalbum.net'),
(56,'fcowap1j','Fayth','Cowap','fcowap1j@chicagotribune.com'),
(57,'mhandlin1k','Morgun','Handlin','mhandlin1k@blinklist.com'),
(58,'ncrew1l','Norry','Crew','ncrew1l@aboutads.info'),
(59,'sellingsworth1m','Serge','Ellingsworth','sellingsworth1m@instagram.com'),
(60,'wjuzek1n','Wyndham','Juzek','wjuzek1n@gizmodo.com'),
(61,'fgatecliff1o','Fina','Gatecliff','fgatecliff1o@hostgator.com'),
(62,'jlowndsborough1p','Judah','Lowndsborough','jlowndsborough1p@jigsy.com'),
(63,'sheam1q','Sapphira','Heam','sheam1q@techcrunch.com'),
(64,'stidcombe1r','Stacy','Tidcombe','stidcombe1r@toplist.cz'),
(65,'iantonovic1s','Isaiah','Antonovic','iantonovic1s@tripod.com'),
(66,'estlouis1t','Easter','St Louis','estlouis1t@gizmodo.com'),
(67,'ygosselin1u','Yoko','Gosselin','ygosselin1u@loc.gov'),
(68,'fschultes1v','Felipa','Schultes','fschultes1v@cargocollective.com'),
(69,'gtourner1w','Giordano','Tourner','gtourner1w@yelp.com'),
(70,'kseago1x','Kellia','Seago','kseago1x@prlog.org'),
(71,'rantoniak1y','Rodi','Antoniak','rantoniak1y@mapquest.com'),
(72,'phalbeard1z','Payton','Halbeard','phalbeard1z@columbia.edu'),
(73,'ibelliss20','Iris','Belliss','ibelliss20@bloomberg.com'),
(74,'nhenricsson21','Noreen','Henricsson','nhenricsson21@earthlink.net'),
(75,'rorrice22','Riley','Orrice','rorrice22@techcrunch.com'),
(76,'ksimpkiss23','Kean','Simpkiss','ksimpkiss23@feedburner.com'),
(77,'rlangland24','Regan','Langland','rlangland24@comcast.net'),
(78,'pcrallan25','Pearl','Crallan','pcrallan25@exblog.jp'),
(79,'kgustus26','Karilynn','Gustus','kgustus26@people.com.cn'),
(80,'mpigot27','Mara','Pigot','mpigot27@histats.com'),
(81,'isanches28','Inger','Sanches','isanches28@pbs.org'),
(82,'mparkes29','Mason','Parkes','mparkes29@biblegateway.com'),
(83,'wdodell2a','Wilmette','Dodell','wdodell2a@theguardian.com'),
(84,'jjarmyn2b','Joannes','Jarmyn','jjarmyn2b@hibu.com'),
(85,'jderdes2c','Joey','Derdes','jderdes2c@xinhuanet.com'),
(86,'wkenderdine2d','Wit','Kenderdine','wkenderdine2d@sfgate.com'),
(87,'emacscherie2e','Enrika','MacScherie','emacscherie2e@wikipedia.org'),
(88,'kagostini2f','Katherine','Agostini','kagostini2f@yahoo.co.jp'),
(89,'ttabert2g','Teresina','Tabert','ttabert2g@cbslocal.com'),
(90,'fyounge2h','Franciska','Younge','fyounge2h@constantcontact.com'),
(91,'pbraunroth2i','Paton','Braunroth','pbraunroth2i@netvibes.com'),
(92,'apalfrie2j','Audrie','Palfrie','apalfrie2j@ebay.co.uk'),
(93,'fdcosta2k','Foss','D\'Costa','fdcosta2k@mayoclinic.com'),
(94,'dduckham2l','Denys','Duckham','dduckham2l@so-net.ne.jp'),
(95,'bmurname2m','Batsheva','Murname','bmurname2m@netlog.com'),
(96,'hbrodway2n','Hubert','Brodway','hbrodway2n@pcworld.com'),
(97,'jgrunder2o','Jasen','Grunder','jgrunder2o@cafepress.com'),
(98,'dbrachell2p','Darb','Brachell','dbrachell2p@gizmodo.com'),
(99,'cmichael2q','Carolina','Michael','cmichael2q@webnode.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_roles`
--

DROP TABLE IF EXISTS `users_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_roles` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_roles`
--

LOCK TABLES `users_roles` WRITE;
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-18  2:30:50
