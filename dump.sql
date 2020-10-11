CREATE DATABASE  IF NOT EXISTS `wordpress` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `wordpress`;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: wordpress
-- ------------------------------------------------------
-- Server version	5.7.30

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
-- Table structure for table `wp_actionscheduler_actions`
--

DROP TABLE IF EXISTS `wp_actionscheduler_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `scheduled_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `args` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `schedule` longtext COLLATE utf8mb4_unicode_520_ci,
  `group_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `attempts` int(11) NOT NULL DEFAULT '0',
  `last_attempt_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `extended_args` varchar(8000) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `hook` (`hook`),
  KEY `status` (`status`),
  KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  KEY `args` (`args`),
  KEY `group_id` (`group_id`),
  KEY `last_attempt_gmt` (`last_attempt_gmt`),
  KEY `claim_id` (`claim_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_actions`
--

LOCK TABLES `wp_actionscheduler_actions` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_actions` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_actions` VALUES (42,'action_scheduler/migration_hook','complete','2020-07-08 15:36:40','2020-07-08 17:36:40','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1594222600;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1594222600;}',1,1,'2020-08-10 05:58:02','2020-08-10 07:58:02',0,NULL);
/*!40000 ALTER TABLE `wp_actionscheduler_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_claims`
--

DROP TABLE IF EXISTS `wp_actionscheduler_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_claims` (
  `claim_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`claim_id`),
  KEY `date_created_gmt` (`date_created_gmt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_claims`
--

LOCK TABLES `wp_actionscheduler_claims` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_actionscheduler_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_groups`
--

DROP TABLE IF EXISTS `wp_actionscheduler_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_groups` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_groups`
--

LOCK TABLES `wp_actionscheduler_groups` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_groups` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_groups` VALUES (1,'action-scheduler-migration');
/*!40000 ALTER TABLE `wp_actionscheduler_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_logs`
--

DROP TABLE IF EXISTS `wp_actionscheduler_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_logs` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` bigint(20) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `log_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`),
  KEY `action_id` (`action_id`),
  KEY `log_date_gmt` (`log_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_logs`
--

LOCK TABLES `wp_actionscheduler_logs` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_logs` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_logs` VALUES (7,42,'action created','2020-07-08 15:35:40','2020-07-08 17:35:40'),(8,42,'action started via WP Cron','2020-08-10 05:58:01','2020-08-10 07:58:01'),(9,42,'action complete via WP Cron','2020-08-10 05:58:02','2020-08-10 07:58:02');
/*!40000 ALTER TABLE `wp_actionscheduler_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_commentmeta`
--

DROP TABLE IF EXISTS `wp_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_commentmeta`
--

LOCK TABLES `wp_commentmeta` WRITE;
/*!40000 ALTER TABLE `wp_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10)),
  KEY `woo_idx_comment_type` (`comment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
INSERT INTO `wp_comments` VALUES (1,1,'A WordPress Commenter','wapuu@wordpress.example','https://wordpress.org/','','2019-01-14 21:43:16','2019-01-14 21:43:16','Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href=\"https://gravatar.com\">Gravatar</a>.',0,'1','','',0,0);
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_appointments`
--

DROP TABLE IF EXISTS `wp_ea_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `worker` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `status` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `price` decimal(10,2) DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `session` varchar(32) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_location` (`location`),
  KEY `appointments_service` (`service`),
  KEY `appointments_worker` (`worker`),
  CONSTRAINT `wp_ea_appointments_ibfk_1` FOREIGN KEY (`location`) REFERENCES `wp_ea_locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wp_ea_appointments_ibfk_2` FOREIGN KEY (`service`) REFERENCES `wp_ea_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wp_ea_appointments_ibfk_3` FOREIGN KEY (`worker`) REFERENCES `wp_ea_staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_appointments`
--

LOCK TABLES `wp_ea_appointments` WRITE;
/*!40000 ALTER TABLE `wp_ea_appointments` DISABLE KEYS */;
INSERT INTO `wp_ea_appointments` VALUES (1,1,1,1,NULL,NULL,NULL,'2019-08-23','14:00:00','15:00:00','2019-08-23',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(2,1,1,1,NULL,NULL,NULL,'2019-02-15','11:30:00','12:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(3,1,1,1,NULL,NULL,NULL,'2019-02-15','12:00:00','12:30:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(4,1,1,1,NULL,NULL,NULL,'2019-02-15','12:00:00','12:30:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(5,1,1,1,NULL,NULL,NULL,'2019-02-15','12:00:00','12:30:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(6,1,1,1,NULL,NULL,NULL,'2019-02-15','12:00:00','12:30:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(7,1,1,1,NULL,NULL,NULL,'2019-02-15','12:00:00','12:30:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(8,1,1,1,NULL,NULL,NULL,'2019-02-15','12:30:00','13:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(9,1,1,1,NULL,NULL,NULL,'2019-02-15','12:30:00','13:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(10,1,1,1,NULL,NULL,NULL,'2019-02-15','12:30:00','13:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(11,1,1,1,NULL,NULL,NULL,'2019-02-15','12:30:00','13:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(12,1,1,1,NULL,NULL,NULL,'2019-02-15','12:30:00','13:00:00','2019-02-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(13,1,1,1,NULL,NULL,NULL,'2019-02-16','11:30:00','12:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(14,1,1,1,NULL,NULL,NULL,'2019-02-16','12:00:00','12:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(15,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(16,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(17,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(18,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(19,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(20,1,1,1,NULL,NULL,NULL,'2019-02-16','12:30:00','13:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(21,1,1,1,NULL,NULL,NULL,'2019-02-16','13:00:00','13:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(22,1,1,1,NULL,NULL,NULL,'2019-02-16','13:00:00','13:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(23,1,1,1,NULL,NULL,NULL,'2019-02-16','13:00:00','13:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(24,1,1,1,NULL,NULL,NULL,'2019-02-16','13:00:00','13:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(25,1,1,1,NULL,NULL,NULL,'2019-02-16','13:00:00','13:30:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(26,1,1,1,NULL,NULL,NULL,'2019-02-16','13:30:00','14:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(27,1,1,1,NULL,NULL,NULL,'2019-02-16','13:30:00','14:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(28,1,1,1,NULL,NULL,NULL,'2019-02-16','13:30:00','14:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(29,1,1,1,NULL,NULL,NULL,'2019-02-16','13:30:00','14:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(30,1,1,1,NULL,NULL,NULL,'2019-02-16','13:30:00','14:00:00','2019-02-16',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(31,1,1,1,NULL,NULL,NULL,'2019-02-18','12:00:00','12:30:00','2019-02-18',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(32,1,1,1,NULL,NULL,NULL,'2019-02-19','10:00:00','10:30:00','2019-02-19',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(33,1,1,1,NULL,NULL,NULL,'2019-02-20','08:30:00','09:00:00','2019-02-20',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(34,1,1,1,NULL,NULL,NULL,'2019-02-20','13:00:00','13:30:00','2019-02-20',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(35,1,1,1,NULL,NULL,NULL,'2019-02-20','17:30:00','18:00:00','2019-02-20',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(36,1,1,1,NULL,NULL,NULL,'2019-02-21','11:00:00','11:30:00','2019-02-21',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(37,1,1,1,NULL,NULL,NULL,'2019-02-28','15:00:00','15:30:00','2019-02-28',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(38,1,1,1,NULL,NULL,NULL,'2019-03-21','10:00:00','10:30:00','2019-03-21',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(39,1,1,1,NULL,NULL,NULL,'2019-04-15','17:00:00','17:30:00','2019-04-15',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(40,1,1,1,NULL,NULL,NULL,'2019-06-17','17:00:00','17:30:00','2019-06-17',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(41,1,1,1,NULL,NULL,NULL,'2019-08-12','17:00:00','17:30:00','2019-08-12',NULL,'confirmed',NULL,'2019-02-15 08:14:54',10.00,NULL,NULL),(42,1,1,1,NULL,NULL,NULL,'2019-02-27','12:00:00','13:00:00','2019-02-27',NULL,'pending',NULL,'2019-02-25 21:40:06',10.00,'172.19.0.1',''),(43,1,1,1,NULL,NULL,NULL,'2019-02-28','16:00:00','17:00:00','2019-02-28',NULL,'pending',NULL,'2019-02-25 21:40:13',10.00,'172.19.0.1',''),(44,1,1,1,NULL,NULL,NULL,'2019-03-21','12:00:00','13:00:00','2019-03-21',NULL,'pending',NULL,'2019-03-06 21:36:33',10.00,'172.19.0.1',''),(45,1,1,1,NULL,NULL,NULL,'2019-03-14','12:00:00','13:00:00','2019-03-14',NULL,'pending',NULL,'2019-03-06 21:41:06',10.00,'172.19.0.1',''),(46,1,1,1,NULL,NULL,NULL,'2019-03-13','11:00:00','12:00:00','2019-03-13',NULL,'pending',NULL,'2019-03-06 21:46:43',10.00,'172.19.0.1',''),(47,1,1,1,NULL,NULL,NULL,'2019-03-13','12:00:00','13:00:00','2019-03-13',NULL,'pending',NULL,'2019-03-06 21:56:56',10.00,'172.19.0.1',''),(48,1,1,1,NULL,NULL,NULL,'2019-03-19','11:00:00','12:00:00','2019-03-19',NULL,'pending',NULL,'2019-03-06 22:07:18',10.00,'172.19.0.1',''),(49,1,1,1,NULL,NULL,NULL,'2019-03-19','12:00:00','13:00:00','2019-03-19',NULL,'pending',NULL,'2019-03-06 22:07:36',10.00,'172.19.0.1',''),(50,1,1,1,NULL,NULL,NULL,'2019-03-21','11:00:00','12:00:00','2019-03-21',NULL,'pending',NULL,'2019-03-06 22:12:02',10.00,'172.19.0.1',''),(51,1,1,1,NULL,NULL,NULL,'2019-03-15','13:00:00','14:00:00','2019-03-15',NULL,'pending',NULL,'2019-03-06 22:20:41',10.00,'172.19.0.1',''),(52,1,1,1,NULL,NULL,NULL,'2019-03-20','12:00:00','13:00:00','2019-03-20',NULL,'pending',NULL,'2019-03-17 21:24:58',10.00,'172.19.0.1',''),(53,1,1,1,NULL,NULL,NULL,'2019-03-21','15:00:00','16:00:00','2019-03-21',NULL,'pending',NULL,'2019-03-17 21:25:28',10.00,'172.19.0.1',''),(54,1,1,1,NULL,NULL,NULL,'2019-03-28','12:00:00','13:00:00','2019-03-28',NULL,'pending',NULL,'2019-03-17 21:26:49',10.00,'172.19.0.1',''),(55,1,1,1,NULL,NULL,NULL,'2019-03-31','15:00:00','16:00:00','2019-03-31',NULL,'pending',NULL,'2019-03-31 12:13:00',10.00,'172.19.0.1',''),(56,1,1,1,NULL,NULL,NULL,'2019-03-31','13:00:00','14:00:00','2019-03-31',NULL,'pending',NULL,'2019-03-31 12:13:52',10.00,'172.19.0.1',''),(57,1,1,1,NULL,NULL,NULL,'2019-05-24','12:00:00','13:00:00','2019-05-24',NULL,'pending',NULL,'2019-05-05 20:12:53',10.00,'172.19.0.1',''),(58,1,1,1,NULL,NULL,NULL,'2019-05-15','11:00:00','12:00:00','2019-05-15',NULL,'confirmed',NULL,'2019-05-12 20:18:40',10.00,'172.19.0.1',''),(60,1,1,1,NULL,NULL,NULL,'2020-05-29','16:00:00','17:00:00','2020-05-29',NULL,'pending',NULL,'2020-05-26 21:33:32',10.00,'172.18.0.1',''),(61,1,1,1,NULL,NULL,NULL,'2020-05-29','17:00:00','18:00:00','2020-05-29',NULL,'pending',NULL,'2020-05-26 21:39:04',10.00,'172.18.0.1',''),(62,1,1,1,NULL,NULL,NULL,'2020-05-30','17:00:00','18:00:00','2020-05-30',NULL,'pending',NULL,'2020-05-26 21:40:30',10.00,'172.18.0.1',''),(63,1,1,1,NULL,NULL,NULL,'2020-05-29','18:00:00','19:00:00','2020-05-29',NULL,'confirmed',NULL,'2020-05-28 18:57:11',10.00,'172.18.0.1',''),(64,1,1,1,NULL,NULL,NULL,'2020-05-30','11:00:00','12:00:00','2020-05-30',NULL,'pending',NULL,'2020-05-28 20:14:37',10.00,'172.18.0.1',''),(65,1,1,1,NULL,NULL,NULL,'2020-10-06','18:00:00','19:00:00','2020-10-06',NULL,'pending',NULL,'2020-06-01 16:28:20',10.00,'172.18.0.1',''),(66,1,1,1,NULL,NULL,NULL,'2020-06-01','19:00:00','20:00:00','2020-06-01',NULL,'pending',NULL,'2020-06-01 16:41:36',10.00,'172.18.0.1',''),(67,1,1,1,NULL,NULL,NULL,'2020-06-01','17:00:00','18:00:00','2020-06-01',NULL,'pending',NULL,'2020-06-01 16:41:44',10.00,'172.18.0.1',''),(68,1,1,1,NULL,NULL,NULL,'2020-06-02','10:00:00','11:00:00','2020-06-02',NULL,'pending',NULL,'2020-06-01 16:41:57',10.00,'172.18.0.1',''),(69,1,1,1,NULL,NULL,NULL,'2020-06-02','11:00:00','12:00:00','2020-06-02',NULL,'pending',NULL,'2020-06-01 16:42:04',10.00,'172.18.0.1',''),(70,1,1,1,NULL,NULL,NULL,'2020-06-02','15:00:00','16:00:00','2020-06-02',NULL,'pending',NULL,'2020-06-01 16:42:13',10.00,'172.18.0.1',''),(71,2,1,1,NULL,NULL,NULL,'2020-06-18','12:00:00','13:00:00','2020-06-18',NULL,'pending',NULL,'2020-06-13 15:04:08',10.00,'172.18.0.1',''),(72,2,1,1,NULL,NULL,NULL,'2020-06-19','16:00:00','17:00:00','2020-06-19',NULL,'canceled',NULL,'2020-06-13 15:05:00',10.00,'172.18.0.1',''),(73,2,1,1,NULL,NULL,NULL,'2020-06-18','11:00:00','12:00:00','2020-06-18',NULL,'pending',NULL,'2020-06-14 20:59:43',10.00,'172.18.0.1',''),(74,1,1,1,NULL,NULL,NULL,'2020-06-19','12:00:00','13:00:00','2020-06-19',NULL,'confirmed',NULL,'2020-06-16 19:11:03',10.00,'172.18.0.1',''),(75,1,1,1,NULL,NULL,NULL,'2020-06-19','16:00:00','17:00:00','2020-06-19',NULL,'canceled',NULL,'2020-06-16 19:13:16',10.00,'172.18.0.1',''),(76,1,1,1,NULL,NULL,NULL,'2020-07-06','13:00:00','14:00:00','2020-07-06',NULL,'confirmed',NULL,'2020-07-05 10:45:44',30.00,'172.18.0.1',''),(77,2,1,1,NULL,NULL,NULL,'2020-07-10','15:00:00','16:00:00','2020-07-10',NULL,'confirmed',NULL,'2020-07-05 13:28:14',10.00,NULL,NULL),(78,1,1,1,NULL,NULL,NULL,'2020-07-10','14:00:00','15:00:00','2020-07-10',NULL,'confirmed',NULL,'2020-07-05 13:30:55',50.00,NULL,NULL),(79,1,1,1,NULL,NULL,NULL,'2020-07-09','10:00:00','11:00:00','2020-07-09',NULL,'confirmed',NULL,'2020-07-05 18:12:01',10.00,NULL,NULL),(80,1,1,1,NULL,NULL,NULL,'2020-07-10','12:00:00','13:00:00','2020-07-10',NULL,'pending',NULL,'2020-07-08 16:51:18',10.00,'172.18.0.1',''),(81,1,1,1,NULL,NULL,NULL,'2020-07-18','12:00:00','13:00:00','2020-07-18',NULL,'pending',NULL,'2020-07-13 20:04:10',10.00,'172.18.0.1',''),(82,1,1,1,NULL,NULL,NULL,'2020-07-14','16:00:00','17:00:00','2020-07-14',NULL,'pending',NULL,'2020-07-13 21:16:00',10.00,'172.18.0.1',''),(83,1,1,1,NULL,NULL,NULL,'2020-07-16','12:00:00','13:00:00','2020-07-16',NULL,'pending',NULL,'2020-07-15 17:34:19',10.00,'172.18.0.1',''),(84,1,1,1,NULL,NULL,NULL,'2020-07-29','11:00:00','12:00:00','2020-07-29',NULL,'pending',NULL,'2020-07-28 20:57:53',10.00,'172.18.0.1',''),(85,1,1,1,NULL,NULL,NULL,'2020-07-31','12:00:00','13:00:00','2020-07-31',NULL,'pending',NULL,'2020-07-29 20:33:21',10.00,'172.18.0.1',''),(87,1,1,1,NULL,NULL,NULL,'2020-08-23',NULL,'18:01:00','2020-08-23',NULL,'pending',NULL,'2020-08-10 05:59:44',10.00,NULL,NULL),(92,1,1,1,NULL,NULL,NULL,'2020-09-10','10:00:00','11:00:00','2020-09-10',NULL,'confirmed',NULL,'2020-09-06 20:24:02',10.00,NULL,NULL),(93,1,1,1,NULL,NULL,NULL,'2020-09-17','10:00:00','10:30:00','2020-09-17',NULL,'confirmed',NULL,'2020-09-06 20:25:49',10.00,NULL,NULL),(94,1,1,1,NULL,NULL,NULL,'2020-09-16','10:30:00','11:00:00','2020-09-16',NULL,'confirmed',NULL,'2020-09-06 20:34:01',10.00,NULL,NULL),(95,1,1,1,NULL,NULL,NULL,'2020-09-18','12:00:00','12:30:00','2020-09-18',NULL,'confirmed',NULL,'2020-09-06 20:57:54',10.00,NULL,NULL),(96,1,1,1,NULL,NULL,NULL,'2020-09-19','10:00:00','10:30:00','2020-09-19',NULL,'confirmed',NULL,'2020-09-06 21:01:44',10.00,NULL,NULL),(97,1,1,1,NULL,NULL,NULL,'2020-09-25','12:00:00','13:00:00','2020-09-25',NULL,'confirmed',NULL,'2020-09-23 19:17:27',10.00,'172.18.0.1',''),(98,1,1,1,NULL,NULL,NULL,'2020-09-30','19:00:00','20:00:00','2020-09-30',NULL,'confirmed',NULL,'2020-09-25 18:48:47',10.00,'172.18.0.1',''),(99,1,1,1,NULL,NULL,NULL,'2020-09-29','19:00:00','20:00:00','2020-09-29',NULL,'reservation',NULL,'2020-09-26 18:43:54',10.00,'172.18.0.1',''),(100,2,2,1,NULL,NULL,NULL,'2020-09-30','11:00:00','12:00:00','2020-09-30',NULL,'reservation',NULL,'2020-09-26 20:49:37',0.00,'172.18.0.1',''),(101,2,2,1,NULL,NULL,NULL,'2020-10-30','10:00:00','11:00:00','2020-10-30',NULL,'reservation',NULL,'2020-09-26 20:58:41',0.00,'172.18.0.1',''),(102,1,1,1,NULL,NULL,NULL,'2020-09-30','14:00:00','15:00:00','2020-09-30',NULL,'reservation',NULL,'2020-09-26 21:17:37',10.00,'172.18.0.1',''),(103,1,1,1,NULL,NULL,NULL,'2020-09-27','16:00:00','17:00:00','2020-09-27',NULL,'reservation',NULL,'2020-09-27 13:01:45',10.00,'172.18.0.1',''),(104,1,1,1,NULL,NULL,NULL,'2020-10-22','12:00:00','13:00:00','2020-10-22',NULL,'reservation',NULL,'2020-10-01 18:57:37',10.00,'172.18.0.1',''),(105,1,1,1,NULL,NULL,NULL,'2020-10-16','13:00:00','14:00:00','2020-10-16',NULL,'reservation',NULL,'2020-10-06 17:27:36',10.00,'172.18.0.1','');
/*!40000 ALTER TABLE `wp_ea_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_connect_links`
--

DROP TABLE IF EXISTS `wp_ea_connect_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_connect_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `google_event_id` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `google_calendar_id` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`,`google_event_id`),
  KEY `googe_event_id` (`google_event_id`),
  KEY `google_event_id` (`google_event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_connect_links`
--

LOCK TABLES `wp_ea_connect_links` WRITE;
/*!40000 ALTER TABLE `wp_ea_connect_links` DISABLE KEYS */;
INSERT INTO `wp_ea_connect_links` VALUES (4,78,'9rm7iea2rsd30ditvqea19hmao','2020-07-05 13:38:45','primary'),(5,76,'nfaejlql45okjieu12b6hpttq4','2020-07-05 16:10:24','primary'),(6,79,'2jfts4ma6tbdi266b08nb1pgkm','2020-07-05 18:12:01','primary'),(7,1,'01mc7jc6h7u5ag8hdfh0t3f2k8','2020-08-23 17:04:12','primary'),(8,92,'4ln1nd6kuhgkrnpc4v9u7ilcr8','2020-09-06 20:24:02','primary'),(9,93,'56pbkcqd6c0vqen0l2uhe1tmvg','2020-09-06 20:25:49','primary'),(10,94,'6f18s5fbe9d5lqc3f6to242lkt','2020-09-06 20:34:01','primary'),(11,95,'2pp79v5m014bmp4os5fh8tip88','2020-09-06 20:57:54','primary'),(12,96,'6c0lug10gd8l3qdcvuv67jkvso','2020-09-06 21:01:44','primary'),(13,97,'t878a6hgkj63774pqhd3ema2vo','2020-09-23 19:17:46','primary'),(14,98,'nhdocpfcqkcfidlvg42mp9v1k0','2020-09-25 18:49:33','primary');
/*!40000 ALTER TABLE `wp_ea_connect_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_connections`
--

DROP TABLE IF EXISTS `wp_ea_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `service` int(11) DEFAULT NULL,
  `worker` int(11) DEFAULT NULL,
  `slot_count` int(11) DEFAULT '1',
  `day_of_week` varchar(60) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `day_from` date DEFAULT NULL,
  `day_to` date DEFAULT NULL,
  `is_working` smallint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_to_connection` (`location`),
  KEY `service_to_location` (`service`),
  KEY `worker_to_connection` (`worker`),
  CONSTRAINT `wp_ea_connections_ibfk_1` FOREIGN KEY (`location`) REFERENCES `wp_ea_locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wp_ea_connections_ibfk_2` FOREIGN KEY (`service`) REFERENCES `wp_ea_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wp_ea_connections_ibfk_3` FOREIGN KEY (`worker`) REFERENCES `wp_ea_staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_connections`
--

LOCK TABLES `wp_ea_connections` WRITE;
/*!40000 ALTER TABLE `wp_ea_connections` DISABLE KEYS */;
INSERT INTO `wp_ea_connections` VALUES (1,NULL,1,1,1,1,'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday','10:00:00','20:00:00','2018-01-01','2022-01-01',1),(2,NULL,2,2,1,1,'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday','10:00:00','20:00:00','2020-01-01','2021-12-31',1);
/*!40000 ALTER TABLE `wp_ea_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_error_logs`
--

DROP TABLE IF EXISTS `wp_ea_error_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `errors` text COLLATE utf8mb4_unicode_520_ci,
  `errors_data` text COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_error_logs`
--

LOCK TABLES `wp_ea_error_logs` WRITE;
/*!40000 ALTER TABLE `wp_ea_error_logs` DISABLE KEYS */;
INSERT INTO `wp_ea_error_logs` VALUES (1,'MAIL','{\"wp_mail_failed\":[\"You must provide at least one recipient email address.\"]}','{\"wp_mail_failed\":{\"to\":[\"\"],\"subject\":\"Reservation 65\",\"message\":\"<!DOCTYPE HTML>\\n<html lang=\\\"en\\\">\\n    <head>\\n        <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html;charset=UTF-8\\\">\\n    <\\/head>\\n    <body>\\n        <p>pending&nbsp;<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>Name: <\\/p>\\n<p>Email: <\\/p>\\n<p>Phone: <\\/p>\\n<p><a>Approve Booking URL<\\/a> (http:\\/\\/127.0.0.1:8080?_ea-action=confirm&_ea-app=65&_ea-t=6c7587d4954bf8b5714b7e1d1fb0ac65)<\\/p>\\n<p><a>Reject Booking URL<\\/a> (http:\\/\\/127.0.0.1:8080?_ea-action=cancel&_ea-app=65&_ea-t=ea14598d0b8a66f8eacb8105f3adc428)<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>AAA<\\/p>\\n    <\\/body>\\n<\\/html>\",\"headers\":[],\"attachments\":[],\"phpmailer_exception_code\":2}}'),(2,'MAIL','{\"wp_mail_failed\":[\"You must provide at least one recipient email address.\"]}','{\"wp_mail_failed\":{\"to\":[\"aaaaa\",null],\"subject\":\"Reservation 65\",\"message\":\"<!DOCTYPE HTML>\\n<html lang=\\\"en\\\">\\n    <head>\\n        <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html;charset=UTF-8\\\">\\n    <\\/head>\\n    <body>\\n        <p>pending&nbsp;<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>Name: <\\/p>\\n<p>Email: aaaaa<\\/p>\\n<p>Phone: <\\/p>\\n<p><a>Approve Booking URL<\\/a> (http:\\/\\/127.0.0.1:8080?_ea-action=confirm&_ea-app=65&_ea-t=6c7587d4954bf8b5714b7e1d1fb0ac65)<\\/p>\\n<p><a>Reject Booking URL<\\/a> (http:\\/\\/127.0.0.1:8080?_ea-action=cancel&_ea-app=65&_ea-t=ea14598d0b8a66f8eacb8105f3adc428)<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>AAA<\\/p>\\n    <\\/body>\\n<\\/html>\",\"headers\":[],\"attachments\":[],\"phpmailer_exception_code\":2}}');
/*!40000 ALTER TABLE `wp_ea_error_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_fields`
--

DROP TABLE IF EXISTS `wp_ea_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=703 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_fields`
--

LOCK TABLES `wp_ea_fields` WRITE;
/*!40000 ALTER TABLE `wp_ea_fields` DISABLE KEYS */;
INSERT INTO `wp_ea_fields` VALUES (527,87,7,''),(528,87,6,''),(529,87,5,''),(530,87,4,''),(531,87,3,'692762294'),(532,87,2,'Nikola Loncar'),(533,87,1,'nikolanbg@gmail.com'),(534,88,7,'adad'),(535,88,6,'nikolanbg@gmail.com'),(536,88,5,''),(537,88,4,'AASASASASAS'),(538,88,3,'adfadf'),(539,88,2,'Nikola'),(540,88,1,'kakfa@dafda.aa'),(541,89,7,'adad'),(542,89,6,'nikolanbg@gmail.com'),(543,89,5,''),(544,89,4,'AASASASASAS'),(545,89,3,'adfadf'),(546,89,2,'Nikola'),(547,89,1,'kakfa@dafda.aa'),(548,90,7,'adad'),(549,90,6,'nikolanbg@gmail.com'),(550,90,5,''),(551,90,4,'AASASASASAS'),(552,90,3,'adfadf'),(553,90,2,'Nikola'),(554,90,1,'kakfa@dafda.aa'),(555,91,7,'adad'),(556,91,6,'nikolanbg@gmail.com'),(557,91,5,''),(558,91,4,'AASASASASAS'),(559,91,3,'adfadf'),(560,91,2,'Nikola'),(561,91,1,'kakfa@dafda.aa'),(562,92,7,'dwdwdw11'),(563,92,6,'blabla 123'),(564,92,5,''),(565,92,4,'DESC'),(566,92,3,'Phone 123'),(567,92,2,'Name 123'),(568,92,1,'zika@mika.com'),(569,0,7,'93'),(570,0,6,'93'),(571,0,5,'93'),(572,0,4,'93'),(573,0,3,'93'),(574,0,2,'93'),(575,0,1,'93'),(576,93,1,'zika2@mika.com'),(577,93,2,NULL),(578,93,4,'TEST\nDDDD'),(579,94,7,'dwdwdw11'),(580,94,6,'blabla 123'),(581,94,5,''),(582,94,4,'DESC'),(583,94,3,'Phone 123'),(584,94,2,'Name 123'),(585,94,1,'zika@mika.com'),(586,94,1,'zika@mika.com'),(587,94,2,NULL),(588,94,4,'TITLE\nDESC'),(589,95,7,'dwdwdw11'),(590,95,6,'blabla 123'),(591,95,5,''),(592,95,4,'DESC'),(593,95,3,'Phone 123'),(594,95,2,'Name 123'),(595,95,1,'zika@mika.com'),(596,95,1,'zika@mika.com'),(597,95,2,NULL),(598,95,4,'TITLE\nDESC'),(599,95,1,'zika@mika.com'),(600,95,2,NULL),(601,95,4,'TITLE\n'),(602,96,1,'nikolanbg@gmail.com'),(603,96,2,NULL),(604,96,4,'CCC\n'),(612,97,7,'adad'),(613,97,6,'nikolanbg@gmail.com'),(614,97,5,''),(615,97,4,'AASASASASAS'),(616,97,3,'adfadf'),(617,97,2,'Nikola'),(618,97,1,'kakfa@dafda.aa'),(626,98,7,'adad'),(627,98,6,'aaa@bbb.com'),(628,98,5,''),(629,98,4,'AASASASASAS'),(630,98,3,'adfadf'),(631,98,2,'Nikola'),(632,98,1,'kakfa@dafda.aa'),(633,99,7,'adad'),(634,99,6,'aaa@bbb.com'),(635,99,5,'8HL08915V0437211G'),(636,99,4,'AASASASASAS'),(637,99,3,'adfadf'),(638,99,2,'Nikola'),(639,99,1,'kakfa@dafda.aa'),(640,100,7,'adad'),(641,100,6,'aaa@bbb.com'),(642,100,5,''),(643,100,4,'AASASASASAS'),(644,100,3,'adfadf'),(645,100,2,'Nikola'),(646,100,1,'kakfa@dafda.aa'),(647,101,7,'adad'),(648,101,6,'aaa@bbb.com'),(649,101,5,''),(650,101,4,'AASASASASAS'),(651,101,3,'adfadf'),(652,101,2,'Nikola'),(653,101,1,'kakfa@dafda.aa'),(654,102,7,''),(655,102,6,'nikolanbg@gmail.com'),(656,102,5,''),(657,102,4,''),(658,102,3,'692762294'),(659,102,2,'Nikola Loncar'),(660,102,1,'nikolanbg@gmail.com'),(661,103,7,'adad'),(662,103,6,'aaa@bbb.com'),(663,103,5,'8GV40141538049144'),(664,103,4,'AASASASASAS'),(665,103,3,'adfadf'),(666,103,2,'Nikola'),(667,103,1,'kakfa@dafda.aa'),(668,104,7,'adad'),(669,104,6,'aaa@bbb.com'),(670,104,5,''),(671,104,4,'AASASASASAS'),(672,104,3,'adfadf'),(673,104,2,'Nikola'),(674,104,1,'kakfa@dafda.aa'),(689,65,7,''),(690,65,6,''),(691,65,5,''),(692,65,4,''),(693,65,3,''),(694,65,2,''),(695,65,1,'aaaaa'),(696,105,7,'adad'),(697,105,6,'aaa@bbb.com'),(698,105,5,''),(699,105,4,'AASASASASAS'),(700,105,3,'adfadf'),(701,105,2,'Nikola'),(702,105,1,'kakfa@dafda.aa');
/*!40000 ALTER TABLE `wp_ea_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_locations`
--

DROP TABLE IF EXISTS `wp_ea_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cord` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_locations`
--

LOCK TABLES `wp_ea_locations` WRITE;
/*!40000 ALTER TABLE `wp_ea_locations` DISABLE KEYS */;
INSERT INTO `wp_ea_locations` VALUES (1,'Test','Test','test',NULL),(2,'aaa','dd','ff',NULL);
/*!40000 ALTER TABLE `wp_ea_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_meta_fields`
--

DROP TABLE IF EXISTS `wp_ea_meta_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_meta_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `mixed` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `default_value` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `validation` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_meta_fields`
--

LOCK TABLES `wp_ea_meta_fields` WRITE;
/*!40000 ALTER TABLE `wp_ea_meta_fields` DISABLE KEYS */;
INSERT INTO `wp_ea_meta_fields` VALUES (1,'INPUT','email','EMail','','',1,1,'email',1),(2,'INPUT','name','Name','','',1,1,'minlength-3',2),(3,'INPUT','phone','Phone','','',1,1,'minlength-3',3),(4,'TEXTAREA','description','Description','','',1,0,NULL,4),(5,'INPUT','paypal-transaction-id','PayPal Transaction ID','','',0,0,'',100),(6,'EMAIL','blabla','blabla','second email','',1,1,'',6),(7,'INPUT','dwdwdw','dwdwdw','','',1,0,'',7);
/*!40000 ALTER TABLE `wp_ea_meta_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_options`
--

DROP TABLE IF EXISTS `wp_ea_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ea_key` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ea_value` text COLLATE utf8mb4_unicode_520_ci,
  `type` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2726 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_options`
--

LOCK TABLES `wp_ea_options` WRITE;
/*!40000 ALTER TABLE `wp_ea_options` DISABLE KEYS */;
INSERT INTO `wp_ea_options` VALUES (2673,'mail.pending','<p>pending&nbsp;</p>\n<p>&nbsp;</p>\n<p>Name: #name#</p>\n<p>Email: #email#</p>\n<p>Phone: #phone#</p>\n<p><a>Approve Booking URL</a> (#url_confirm#)</p>\n<p><a>Reject Booking URL</a> (#url_cancel#)</p>\n<p>&nbsp;</p>\n<p>AAA</p>','default'),(2674,'mail.reservation','<p>reservation 1</p>','default'),(2675,'mail.canceled','<p>canceled 2</p>','default'),(2676,'mail.confirmed','<p>confirmed 3</p>','default'),(2677,'mail.admin','<p>dasda</p>','default'),(2678,'mail.action.two_step','1','default'),(2679,'trans.service','Service','default'),(2680,'trans.location','Location','default'),(2681,'trans.worker','Worker','default'),(2682,'trans.done_message','Done','default'),(2683,'time_format','am-pm','default'),(2684,'trans.currency','$','default'),(2685,'pending.email','','default'),(2686,'price.hide','0','default'),(2687,'price.hide.service','0','default'),(2688,'datepicker','en-US','default'),(2689,'send.user.email','1','default'),(2690,'custom.css','','default'),(2691,'form.label.above','1','default'),(2692,'show.iagree','0','default'),(2693,'cancel.scroll','calendar','default'),(2694,'multiple.work','1','default'),(2695,'compatibility.mode','0','default'),(2696,'pending.subject.email','New Reservation #id#','default'),(2697,'send.from.email','TEST<nikolanbg@gmail.com>','default'),(2698,'css.off','0','default'),(2699,'submit.redirect','','default'),(2700,'advance.redirect','[]','default'),(2701,'pending.subject.visitor.email','Reservation #id#','default'),(2702,'block.time','0','default'),(2703,'max.appointments','1','default'),(2704,'pre.reservation','0','default'),(2705,'default.status','reservation','default'),(2706,'send.worker.email','1','default'),(2707,'currency.before','0','default'),(2708,'nonce.off','0','default'),(2709,'gdpr.on','0','default'),(2710,'gdpr.label','By using this form you agree with the storage and handling of your data by this website.','default'),(2711,'gdpr.link','','default'),(2712,'gdpr.message','You need to accept the privacy checkbox','default'),(2713,'gdpr.auto_remove','1','default'),(2714,'sort.workers-by',NULL,'default'),(2715,'sort.services-by',NULL,'default'),(2716,'sort.locations-by',NULL,'default'),(2717,'order.workers-by',NULL,'default'),(2718,'order.services-by',NULL,'default'),(2719,'order.locations-by',NULL,'default'),(2720,'captcha.site-key','','default'),(2721,'captcha.secret-key','','default'),(2722,'fullcalendar.public','1','default'),(2723,'fullcalendar.event.show','1','default'),(2724,'fullcalendar.event.template','<p>{= id}</p>\n<p>{= event.location_name} 1 2 3</p>\n<p>&nbsp;</p>\n<pre lang=\"text\">{= event}</pre>','default'),(2725,'shortcode.compress','0','default');
/*!40000 ALTER TABLE `wp_ea_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_services`
--

DROP TABLE IF EXISTS `wp_ea_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `slot_step` int(11) DEFAULT NULL,
  `block_before` int(11) DEFAULT '0',
  `block_after` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_services`
--

LOCK TABLES `wp_ea_services` WRITE;
/*!40000 ALTER TABLE `wp_ea_services` DISABLE KEYS */;
INSERT INTO `wp_ea_services` VALUES (1,'Test',60,60,0,0,10.00),(2,'A',60,60,0,0,0.00);
/*!40000 ALTER TABLE `wp_ea_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_staff`
--

DROP TABLE IF EXISTS `wp_ea_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_staff`
--

LOCK TABLES `wp_ea_staff` WRITE;
/*!40000 ALTER TABLE `wp_ea_staff` DISABLE KEYS */;
INSERT INTO `wp_ea_staff` VALUES (1,'Test','test','demo@site.com','phone');
/*!40000 ALTER TABLE `wp_ea_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_ea_twilio_reminder`
--

DROP TABLE IF EXISTS `wp_ea_twilio_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_ea_twilio_reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `reminder` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_ea_twilio_reminder`
--

LOCK TABLES `wp_ea_twilio_reminder` WRITE;
/*!40000 ALTER TABLE `wp_ea_twilio_reminder` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_ea_twilio_reminder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_links`
--

LOCK TABLES `wp_links` WRITE;
/*!40000 ALTER TABLE `wp_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=1136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,'siteurl','http://127.0.0.1:8080','yes'),(2,'home','http://127.0.0.1:8080','yes'),(3,'blogname','demo','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','nikolanbg@gmail.com','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','d/m/Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%postname%/','yes'),(29,'rewrite_rules','a:155:{s:24:\"^wc-auth/v([1]{1})/(.*)?\";s:63:\"index.php?wc-auth-version=$matches[1]&wc-auth-route=$matches[2]\";s:22:\"^wc-api/v([1-3]{1})/?$\";s:51:\"index.php?wc-api-version=$matches[1]&wc-api-route=/\";s:24:\"^wc-api/v([1-3]{1})(.*)?\";s:61:\"index.php?wc-api-version=$matches[1]&wc-api-route=$matches[2]\";s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:17:\"^wp-sitemap\\.xml$\";s:23:\"index.php?sitemap=index\";s:17:\"^wp-sitemap\\.xsl$\";s:36:\"index.php?sitemap-stylesheet=sitemap\";s:23:\"^wp-sitemap-index\\.xsl$\";s:34:\"index.php?sitemap-stylesheet=index\";s:48:\"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$\";s:75:\"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]\";s:34:\"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$\";s:47:\"index.php?sitemap=$matches[1]&paged=$matches[2]\";s:7:\"^ea-api\";s:18:\"index.php?ea_api=1\";s:47:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:42:\"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:23:\"category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:32:\"category/(.+?)/wc-api(/(.*))?/?$\";s:54:\"index.php?category_name=$matches[1]&wc-api=$matches[3]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:44:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:39:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:20:\"tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:29:\"tag/([^/]+)/wc-api(/(.*))?/?$\";s:44:\"index.php?tag=$matches[1]&wc-api=$matches[3]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:45:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:40:\"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:21:\"type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:55:\"product-category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_cat=$matches[1]&feed=$matches[2]\";s:50:\"product-category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_cat=$matches[1]&feed=$matches[2]\";s:31:\"product-category/(.+?)/embed/?$\";s:44:\"index.php?product_cat=$matches[1]&embed=true\";s:43:\"product-category/(.+?)/page/?([0-9]{1,})/?$\";s:51:\"index.php?product_cat=$matches[1]&paged=$matches[2]\";s:25:\"product-category/(.+?)/?$\";s:33:\"index.php?product_cat=$matches[1]\";s:52:\"product-tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_tag=$matches[1]&feed=$matches[2]\";s:47:\"product-tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_tag=$matches[1]&feed=$matches[2]\";s:28:\"product-tag/([^/]+)/embed/?$\";s:44:\"index.php?product_tag=$matches[1]&embed=true\";s:40:\"product-tag/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?product_tag=$matches[1]&paged=$matches[2]\";s:22:\"product-tag/([^/]+)/?$\";s:33:\"index.php?product_tag=$matches[1]\";s:35:\"product/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:45:\"product/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:65:\"product/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:60:\"product/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:60:\"product/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:41:\"product/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:24:\"product/([^/]+)/embed/?$\";s:40:\"index.php?product=$matches[1]&embed=true\";s:28:\"product/([^/]+)/trackback/?$\";s:34:\"index.php?product=$matches[1]&tb=1\";s:36:\"product/([^/]+)/page/?([0-9]{1,})/?$\";s:47:\"index.php?product=$matches[1]&paged=$matches[2]\";s:43:\"product/([^/]+)/comment-page-([0-9]{1,})/?$\";s:47:\"index.php?product=$matches[1]&cpage=$matches[2]\";s:33:\"product/([^/]+)/wc-api(/(.*))?/?$\";s:48:\"index.php?product=$matches[1]&wc-api=$matches[3]\";s:39:\"product/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:50:\"product/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:32:\"product/([^/]+)(?:/([0-9]+))?/?$\";s:46:\"index.php?product=$matches[1]&page=$matches[2]\";s:24:\"product/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:34:\"product/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:54:\"product/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:49:\"product/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:49:\"product/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:30:\"product/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:12:\"robots\\.txt$\";s:18:\"index.php?robots=1\";s:13:\"favicon\\.ico$\";s:19:\"index.php?favicon=1\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:17:\"wc-api(/(.*))?/?$\";s:29:\"index.php?&wc-api=$matches[2]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:26:\"comments/wc-api(/(.*))?/?$\";s:29:\"index.php?&wc-api=$matches[2]\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:29:\"search/(.+)/wc-api(/(.*))?/?$\";s:42:\"index.php?s=$matches[1]&wc-api=$matches[3]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:47:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:42:\"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:23:\"author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:32:\"author/([^/]+)/wc-api(/(.*))?/?$\";s:52:\"index.php?author_name=$matches[1]&wc-api=$matches[3]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:69:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:54:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/wc-api(/(.*))?/?$\";s:82:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&wc-api=$matches[5]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:56:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:51:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:32:\"([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:41:\"([0-9]{4})/([0-9]{1,2})/wc-api(/(.*))?/?$\";s:66:\"index.php?year=$matches[1]&monthnum=$matches[2]&wc-api=$matches[4]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:43:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:38:\"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:19:\"([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:28:\"([0-9]{4})/wc-api(/(.*))?/?$\";s:45:\"index.php?year=$matches[1]&wc-api=$matches[3]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:25:\"(.?.+?)/wc-api(/(.*))?/?$\";s:49:\"index.php?pagename=$matches[1]&wc-api=$matches[3]\";s:28:\"(.?.+?)/order-pay(/(.*))?/?$\";s:52:\"index.php?pagename=$matches[1]&order-pay=$matches[3]\";s:33:\"(.?.+?)/order-received(/(.*))?/?$\";s:57:\"index.php?pagename=$matches[1]&order-received=$matches[3]\";s:25:\"(.?.+?)/orders(/(.*))?/?$\";s:49:\"index.php?pagename=$matches[1]&orders=$matches[3]\";s:29:\"(.?.+?)/view-order(/(.*))?/?$\";s:53:\"index.php?pagename=$matches[1]&view-order=$matches[3]\";s:28:\"(.?.+?)/downloads(/(.*))?/?$\";s:52:\"index.php?pagename=$matches[1]&downloads=$matches[3]\";s:31:\"(.?.+?)/edit-account(/(.*))?/?$\";s:55:\"index.php?pagename=$matches[1]&edit-account=$matches[3]\";s:31:\"(.?.+?)/edit-address(/(.*))?/?$\";s:55:\"index.php?pagename=$matches[1]&edit-address=$matches[3]\";s:34:\"(.?.+?)/payment-methods(/(.*))?/?$\";s:58:\"index.php?pagename=$matches[1]&payment-methods=$matches[3]\";s:32:\"(.?.+?)/lost-password(/(.*))?/?$\";s:56:\"index.php?pagename=$matches[1]&lost-password=$matches[3]\";s:34:\"(.?.+?)/customer-logout(/(.*))?/?$\";s:58:\"index.php?pagename=$matches[1]&customer-logout=$matches[3]\";s:37:\"(.?.+?)/add-payment-method(/(.*))?/?$\";s:61:\"index.php?pagename=$matches[1]&add-payment-method=$matches[3]\";s:40:\"(.?.+?)/delete-payment-method(/(.*))?/?$\";s:64:\"index.php?pagename=$matches[1]&delete-payment-method=$matches[3]\";s:45:\"(.?.+?)/set-default-payment-method(/(.*))?/?$\";s:69:\"index.php?pagename=$matches[1]&set-default-payment-method=$matches[3]\";s:31:\".?.+?/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:42:\".?.+?/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";s:27:\"[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\"[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\"[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\"[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"([^/]+)/embed/?$\";s:37:\"index.php?name=$matches[1]&embed=true\";s:20:\"([^/]+)/trackback/?$\";s:31:\"index.php?name=$matches[1]&tb=1\";s:40:\"([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:35:\"([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:28:\"([^/]+)/page/?([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&paged=$matches[2]\";s:35:\"([^/]+)/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&cpage=$matches[2]\";s:25:\"([^/]+)/wc-api(/(.*))?/?$\";s:45:\"index.php?name=$matches[1]&wc-api=$matches[3]\";s:31:\"[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:42:\"[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:24:\"([^/]+)(?:/([0-9]+))?/?$\";s:43:\"index.php?name=$matches[1]&page=$matches[2]\";s:16:\"[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:26:\"[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:46:\"[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:22:\"[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";}','yes'),(30,'hack_file','0','yes'),(31,'blog_charset','UTF-8','yes'),(32,'moderation_keys','','no'),(33,'active_plugins','a:3:{i:0;s:34:\"easy-appointments-connect/main.php\";i:1;s:26:\"easy-appointments/main.php\";i:2;s:27:\"woocommerce/woocommerce.php\";}','yes'),(34,'category_base','','yes'),(35,'ping_sites','http://rpc.pingomatic.com/','yes'),(36,'comment_max_links','2','yes'),(37,'gmt_offset','','yes'),(38,'default_email_category','1','yes'),(39,'recently_edited','','no'),(40,'template','Bootstrap-3-blank-wordpress-theme-master','yes'),(41,'stylesheet','Bootstrap-3-blank-wordpress-theme-master-child','yes'),(44,'comment_registration','0','yes'),(45,'html_type','text/html','yes'),(46,'use_trackback','0','yes'),(47,'default_role','subscriber','yes'),(48,'db_version','48748','yes'),(49,'uploads_use_yearmonth_folders','1','yes'),(50,'upload_path','','yes'),(51,'blog_public','0','yes'),(52,'default_link_category','2','yes'),(53,'show_on_front','posts','yes'),(54,'tag_base','','yes'),(55,'show_avatars','1','yes'),(56,'avatar_rating','G','yes'),(57,'upload_url_path','','yes'),(58,'thumbnail_size_w','150','yes'),(59,'thumbnail_size_h','150','yes'),(60,'thumbnail_crop','1','yes'),(61,'medium_size_w','300','yes'),(62,'medium_size_h','300','yes'),(63,'avatar_default','mystery','yes'),(64,'large_size_w','1024','yes'),(65,'large_size_h','1024','yes'),(66,'image_default_link_type','none','yes'),(67,'image_default_size','','yes'),(68,'image_default_align','','yes'),(69,'close_comments_for_old_posts','0','yes'),(70,'close_comments_days_old','14','yes'),(71,'thread_comments','1','yes'),(72,'thread_comments_depth','5','yes'),(73,'page_comments','0','yes'),(74,'comments_per_page','50','yes'),(75,'default_comments_page','newest','yes'),(76,'comment_order','asc','yes'),(77,'sticky_posts','a:0:{}','yes'),(78,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(79,'widget_text','a:0:{}','yes'),(80,'widget_rss','a:0:{}','yes'),(81,'uninstall_plugins','a:2:{s:26:\"easy-appointments/main.php\";a:2:{i:0;s:15:\"EasyAppointment\";i:1;s:9:\"uninstall\";}s:34:\"easy-appointments-connect/main.php\";a:2:{i:0;s:23:\"EasyAppointmentsConnect\";i:1;s:9:\"uninstall\";}}','no'),(82,'timezone_string','Europe/Belgrade','yes'),(83,'page_for_posts','0','yes'),(84,'page_on_front','0','yes'),(85,'default_post_format','0','yes'),(86,'link_manager_enabled','0','yes'),(87,'finished_splitting_shared_terms','1','yes'),(88,'site_icon','0','yes'),(89,'medium_large_size_w','768','yes'),(90,'medium_large_size_h','0','yes'),(91,'wp_page_for_privacy_policy','3','yes'),(92,'show_comments_cookies_opt_in','0','yes'),(93,'initial_db_version','43764','yes'),(94,'wp_user_roles','a:7:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:114:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}s:8:\"customer\";a:2:{s:4:\"name\";s:8:\"Customer\";s:12:\"capabilities\";a:1:{s:4:\"read\";b:1;}}s:12:\"shop_manager\";a:2:{s:4:\"name\";s:12:\"Shop manager\";s:12:\"capabilities\";a:92:{s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:4:\"read\";b:1;s:18:\"read_private_pages\";b:1;s:18:\"read_private_posts\";b:1;s:10:\"edit_posts\";b:1;s:10:\"edit_pages\";b:1;s:20:\"edit_published_posts\";b:1;s:20:\"edit_published_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"edit_private_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:17:\"edit_others_pages\";b:1;s:13:\"publish_posts\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_posts\";b:1;s:12:\"delete_pages\";b:1;s:20:\"delete_private_pages\";b:1;s:20:\"delete_private_posts\";b:1;s:22:\"delete_published_pages\";b:1;s:22:\"delete_published_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:19:\"delete_others_pages\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:17:\"moderate_comments\";b:1;s:12:\"upload_files\";b:1;s:6:\"export\";b:1;s:6:\"import\";b:1;s:10:\"list_users\";b:1;s:18:\"edit_theme_options\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;}}}','yes'),(95,'fresh_site','0','yes'),(96,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(97,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(98,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(99,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(100,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(101,'sidebars_widgets','a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:11:\"forum_right\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:13:\"array_version\";i:3;}','yes'),(102,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(103,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(104,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(105,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(106,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(107,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(108,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(109,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(110,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(135,'recently_activated','a:0:{}','yes'),(136,'easy_app_db_version','2.14.1','yes'),(140,'external_updates-main','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1557855232;s:14:\"checkedVersion\";s:6:\"0.10.5\";s:6:\"update\";O:8:\"stdClass\":9:{s:2:\"id\";i:0;s:4:\"slug\";s:4:\"main\";s:7:\"version\";s:6:\"0.10.5\";s:8:\"homepage\";N;s:6:\"tested\";N;s:12:\"download_url\";s:52:\"http://easy-appointments.net/easy-connect/plugin.zip\";s:14:\"upgrade_notice\";N;s:8:\"filename\";s:34:\"easy-appointments-connect/main.php\";s:12:\"translations\";a:0:{}}}','no'),(141,'theme_mods_twentynineteen','a:3:{s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1557858653;s:4:\"data\";a:2:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}}}s:18:\"nav_menu_locations\";a:0:{}}','yes'),(173,'WPLANG','','yes'),(174,'new_admin_email','nikolanbg@gmail.com','yes'),(204,'auto_core_update_notified','a:4:{s:4:\"type\";s:7:\"success\";s:5:\"email\";s:19:\"nikolanbg@gmail.com\";s:7:\"version\";s:5:\"5.2.7\";s:9:\"timestamp\";i:1593956392;}','no'),(224,'current_theme','Easy Appointments','yes'),(225,'theme_mods_twentysixteen','a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1551003626;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}}}}','yes'),(226,'theme_switched','','yes'),(229,'theme_mods_twentyseventeen','a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1551004354;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}}}}','yes'),(231,'theme_mods_twentyfifteen','a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1555244290;s:4:\"data\";a:2:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}}}}','yes'),(279,'EAC_twilio_account_id','','yes'),(280,'EAC_twilio_token','','yes'),(281,'easy_app_connect_db_version','0.10.6','yes'),(284,'EAC_twilio_phone_field','phone','yes'),(285,'EAC_twilio_phone_from','121341413','yes'),(286,'EAC_twilio_sms_reminder','','yes'),(287,'EAC_twilio_template_canceled','','yes'),(288,'EAC_twilio_template_confirmed','','yes'),(289,'EAC_twilio_template_pending','','yes'),(290,'EAC_twilio_template_reservation','','yes'),(291,'EAC_twilio_template_reminder','CCC','yes'),(292,'EAC_twilio_send_to','1','yes'),(293,'EAC_twilio_country_number','','yes'),(322,'db_upgraded','','yes'),(331,'EAC_twilio_sms_limit_month','5','yes'),(332,'_transient_twentyfifteen_categories','1','yes'),(347,'theme_mods_original','a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1557856115;s:4:\"data\";a:2:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}}}}','yes'),(387,'theme_mods_Bootstrap-3-blank-wordpress-theme-master-child','a:3:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;}','yes'),(396,'external_updates-easy-appointments-connect','O:8:\"stdClass\":5:{s:9:\"lastCheck\";i:1602407567;s:14:\"checkedVersion\";s:6:\"0.13.1\";s:6:\"update\";O:8:\"stdClass\":10:{s:4:\"slug\";s:25:\"easy-appointments-connect\";s:7:\"version\";s:6:\"0.13.1\";s:12:\"download_url\";s:53:\"https://easy-appointments.net/easy-connect/plugin.zip\";s:12:\"translations\";a:0:{}s:2:\"id\";i:0;s:8:\"homepage\";N;s:6:\"tested\";N;s:14:\"upgrade_notice\";N;s:5:\"icons\";a:0:{}s:8:\"filename\";s:34:\"easy-appointments-connect/main.php\";}s:11:\"updateClass\";s:22:\"Puc_v4p9_Plugin_Update\";s:15:\"updateBaseClass\";s:13:\"Plugin_Update\";}','no'),(401,'action_scheduler_hybrid_store_demarkation','39','yes'),(402,'schema-ActionScheduler_StoreSchema','3.0.1590528686','yes'),(403,'schema-ActionScheduler_LoggerSchema','2.0.1590528686','yes'),(406,'woocommerce_store_address','','yes'),(407,'woocommerce_store_address_2','','yes'),(408,'woocommerce_store_city','','yes'),(409,'woocommerce_default_country','GB','yes'),(410,'woocommerce_store_postcode','','yes'),(411,'woocommerce_allowed_countries','all','yes'),(412,'woocommerce_all_except_countries','','yes'),(413,'woocommerce_specific_allowed_countries','','yes'),(414,'woocommerce_ship_to_countries','','yes'),(415,'woocommerce_specific_ship_to_countries','','yes'),(416,'woocommerce_default_customer_address','base','yes'),(417,'woocommerce_calc_taxes','no','yes'),(418,'woocommerce_enable_coupons','yes','yes'),(419,'woocommerce_calc_discounts_sequentially','no','no'),(420,'woocommerce_currency','GBP','yes'),(421,'woocommerce_currency_pos','left','yes'),(422,'woocommerce_price_thousand_sep',',','yes'),(423,'woocommerce_price_decimal_sep','.','yes'),(424,'woocommerce_price_num_decimals','2','yes'),(425,'woocommerce_shop_page_id','','yes'),(426,'woocommerce_cart_redirect_after_add','no','yes'),(427,'woocommerce_enable_ajax_add_to_cart','yes','yes'),(428,'woocommerce_placeholder_image','39','yes'),(429,'woocommerce_weight_unit','kg','yes'),(430,'woocommerce_dimension_unit','cm','yes'),(431,'woocommerce_enable_reviews','yes','yes'),(432,'woocommerce_review_rating_verification_label','yes','no'),(433,'woocommerce_review_rating_verification_required','no','no'),(434,'woocommerce_enable_review_rating','yes','yes'),(435,'woocommerce_review_rating_required','yes','no'),(436,'woocommerce_manage_stock','yes','yes'),(437,'woocommerce_hold_stock_minutes','60','no'),(438,'woocommerce_notify_low_stock','yes','no'),(439,'woocommerce_notify_no_stock','yes','no'),(440,'woocommerce_stock_email_recipient','nikolanbg@gmail.com','no'),(441,'woocommerce_notify_low_stock_amount','2','no'),(442,'woocommerce_notify_no_stock_amount','0','yes'),(443,'woocommerce_hide_out_of_stock_items','no','yes'),(444,'woocommerce_stock_format','','yes'),(445,'woocommerce_file_download_method','force','no'),(446,'woocommerce_downloads_require_login','no','no'),(447,'woocommerce_downloads_grant_access_after_payment','yes','no'),(448,'woocommerce_downloads_add_hash_to_filename','yes','yes'),(449,'woocommerce_prices_include_tax','no','yes'),(450,'woocommerce_tax_based_on','shipping','yes'),(451,'woocommerce_shipping_tax_class','inherit','yes'),(452,'woocommerce_tax_round_at_subtotal','no','yes'),(453,'woocommerce_tax_classes','','yes'),(454,'woocommerce_tax_display_shop','excl','yes'),(455,'woocommerce_tax_display_cart','excl','yes'),(456,'woocommerce_price_display_suffix','','yes'),(457,'woocommerce_tax_total_display','itemized','no'),(458,'woocommerce_enable_shipping_calc','yes','no'),(459,'woocommerce_shipping_cost_requires_address','no','yes'),(460,'woocommerce_ship_to_destination','billing','no'),(461,'woocommerce_shipping_debug_mode','no','yes'),(462,'woocommerce_enable_guest_checkout','yes','no'),(463,'woocommerce_enable_checkout_login_reminder','no','no'),(464,'woocommerce_enable_signup_and_login_from_checkout','no','no'),(465,'woocommerce_enable_myaccount_registration','no','no'),(466,'woocommerce_registration_generate_username','yes','no'),(467,'woocommerce_registration_generate_password','yes','no'),(468,'woocommerce_erasure_request_removes_order_data','no','no'),(469,'woocommerce_erasure_request_removes_download_data','no','no'),(470,'woocommerce_allow_bulk_remove_personal_data','no','no'),(471,'woocommerce_registration_privacy_policy_text','Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our [privacy_policy].','yes'),(472,'woocommerce_checkout_privacy_policy_text','Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [privacy_policy].','yes'),(473,'woocommerce_delete_inactive_accounts','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','no'),(474,'woocommerce_trash_pending_orders','','no'),(475,'woocommerce_trash_failed_orders','','no'),(476,'woocommerce_trash_cancelled_orders','','no'),(477,'woocommerce_anonymize_completed_orders','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','no'),(478,'woocommerce_email_from_name','demo','no'),(479,'woocommerce_email_from_address','nikolanbg@gmail.com','no'),(480,'woocommerce_email_header_image','','no'),(481,'woocommerce_email_footer_text','{site_title} &mdash; Built with {WooCommerce}','no'),(482,'woocommerce_email_base_color','#96588a','no'),(483,'woocommerce_email_background_color','#f7f7f7','no'),(484,'woocommerce_email_body_background_color','#ffffff','no'),(485,'woocommerce_email_text_color','#3c3c3c','no'),(486,'woocommerce_cart_page_id','43','no'),(487,'woocommerce_checkout_page_id','41','no'),(488,'woocommerce_myaccount_page_id','','no'),(489,'woocommerce_terms_page_id','','no'),(490,'woocommerce_force_ssl_checkout','no','yes'),(491,'woocommerce_unforce_ssl_checkout','no','yes'),(492,'woocommerce_checkout_pay_endpoint','order-pay','yes'),(493,'woocommerce_checkout_order_received_endpoint','order-received','yes'),(494,'woocommerce_myaccount_add_payment_method_endpoint','add-payment-method','yes'),(495,'woocommerce_myaccount_delete_payment_method_endpoint','delete-payment-method','yes'),(496,'woocommerce_myaccount_set_default_payment_method_endpoint','set-default-payment-method','yes'),(497,'woocommerce_myaccount_orders_endpoint','orders','yes'),(498,'woocommerce_myaccount_view_order_endpoint','view-order','yes'),(499,'woocommerce_myaccount_downloads_endpoint','downloads','yes'),(500,'woocommerce_myaccount_edit_account_endpoint','edit-account','yes'),(501,'woocommerce_myaccount_edit_address_endpoint','edit-address','yes'),(502,'woocommerce_myaccount_payment_methods_endpoint','payment-methods','yes'),(503,'woocommerce_myaccount_lost_password_endpoint','lost-password','yes'),(504,'woocommerce_logout_endpoint','customer-logout','yes'),(505,'woocommerce_api_enabled','no','yes'),(506,'woocommerce_allow_tracking','no','no'),(507,'woocommerce_show_marketplace_suggestions','yes','no'),(508,'woocommerce_single_image_width','600','yes'),(509,'woocommerce_thumbnail_image_width','300','yes'),(510,'woocommerce_checkout_highlight_required_fields','yes','yes'),(511,'woocommerce_demo_store','no','no'),(512,'woocommerce_permalinks','a:5:{s:12:\"product_base\";s:7:\"product\";s:13:\"category_base\";s:16:\"product-category\";s:8:\"tag_base\";s:11:\"product-tag\";s:14:\"attribute_base\";s:0:\"\";s:22:\"use_verbose_page_rules\";b:0;}','yes'),(513,'current_theme_supports_woocommerce','no','yes'),(514,'woocommerce_queue_flush_rewrite_rules','no','yes'),(516,'product_cat_children','a:0:{}','yes'),(517,'default_product_cat','15','yes'),(518,'woocommerce_admin_notices','a:2:{i:0;s:6:\"update\";i:1;s:20:\"no_secure_connection\";}','yes'),(525,'action_scheduler_lock_async-request-runner','1602407942','yes'),(526,'woocommerce_maxmind_geolocation_settings','a:1:{s:15:\"database_prefix\";s:32:\"olufzdOD4uU8DUyvbtsyIAy6Baz9yngb\";}','yes'),(527,'_transient_woocommerce_webhook_ids_status_active','a:0:{}','yes'),(528,'widget_woocommerce_widget_cart','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(529,'widget_woocommerce_layered_nav_filters','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(530,'widget_woocommerce_layered_nav','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(531,'widget_woocommerce_price_filter','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(532,'widget_woocommerce_product_categories','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(533,'widget_woocommerce_product_search','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(534,'widget_woocommerce_product_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(535,'widget_woocommerce_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(536,'widget_woocommerce_recently_viewed_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(537,'widget_woocommerce_top_rated_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(538,'widget_woocommerce_recent_reviews','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(539,'widget_woocommerce_rating_filter','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(542,'_transient_wc_count_comments','O:8:\"stdClass\":7:{s:14:\"total_comments\";i:1;s:3:\"all\";i:1;s:8:\"approved\";s:1:\"1\";s:9:\"moderated\";i:0;s:4:\"spam\";i:0;s:5:\"trash\";i:0;s:12:\"post-trashed\";i:0;}','yes'),(543,'woocommerce_meta_box_errors','a:0:{}','yes'),(574,'_transient_product_query-transient-version','1601155832','yes'),(575,'_transient_product-transient-version','1590529207','yes'),(576,'EAC_woo_products','[{\"id\":\"40\",\"name\":\"Test proizvod\",\"service\":\"1\"}]','yes'),(577,'EAC_woo_status','1','yes'),(578,'_transient_shipping-transient-version','1590528871','yes'),(591,'EAC_client_id','232435844023-57035d8tdlqcmpg42nn1lhmofs976air.apps.googleusercontent.com','yes'),(592,'EAC_client_secret','M7SeTQLo2rJWLY763Nkg0HC7','yes'),(593,'EAC_google_subject','Subject : #email#, #name#','yes'),(594,'EAC_google_description','Description #start#, #end#, #status#, #created#, #price#, #url_confirm#','yes'),(595,'EAC_default_service','1','yes'),(596,'EAC_default_location','1','yes'),(597,'EAC_google_sync_days','100','yes'),(598,'EAC_google_sync_interval','5','yes'),(599,'EAC_google_sync_calendars','[{\"calendar\":{\"id\":\"primary\",\"name\":\"Primary\"},\"location\":{\"id\":\"1\",\"name\":\"Test\"},\"service\":{\"id\":\"1\",\"name\":\"Test\"},\"worker\":{\"id\":\"1\",\"name\":\"Test\"}}]','yes'),(600,'EAC_google_use_customer_email','1','yes'),(601,'EAC_google_sync_log_level','debug','yes'),(602,'EAC_google_delete_strategy','0','yes'),(688,'recovery_mode_email_last_sent','1602316160','yes'),(689,'recovery_keys','a:2:{s:22:\"L0mmgGPcl28VEdv5SLDVwd\";a:2:{s:10:\"hashed_key\";s:34:\"$P$BdGaSkQ5Ss8BT07HZ4IqekfY.mgBdQ1\";s:10:\"created_at\";i:1602087100;}s:22:\"rjZokiINWigSIZW6ibVsMT\";a:2:{s:10:\"hashed_key\";s:34:\"$P$B0bK/RczmHvYaqzjFhwKK6nirVHGus1\";s:10:\"created_at\";i:1602316160;}}','yes'),(690,'EAC_DEFAULT_GOOGLE_TOKEN','{\"access_token\":\"ya29.a0AfH6SMDoGn-cbLKzyXgN1b1EV1kEFh05ZWmbe6eA8wlKqoM5qg0VJBRn2y1Lsf6PJtF6NqTle1hlMI63r2LzPgzY-fBzBp-pbbkkmXqbMrxhoXzA2W29BF9tHg_WRktI8iLtvcJxBbEHOfkzho4i56q_Us_1wWkv3wBl9A\",\"expires_in\":3599,\"refresh_token\":\"1\\/\\/09a122DqBOBjVCgYIARAAGAkSNwF-L9IrArfRdUSd7iwxp8a9CImUzxQdJNXd4WPFFtiZYZB-lrNuGlTsOnBPLiMVYJPDUcGiJ7U\",\"scope\":\"https:\\/\\/www.googleapis.com\\/auth\\/calendar\",\"token_type\":\"Bearer\",\"created\":1602005257}','yes'),(721,'woocommerce_marketplace_suggestions','a:2:{s:11:\"suggestions\";a:26:{i:0;a:4:{s:4:\"slug\";s:28:\"product-edit-meta-tab-header\";s:7:\"context\";s:28:\"product-edit-meta-tab-header\";s:5:\"title\";s:22:\"Recommended extensions\";s:13:\"allow-dismiss\";b:0;}i:1;a:6:{s:4:\"slug\";s:39:\"product-edit-meta-tab-footer-browse-all\";s:7:\"context\";s:28:\"product-edit-meta-tab-footer\";s:9:\"link-text\";s:21:\"Browse all extensions\";s:3:\"url\";s:64:\"https://woocommerce.com/product-category/woocommerce-extensions/\";s:8:\"promoted\";s:31:\"category-woocommerce-extensions\";s:13:\"allow-dismiss\";b:0;}i:2;a:9:{s:4:\"slug\";s:46:\"product-edit-mailchimp-woocommerce-memberships\";s:7:\"product\";s:33:\"woocommerce-memberships-mailchimp\";s:14:\"show-if-active\";a:1:{i:0;s:23:\"woocommerce-memberships\";}s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:116:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/mailchimp-for-memberships.svg\";s:5:\"title\";s:25:\"Mailchimp for Memberships\";s:4:\"copy\";s:79:\"Completely automate your email lists by syncing membership changes to Mailchimp\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:67:\"https://woocommerce.com/products/mailchimp-woocommerce-memberships/\";}i:3;a:9:{s:4:\"slug\";s:19:\"product-edit-addons\";s:7:\"product\";s:26:\"woocommerce-product-addons\";s:14:\"show-if-active\";a:2:{i:0;s:25:\"woocommerce-subscriptions\";i:1;s:20:\"woocommerce-bookings\";}s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:106:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/product-add-ons.svg\";s:5:\"title\";s:15:\"Product Add-Ons\";s:4:\"copy\";s:93:\"Offer add-ons like gift wrapping, special messages or other special options for your products\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:49:\"https://woocommerce.com/products/product-add-ons/\";}i:4;a:9:{s:4:\"slug\";s:46:\"product-edit-woocommerce-subscriptions-gifting\";s:7:\"product\";s:33:\"woocommerce-subscriptions-gifting\";s:14:\"show-if-active\";a:1:{i:0;s:25:\"woocommerce-subscriptions\";}s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:116:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/gifting-for-subscriptions.svg\";s:5:\"title\";s:25:\"Gifting for Subscriptions\";s:4:\"copy\";s:70:\"Let customers buy subscriptions for others - they\'re the ultimate gift\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:67:\"https://woocommerce.com/products/woocommerce-subscriptions-gifting/\";}i:5;a:9:{s:4:\"slug\";s:42:\"product-edit-teams-woocommerce-memberships\";s:7:\"product\";s:33:\"woocommerce-memberships-for-teams\";s:14:\"show-if-active\";a:1:{i:0;s:23:\"woocommerce-memberships\";}s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:112:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/teams-for-memberships.svg\";s:5:\"title\";s:21:\"Teams for Memberships\";s:4:\"copy\";s:123:\"Adds B2B functionality to WooCommerce Memberships, allowing sites to sell team, group, corporate, or family member accounts\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:63:\"https://woocommerce.com/products/teams-woocommerce-memberships/\";}i:6;a:8:{s:4:\"slug\";s:29:\"product-edit-variation-images\";s:7:\"product\";s:39:\"woocommerce-additional-variation-images\";s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:118:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/additional-variation-images.svg\";s:5:\"title\";s:27:\"Additional Variation Images\";s:4:\"copy\";s:72:\"Showcase your products in the best light with a image for each variation\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:73:\"https://woocommerce.com/products/woocommerce-additional-variation-images/\";}i:7;a:9:{s:4:\"slug\";s:47:\"product-edit-woocommerce-subscription-downloads\";s:7:\"product\";s:34:\"woocommerce-subscription-downloads\";s:14:\"show-if-active\";a:1:{i:0;s:25:\"woocommerce-subscriptions\";}s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:113:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/subscription-downloads.svg\";s:5:\"title\";s:22:\"Subscription Downloads\";s:4:\"copy\";s:57:\"Give customers special downloads with their subscriptions\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:68:\"https://woocommerce.com/products/woocommerce-subscription-downloads/\";}i:8;a:8:{s:4:\"slug\";s:31:\"product-edit-min-max-quantities\";s:7:\"product\";s:30:\"woocommerce-min-max-quantities\";s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:109:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/min-max-quantities.svg\";s:5:\"title\";s:18:\"Min/Max Quantities\";s:4:\"copy\";s:81:\"Specify minimum and maximum allowed product quantities for orders to be completed\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:52:\"https://woocommerce.com/products/min-max-quantities/\";}i:9;a:8:{s:4:\"slug\";s:28:\"product-edit-name-your-price\";s:7:\"product\";s:27:\"woocommerce-name-your-price\";s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:106:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/name-your-price.svg\";s:5:\"title\";s:15:\"Name Your Price\";s:4:\"copy\";s:70:\"Let customers pay what they want - useful for donations, tips and more\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:49:\"https://woocommerce.com/products/name-your-price/\";}i:10;a:8:{s:4:\"slug\";s:42:\"product-edit-woocommerce-one-page-checkout\";s:7:\"product\";s:29:\"woocommerce-one-page-checkout\";s:7:\"context\";a:1:{i:0;s:26:\"product-edit-meta-tab-body\";}s:4:\"icon\";s:108:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/one-page-checkout.svg\";s:5:\"title\";s:17:\"One Page Checkout\";s:4:\"copy\";s:92:\"Don\'t make customers click around - let them choose products, checkout & pay all on one page\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:63:\"https://woocommerce.com/products/woocommerce-one-page-checkout/\";}i:11;a:4:{s:4:\"slug\";s:19:\"orders-empty-header\";s:7:\"context\";s:24:\"orders-list-empty-header\";s:5:\"title\";s:20:\"Tools for your store\";s:13:\"allow-dismiss\";b:0;}i:12;a:6:{s:4:\"slug\";s:30:\"orders-empty-footer-browse-all\";s:7:\"context\";s:24:\"orders-list-empty-footer\";s:9:\"link-text\";s:21:\"Browse all extensions\";s:3:\"url\";s:64:\"https://woocommerce.com/product-category/woocommerce-extensions/\";s:8:\"promoted\";s:31:\"category-woocommerce-extensions\";s:13:\"allow-dismiss\";b:0;}i:13;a:8:{s:4:\"slug\";s:19:\"orders-empty-zapier\";s:7:\"context\";s:22:\"orders-list-empty-body\";s:7:\"product\";s:18:\"woocommerce-zapier\";s:4:\"icon\";s:97:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/zapier.svg\";s:5:\"title\";s:6:\"Zapier\";s:4:\"copy\";s:88:\"Save time and increase productivity by connecting your store to more than 1000+ services\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:52:\"https://woocommerce.com/products/woocommerce-zapier/\";}i:14;a:8:{s:4:\"slug\";s:30:\"orders-empty-shipment-tracking\";s:7:\"context\";s:22:\"orders-list-empty-body\";s:7:\"product\";s:29:\"woocommerce-shipment-tracking\";s:4:\"icon\";s:108:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/shipment-tracking.svg\";s:5:\"title\";s:17:\"Shipment Tracking\";s:4:\"copy\";s:86:\"Let customers know when their orders will arrive by adding shipment tracking to emails\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:51:\"https://woocommerce.com/products/shipment-tracking/\";}i:15;a:8:{s:4:\"slug\";s:32:\"orders-empty-table-rate-shipping\";s:7:\"context\";s:22:\"orders-list-empty-body\";s:7:\"product\";s:31:\"woocommerce-table-rate-shipping\";s:4:\"icon\";s:110:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/table-rate-shipping.svg\";s:5:\"title\";s:19:\"Table Rate Shipping\";s:4:\"copy\";s:122:\"Advanced, flexible shipping. Define multiple shipping rates based on location, price, weight, shipping class or item count\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:53:\"https://woocommerce.com/products/table-rate-shipping/\";}i:16;a:8:{s:4:\"slug\";s:40:\"orders-empty-shipping-carrier-extensions\";s:7:\"context\";s:22:\"orders-list-empty-body\";s:4:\"icon\";s:118:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/shipping-carrier-extensions.svg\";s:5:\"title\";s:27:\"Shipping Carrier Extensions\";s:4:\"copy\";s:116:\"Show live rates from FedEx, UPS, USPS and more directly on your store - never under or overcharge for shipping again\";s:11:\"button-text\";s:13:\"Find Carriers\";s:8:\"promoted\";s:26:\"category-shipping-carriers\";s:3:\"url\";s:99:\"https://woocommerce.com/product-category/woocommerce-extensions/shipping-methods/shipping-carriers/\";}i:17;a:8:{s:4:\"slug\";s:32:\"orders-empty-google-product-feed\";s:7:\"context\";s:22:\"orders-list-empty-body\";s:7:\"product\";s:25:\"woocommerce-product-feeds\";s:4:\"icon\";s:110:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/google-product-feed.svg\";s:5:\"title\";s:19:\"Google Product Feed\";s:4:\"copy\";s:76:\"Increase sales by letting customers find you when they\'re shopping on Google\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:53:\"https://woocommerce.com/products/google-product-feed/\";}i:18;a:4:{s:4:\"slug\";s:35:\"products-empty-header-product-types\";s:7:\"context\";s:26:\"products-list-empty-header\";s:5:\"title\";s:23:\"Other types of products\";s:13:\"allow-dismiss\";b:0;}i:19;a:6:{s:4:\"slug\";s:32:\"products-empty-footer-browse-all\";s:7:\"context\";s:26:\"products-list-empty-footer\";s:9:\"link-text\";s:21:\"Browse all extensions\";s:3:\"url\";s:64:\"https://woocommerce.com/product-category/woocommerce-extensions/\";s:8:\"promoted\";s:31:\"category-woocommerce-extensions\";s:13:\"allow-dismiss\";b:0;}i:20;a:8:{s:4:\"slug\";s:30:\"products-empty-product-vendors\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:27:\"woocommerce-product-vendors\";s:4:\"icon\";s:106:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/product-vendors.svg\";s:5:\"title\";s:15:\"Product Vendors\";s:4:\"copy\";s:47:\"Turn your store into a multi-vendor marketplace\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:49:\"https://woocommerce.com/products/product-vendors/\";}i:21;a:8:{s:4:\"slug\";s:26:\"products-empty-memberships\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:23:\"woocommerce-memberships\";s:4:\"icon\";s:102:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/memberships.svg\";s:5:\"title\";s:11:\"Memberships\";s:4:\"copy\";s:76:\"Give members access to restricted content or products, for a fee or for free\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:57:\"https://woocommerce.com/products/woocommerce-memberships/\";}i:22;a:9:{s:4:\"slug\";s:35:\"products-empty-woocommerce-deposits\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:20:\"woocommerce-deposits\";s:14:\"show-if-active\";a:1:{i:0;s:20:\"woocommerce-bookings\";}s:4:\"icon\";s:99:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/deposits.svg\";s:5:\"title\";s:8:\"Deposits\";s:4:\"copy\";s:75:\"Make it easier for customers to pay by offering a deposit or a payment plan\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:54:\"https://woocommerce.com/products/woocommerce-deposits/\";}i:23;a:8:{s:4:\"slug\";s:40:\"products-empty-woocommerce-subscriptions\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:25:\"woocommerce-subscriptions\";s:4:\"icon\";s:104:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/subscriptions.svg\";s:5:\"title\";s:13:\"Subscriptions\";s:4:\"copy\";s:97:\"Let customers subscribe to your products or services and pay on a weekly, monthly or annual basis\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:59:\"https://woocommerce.com/products/woocommerce-subscriptions/\";}i:24;a:8:{s:4:\"slug\";s:35:\"products-empty-woocommerce-bookings\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:20:\"woocommerce-bookings\";s:4:\"icon\";s:99:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/bookings.svg\";s:5:\"title\";s:8:\"Bookings\";s:4:\"copy\";s:99:\"Allow customers to book appointments, make reservations or rent equipment without leaving your site\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:54:\"https://woocommerce.com/products/woocommerce-bookings/\";}i:25;a:8:{s:4:\"slug\";s:30:\"products-empty-product-bundles\";s:7:\"context\";s:24:\"products-list-empty-body\";s:7:\"product\";s:27:\"woocommerce-product-bundles\";s:4:\"icon\";s:106:\"https://woocommerce.com/wp-content/plugins/wccom-plugins/marketplace-suggestions/icons/product-bundles.svg\";s:5:\"title\";s:15:\"Product Bundles\";s:4:\"copy\";s:49:\"Offer customizable bundles and assembled products\";s:11:\"button-text\";s:10:\"Learn More\";s:3:\"url\";s:49:\"https://woocommerce.com/products/product-bundles/\";}}s:7:\"updated\";i:1593965535;}','no'),(731,'cron','a:22:{i:1599426133;a:1:{s:26:\"action_scheduler_run_queue\";a:1:{s:32:\"0d04ed39571b55704c122d726248bbac\";a:3:{s:8:\"schedule\";s:12:\"every_minute\";s:4:\"args\";a:1:{i:0;s:7:\"WP Cron\";}s:8:\"interval\";i:60;}}}i:1599427033;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1599427552;a:1:{s:32:\"woocommerce_cancel_unpaid_orders\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1599429229;a:1:{s:20:\"easyapp_hourly_event\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1599429600;a:1:{s:27:\"woocommerce_scheduled_sales\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599434518;a:1:{s:28:\"woocommerce_cleanup_sessions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1599439926;a:1:{s:48:\"puc_cron_check_updates-easy-appointments-connect\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1599452233;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1599495432;a:1:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599495456;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599497608;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599499328;a:2:{s:33:\"woocommerce_cleanup_personal_data\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:30:\"woocommerce_tracker_send_event\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599510118;a:1:{s:24:\"woocommerce_cleanup_logs\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1599585778;a:1:{s:25:\"woocommerce_geoip_updater\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:11:\"fifteendays\";s:4:\"args\";a:0:{}s:8:\"interval\";i:1296000;}}}i:1600888624;a:1:{s:15:\"eac_google_sync\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"eac_cron_5\";s:4:\"args\";a:0:{}s:8:\"interval\";i:300;}}}i:1602006301;a:1:{s:19:\"ea_gdpr_auto_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1602407587;a:2:{s:33:\"wc_admin_process_orders_milestone\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:14:\"wc_admin_daily\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1602407597;a:1:{s:30:\"generate_category_lookup_table\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1602407639;a:1:{s:29:\"wc_admin_unsnooze_admin_notes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1602407692;a:1:{s:28:\"wp_update_comment_type_batch\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1602493987;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}','yes'),(737,'EAC_last_cron_runtime','1599426103','yes'),(876,'action_scheduler_migration_status','complete','yes'),(891,'woocommerce_schema_version','430','yes'),(893,'woocommerce_db_version','4.3.1','yes'),(896,'wc_blocks_db_schema_version','260','yes'),(947,'woocommerce_cod_settings','a:6:{s:7:\"enabled\";s:3:\"yes\";s:5:\"title\";s:16:\"Cash on delivery\";s:11:\"description\";s:28:\"Pay with cash upon delivery.\";s:12:\"instructions\";s:28:\"Pay with cash upon delivery.\";s:18:\"enable_for_methods\";a:0:{}s:18:\"enable_for_virtual\";s:3:\"yes\";}','yes'),(948,'woocommerce_gateway_order','a:4:{s:4:\"bacs\";i:0;s:6:\"cheque\";i:1;s:3:\"cod\";i:2;s:6:\"paypal\";i:3;}','yes'),(953,'_site_transient_update_plugins','O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1602407584;s:8:\"response\";a:0:{}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:5:{s:19:\"akismet/akismet.php\";O:8:\"stdClass\":9:{s:2:\"id\";s:21:\"w.org/plugins/akismet\";s:4:\"slug\";s:7:\"akismet\";s:6:\"plugin\";s:19:\"akismet/akismet.php\";s:11:\"new_version\";s:5:\"4.1.6\";s:3:\"url\";s:38:\"https://wordpress.org/plugins/akismet/\";s:7:\"package\";s:56:\"https://downloads.wordpress.org/plugin/akismet.4.1.6.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:59:\"https://ps.w.org/akismet/assets/icon-256x256.png?rev=969272\";s:2:\"1x\";s:59:\"https://ps.w.org/akismet/assets/icon-128x128.png?rev=969272\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:61:\"https://ps.w.org/akismet/assets/banner-772x250.jpg?rev=479904\";}s:11:\"banners_rtl\";a:0:{}}s:26:\"easy-appointments/main.php\";O:8:\"stdClass\":9:{s:2:\"id\";s:31:\"w.org/plugins/easy-appointments\";s:4:\"slug\";s:17:\"easy-appointments\";s:6:\"plugin\";s:26:\"easy-appointments/main.php\";s:11:\"new_version\";s:5:\"3.0.7\";s:3:\"url\";s:48:\"https://wordpress.org/plugins/easy-appointments/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/easy-appointments.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:70:\"https://ps.w.org/easy-appointments/assets/icon-256x256.png?rev=1472759\";s:2:\"1x\";s:70:\"https://ps.w.org/easy-appointments/assets/icon-128x128.png?rev=1472759\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:73:\"https://ps.w.org/easy-appointments/assets/banner-1544x500.png?rev=2169533\";s:2:\"1x\";s:72:\"https://ps.w.org/easy-appointments/assets/banner-772x250.png?rev=2169533\";}s:11:\"banners_rtl\";a:0:{}}s:9:\"hello.php\";O:8:\"stdClass\":9:{s:2:\"id\";s:25:\"w.org/plugins/hello-dolly\";s:4:\"slug\";s:11:\"hello-dolly\";s:6:\"plugin\";s:9:\"hello.php\";s:11:\"new_version\";s:5:\"1.7.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/hello-dolly/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/hello-dolly.1.7.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-256x256.jpg?rev=2052855\";s:2:\"1x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-128x128.jpg?rev=2052855\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:66:\"https://ps.w.org/hello-dolly/assets/banner-772x250.jpg?rev=2052855\";}s:11:\"banners_rtl\";a:0:{}}s:27:\"woocommerce/woocommerce.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:25:\"w.org/plugins/woocommerce\";s:4:\"slug\";s:11:\"woocommerce\";s:6:\"plugin\";s:27:\"woocommerce/woocommerce.php\";s:11:\"new_version\";s:5:\"4.5.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/woocommerce/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/woocommerce.4.5.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-256x256.png?rev=2366418\";s:2:\"1x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-128x128.png?rev=2366418\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/woocommerce/assets/banner-1544x500.png?rev=2366418\";s:2:\"1x\";s:66:\"https://ps.w.org/woocommerce/assets/banner-772x250.png?rev=2366418\";}s:11:\"banners_rtl\";a:0:{}s:6:\"tested\";s:5:\"5.5.1\";s:12:\"requires_php\";s:3:\"7.0\";s:13:\"compatibility\";a:0:{}}s:39:\"woocommerce-admin/woocommerce-admin.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:31:\"w.org/plugins/woocommerce-admin\";s:4:\"slug\";s:17:\"woocommerce-admin\";s:6:\"plugin\";s:39:\"woocommerce-admin/woocommerce-admin.php\";s:11:\"new_version\";s:5:\"1.5.0\";s:3:\"url\";s:48:\"https://wordpress.org/plugins/woocommerce-admin/\";s:7:\"package\";s:66:\"https://downloads.wordpress.org/plugin/woocommerce-admin.1.5.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:70:\"https://ps.w.org/woocommerce-admin/assets/icon-256x256.jpg?rev=2057866\";s:2:\"1x\";s:70:\"https://ps.w.org/woocommerce-admin/assets/icon-128x128.jpg?rev=2057866\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:73:\"https://ps.w.org/woocommerce-admin/assets/banner-1544x500.jpg?rev=2057866\";s:2:\"1x\";s:72:\"https://ps.w.org/woocommerce-admin/assets/banner-772x250.jpg?rev=2057866\";}s:11:\"banners_rtl\";a:0:{}s:6:\"tested\";s:5:\"5.4.2\";s:12:\"requires_php\";s:6:\"5.6.20\";s:13:\"compatibility\";a:0:{}}}}','no'),(956,'woocommerce_version','4.4.1','yes'),(959,'_transient_wc_attribute_taxonomies','a:0:{}','yes'),(970,'_site_transient_update_themes','O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1602407586;s:7:\"checked\";a:5:{s:46:\"Bootstrap-3-blank-wordpress-theme-master-child\";s:5:\"1.2.3\";s:40:\"Bootstrap-3-blank-wordpress-theme-master\";s:3:\"1.0\";s:14:\"twentynineteen\";s:3:\"1.4\";s:15:\"twentyseventeen\";s:3:\"2.2\";s:13:\"twentysixteen\";s:3:\"2.0\";}s:8:\"response\";a:3:{s:14:\"twentynineteen\";a:6:{s:5:\"theme\";s:14:\"twentynineteen\";s:11:\"new_version\";s:3:\"1.7\";s:3:\"url\";s:44:\"https://wordpress.org/themes/twentynineteen/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/theme/twentynineteen.1.7.zip\";s:8:\"requires\";s:5:\"4.9.6\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:15:\"twentyseventeen\";a:6:{s:5:\"theme\";s:15:\"twentyseventeen\";s:11:\"new_version\";s:3:\"2.4\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentyseventeen/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentyseventeen.2.4.zip\";s:8:\"requires\";s:3:\"4.7\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:13:\"twentysixteen\";a:6:{s:5:\"theme\";s:13:\"twentysixteen\";s:11:\"new_version\";s:3:\"2.2\";s:3:\"url\";s:43:\"https://wordpress.org/themes/twentysixteen/\";s:7:\"package\";s:59:\"https://downloads.wordpress.org/theme/twentysixteen.2.2.zip\";s:8:\"requires\";s:3:\"4.4\";s:12:\"requires_php\";s:5:\"5.2.4\";}}s:12:\"translations\";a:0:{}}','no'),(975,'EAC_google_use_previous_fields','0','yes'),(1006,'EAC_paypal_on','1','yes'),(1007,'EAC_paypal_prod','','yes'),(1008,'EAC_paypal_sandbox','AY7haiAgD9jZBd4B96mxhNjr6kkm0_f5yfBTrG8kJibLa3s_IX3jURXn_c2oXljKT3LMhU25pAUnEqBy','yes'),(1009,'EAC_paypal_mode','sandbox','yes'),(1010,'EAC_paypal_currency','USD','yes'),(1011,'EAC_paypal_allow_card','1','yes'),(1012,'EAC_paypal_button_size','medium','yes'),(1013,'EAC_paypal_button_color','gold','yes'),(1014,'EAC_paypal_required','0','yes'),(1015,'EAC_paypal_smart_button','0','yes'),(1016,'EAC_paypal_prod_secret','','yes'),(1017,'EAC_paypal_sandbox_secret','','yes'),(1022,'_transient_timeout_wc_low_stock_count','1603747832','no'),(1023,'_transient_wc_low_stock_count','0','no'),(1024,'_transient_timeout_wc_outofstock_count','1603747832','no'),(1025,'_transient_wc_outofstock_count','0','no'),(1084,'_transient_timeout_wc_shipping_method_count_legacy','1604908250','no'),(1085,'_transient_wc_shipping_method_count_legacy','a:2:{s:7:\"version\";s:10:\"1590528871\";s:5:\"value\";i:0;}','no'),(1086,'_site_transient_timeout_browser_cab7c9a466b758fa6672bdcf5fd93ea2','1602921142','no'),(1087,'_site_transient_browser_cab7c9a466b758fa6672bdcf5fd93ea2','a:10:{s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:12:\"86.0.4240.75\";s:8:\"platform\";s:9:\"Macintosh\";s:10:\"update_url\";s:29:\"https://www.google.com/chrome\";s:7:\"img_src\";s:43:\"http://s.w.org/images/browsers/chrome.png?1\";s:11:\"img_src_ssl\";s:44:\"https://s.w.org/images/browsers/chrome.png?1\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}','no'),(1088,'_site_transient_timeout_php_check_a4e7a3af7060c530d791075f6e3eb5fa','1602921143','no'),(1089,'_site_transient_php_check_a4e7a3af7060c530d791075f6e3eb5fa','a:5:{s:19:\"recommended_version\";s:3:\"7.4\";s:15:\"minimum_version\";s:6:\"5.6.20\";s:12:\"is_supported\";b:1;s:9:\"is_secure\";b:1;s:13:\"is_acceptable\";b:1;}','no'),(1116,'_transient_timeout__woocommerce_helper_updates','1602448698','no'),(1117,'_transient__woocommerce_helper_updates','a:4:{s:4:\"hash\";s:32:\"d751713988987e9331980363e24189ce\";s:7:\"updated\";i:1602405498;s:8:\"products\";a:0:{}s:6:\"errors\";a:1:{i:0;s:10:\"http-error\";}}','no'),(1118,'_transient_timeout__woocommerce_helper_subscriptions','1602408466','no'),(1119,'_transient__woocommerce_helper_subscriptions','a:0:{}','no'),(1120,'_site_transient_timeout_theme_roots','1602409366','no'),(1121,'_site_transient_theme_roots','a:5:{s:46:\"Bootstrap-3-blank-wordpress-theme-master-child\";s:7:\"/themes\";s:40:\"Bootstrap-3-blank-wordpress-theme-master\";s:7:\"/themes\";s:14:\"twentynineteen\";s:7:\"/themes\";s:15:\"twentyseventeen\";s:7:\"/themes\";s:13:\"twentysixteen\";s:7:\"/themes\";}','no'),(1127,'woocommerce_admin_version','1.4.0','yes'),(1128,'woocommerce_admin_install_timestamp','1602407587','yes'),(1129,'admin_email_lifespan','1617959632','yes'),(1130,'disallowed_keys','','no'),(1131,'comment_previously_approved','1','yes'),(1132,'auto_plugin_theme_update_emails','a:0:{}','no'),(1133,'finished_updating_comment_type','0','yes'),(1134,'_site_transient_update_core','O:8:\"stdClass\":4:{s:7:\"updates\";a:1:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.5.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.5.1.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-5.5.1-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-5.5.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"5.5.1\";s:7:\"version\";s:5:\"5.5.1\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"5.3\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1602407634;s:15:\"version_checked\";s:5:\"5.5.1\";s:12:\"translations\";a:0:{}}','no'),(1135,'can_compress_scripts','0','no');
/*!40000 ALTER TABLE `wp_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
INSERT INTO `wp_postmeta` VALUES (1,2,'_wp_page_template','page_one-column.php'),(2,3,'_wp_page_template','default'),(3,2,'_edit_lock','1601490904:1'),(4,18,'_edit_lock','1557859000:1'),(6,1,'_edit_lock','1551910426:1'),(8,32,'_edit_lock','1557859174:1'),(9,18,'_wp_page_template','page_one-column.php'),(10,32,'_wp_page_template','page_one-column.php'),(11,39,'_wp_attached_file','woocommerce-placeholder.png'),(12,39,'_wp_attachment_metadata','a:5:{s:5:\"width\";i:1200;s:6:\"height\";i:1200;s:4:\"file\";s:27:\"woocommerce-placeholder.png\";s:5:\"sizes\";a:4:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:35:\"woocommerce-placeholder-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";}s:6:\"medium\";a:4:{s:4:\"file\";s:35:\"woocommerce-placeholder-300x300.png\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";}s:12:\"medium_large\";a:4:{s:4:\"file\";s:35:\"woocommerce-placeholder-768x768.png\";s:5:\"width\";i:768;s:6:\"height\";i:768;s:9:\"mime-type\";s:9:\"image/png\";}s:5:\"large\";a:4:{s:4:\"file\";s:37:\"woocommerce-placeholder-1024x1024.png\";s:5:\"width\";i:1024;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";}}s:10:\"image_meta\";a:12:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}}}'),(13,40,'_edit_last','1'),(14,40,'_edit_lock','1592050584:1'),(15,40,'total_sales','0'),(16,40,'_tax_status','taxable'),(17,40,'_tax_class',''),(18,40,'_manage_stock','no'),(19,40,'_backorders','no'),(20,40,'_sold_individually','no'),(21,40,'_virtual','yes'),(22,40,'_downloadable','no'),(23,40,'_download_limit','-1'),(24,40,'_download_expiry','-1'),(25,40,'_stock',NULL),(26,40,'_stock_status','instock'),(27,40,'_wc_average_rating','0'),(28,40,'_wc_review_count','0'),(29,40,'_product_version','4.1.1'),(30,41,'_edit_lock','1598289466:1'),(31,43,'_edit_lock','1590529012:1'),(32,40,'_regular_price','111'),(33,40,'_price','111');
/*!40000 ALTER TABLE `wp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2019-01-14 21:43:16','2019-01-14 21:43:16','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->','Hello world!','','publish','open','open','','hello-world','','','2019-03-06 22:15:57','2019-03-06 22:15:57','',0,'http://127.0.0.1:8080/?p=1',0,'post','',1),(2,1,'2019-01-14 21:43:16','2019-01-14 21:43:16','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','publish','closed','open','','demo-bootstrap','','','2020-09-30 20:35:14','2020-09-30 18:35:14','',0,'http://127.0.0.1:8080/?page_id=2',0,'page','',0),(3,1,'2019-01-14 21:43:16','2019-01-14 21:43:16','<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Our website address is: http://127.0.0.1:8080.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What personal data we collect and why we collect it</h2><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>Comments</h3><!-- /wp:heading --><!-- wp:paragraph --><p>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><!-- /wp:paragraph --><!-- wp:heading {\"level\":3} --><h3>Media</h3><!-- /wp:heading --><!-- wp:paragraph --><p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><!-- /wp:paragraph --><!-- wp:heading {\"level\":3} --><h3>Contact forms</h3><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>Cookies</h3><!-- /wp:heading --><!-- wp:paragraph --><p>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you have an account and you log in to this site, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><!-- /wp:paragraph --><!-- wp:heading {\"level\":3} --><h3>Embedded content from other websites</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><!-- /wp:paragraph --><!-- wp:heading {\"level\":3} --><h3>Analytics</h3><!-- /wp:heading --><!-- wp:heading --><h2>Who we share your data with</h2><!-- /wp:heading --><!-- wp:heading --><h2>How long we retain your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What rights you have over your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where we send your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Visitor comments may be checked through an automated spam detection service.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Your contact information</h2><!-- /wp:heading --><!-- wp:heading --><h2>Additional information</h2><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>How we protect your data</h3><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>What data breach procedures we have in place</h3><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>What third parties we receive data from</h3><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>What automated decision making and/or profiling we do with user data</h3><!-- /wp:heading --><!-- wp:heading {\"level\":3} --><h3>Industry regulatory disclosure requirements</h3><!-- /wp:heading -->','Privacy Policy','','draft','closed','open','','privacy-policy','','','2019-01-14 21:43:16','2019-01-14 21:43:16','',0,'http://127.0.0.1:8080/?page_id=3',0,'page','',0),(5,1,'2019-01-14 21:45:23','2019-01-14 21:45:23','<!-- wp:shortcode -->\n[easy-appointment]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:45:23','2019-01-14 21:45:23','',2,'http://127.0.0.1:8080/?p=5',0,'revision','',0),(6,1,'2019-01-14 21:45:34','2019-01-14 21:45:34','<!-- wp:shortcode -->\neasy-appointment\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:45:34','2019-01-14 21:45:34','',2,'http://127.0.0.1:8080/?p=6',0,'revision','',0),(7,1,'2019-01-14 21:46:02','2019-01-14 21:46:02','<!-- wp:shortcode -->\nea-bootstrap\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:46:02','2019-01-14 21:46:02','',2,'http://127.0.0.1:8080/?p=7',0,'revision','',0),(8,1,'2019-01-14 21:46:15','2019-01-14 21:46:15','<!-- wp:shortcode -->\n[ea-bootstrap]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:46:15','2019-01-14 21:46:15','',2,'http://127.0.0.1:8080/?p=8',0,'revision','',0),(9,1,'2019-01-14 21:46:39','2019-01-14 21:46:39','<!-- wp:shortcode -->\nea_bootstrap\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:46:39','2019-01-14 21:46:39','',2,'http://127.0.0.1:8080/?p=9',0,'revision','',0),(10,1,'2019-01-14 21:46:48','2019-01-14 21:46:48','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:46:48','2019-01-14 21:46:48','',2,'http://127.0.0.1:8080/?p=10',0,'revision','',0),(11,1,'2019-01-14 21:54:18','2019-01-14 21:54:18','<!-- wp:shortcode -->\n[ea_bootstrap show_week=\'1\']\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:54:18','2019-01-14 21:54:18','',2,'http://127.0.0.1:8080/?p=11',0,'revision','',0),(12,1,'2019-01-14 21:54:36','2019-01-14 21:54:36','<!-- wp:shortcode -->\n[ea_bootstrap show_week=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 21:54:36','2019-01-14 21:54:36','',2,'http://127.0.0.1:8080/?p=12',0,'revision','',0),(13,1,'2019-01-14 22:06:37','2019-01-14 22:06:37','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 22:06:37','2019-01-14 22:06:37','',2,'http://127.0.0.1:8080/?p=13',0,'revision','',0),(14,1,'2019-01-14 22:20:45','2019-01-14 22:20:45','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 22:20:45','2019-01-14 22:20:45','',2,'http://127.0.0.1:8080/?p=14',0,'revision','',0),(15,1,'2019-01-14 22:21:02','2019-01-14 22:21:02','<!-- wp:shortcode -->\n[ea_standard show_week=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 22:21:02','2019-01-14 22:21:02','',2,'http://127.0.0.1:8080/?p=15',0,'revision','',0),(16,1,'2019-01-14 22:22:19','2019-01-14 22:22:19','<!-- wp:shortcode -->\n[ea_bootstrap show_week=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://127.0.0.1:8080/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-01-14 22:22:19','2019-01-14 22:22:19','',2,'http://127.0.0.1:8080/?p=16',0,'revision','',0),(17,1,'2019-02-03 10:56:14','2019-02-03 10:56:14','<!-- wp:shortcode -->\n[ea_bootstrap show_week=\"1\"]\n<!-- /wp:shortcode -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-02-03 10:56:14','2019-02-03 10:56:14','',2,'http://127.0.0.1:8080/?p=17',0,'revision','',0),(18,1,'2019-02-24 09:13:48','2019-02-24 09:13:48','<!-- wp:shortcode -->\n[ea_full_calendar location=\"1\" worker=\"1\" service=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - FullCalendar','','publish','closed','closed','','demo-fullcalendar','','','2019-05-14 18:39:03','2019-05-14 18:39:03','',0,'http://127.0.0.1:8080/?page_id=18',0,'page','',0),(19,1,'2019-02-24 09:13:48','2019-02-24 09:13:48','<!-- wp:shortcode -->\n[ea_full_calendar]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','FullCalendar','','inherit','closed','closed','','18-revision-v1','','','2019-02-24 09:13:48','2019-02-24 09:13:48','',18,'http://127.0.0.1:8080/?p=19',0,'revision','',0),(21,1,'2019-03-06 22:15:57','2019-03-06 22:15:57','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->','Hello world!','','inherit','closed','closed','','1-revision-v1','','','2019-03-06 22:15:57','2019-03-06 22:15:57','',1,'http://127.0.0.1:8080/1-revision-v1/',0,'revision','',0),(22,1,'2019-03-06 22:15:58','2019-03-06 22:15:58','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->','Hello world!','','inherit','closed','closed','','1-autosave-v1','','','2019-03-06 22:15:58','2019-03-06 22:15:58','',1,'http://127.0.0.1:8080/1-autosave-v1/',0,'revision','',0),(23,1,'2019-03-17 12:04:15','2019-03-17 12:04:15','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-03-17 12:04:15','2019-03-17 12:04:15','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(25,1,'2019-03-17 21:24:22','2019-03-17 21:24:22','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->','Sample Page','','inherit','closed','closed','','2-revision-v1','','','2019-03-17 21:24:22','2019-03-17 21:24:22','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(28,1,'2019-04-15 20:33:50','2019-04-15 20:33:50','<!-- wp:shortcode -->\n[ea_full_calendar location=\"1\" worker=\"1\" service=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','FullCalendar','','inherit','closed','closed','','18-revision-v1','','','2019-04-15 20:33:50','2019-04-15 20:33:50','',18,'http://127.0.0.1:8080/18-revision-v1/',0,'revision','',0),(29,1,'2019-05-14 18:34:59','2019-05-14 18:34:59','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->','bootstap-shortcode','','inherit','closed','closed','','2-revision-v1','','','2019-05-14 18:34:59','2019-05-14 18:34:59','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(30,1,'2019-05-14 18:35:53','2019-05-14 18:35:53','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->','Demo','','inherit','closed','closed','','2-revision-v1','','','2019-05-14 18:35:53','2019-05-14 18:35:53','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(31,1,'2019-05-14 18:37:02','2019-05-14 18:37:02','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->','Demo page - bootstrap','','inherit','closed','closed','','2-revision-v1','','','2019-05-14 18:37:02','2019-05-14 18:37:02','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(32,1,'2019-05-14 18:38:10','2019-05-14 18:38:10','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->','Demo - Standard','','publish','closed','closed','','demo-standard','','','2019-05-14 18:41:57','2019-05-14 18:41:57','',0,'http://127.0.0.1:8080/?page_id=32',0,'page','',0),(33,1,'2019-05-14 18:38:10','2019-05-14 18:38:10','<!-- wp:shortcode -->\n[ea-standard]\n<!-- /wp:shortcode -->','Demo - standard','','inherit','closed','closed','','32-revision-v1','','','2019-05-14 18:38:10','2019-05-14 18:38:10','',32,'http://127.0.0.1:8080/32-revision-v1/',0,'revision','',0),(34,1,'2019-05-14 18:38:26','2019-05-14 18:38:26','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->','Demo - bootstrap','','inherit','closed','closed','','2-revision-v1','','','2019-05-14 18:38:26','2019-05-14 18:38:26','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(35,1,'2019-05-14 18:39:03','2019-05-14 18:39:03','<!-- wp:shortcode -->\n[ea_full_calendar location=\"1\" worker=\"1\" service=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - FullCalendar','','inherit','closed','closed','','18-revision-v1','','','2019-05-14 18:39:03','2019-05-14 18:39:03','',18,'http://127.0.0.1:8080/18-revision-v1/',0,'revision','',0),(36,1,'2019-05-14 18:39:17','2019-05-14 18:39:17','<!-- wp:shortcode -->\n[ea-standard]\n<!-- /wp:shortcode -->','Demo - Standard','','inherit','closed','closed','','32-revision-v1','','','2019-05-14 18:39:17','2019-05-14 18:39:17','',32,'http://127.0.0.1:8080/32-revision-v1/',0,'revision','',0),(37,1,'2019-05-14 18:39:24','2019-05-14 18:39:24','<!-- wp:shortcode -->\n[ea_bootstrap]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2019-05-14 18:39:24','2019-05-14 18:39:24','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(38,1,'2019-05-14 18:41:57','2019-05-14 18:41:57','<!-- wp:shortcode -->\n[ea_standard]\n<!-- /wp:shortcode -->','Demo - Standard','','inherit','closed','closed','','32-revision-v1','','','2019-05-14 18:41:57','2019-05-14 18:41:57','',32,'http://127.0.0.1:8080/32-revision-v1/',0,'revision','',0),(39,1,'2020-05-26 21:31:28','2020-05-26 21:31:28','','woocommerce-placeholder','','inherit','open','closed','','woocommerce-placeholder','','','2020-05-26 21:31:28','2020-05-26 21:31:28','',0,'http://127.0.0.1:8080/wp-content/uploads/2020/05/woocommerce-placeholder.png',0,'attachment','image/png',0),(40,1,'2020-05-26 21:32:11','2020-05-26 21:32:11','','Test proizvod','','publish','open','closed','','test-proizvod','','','2020-05-26 21:40:07','2020-05-26 21:40:07','',0,'http://127.0.0.1:8080/?post_type=product&#038;p=40',0,'product','',0),(41,1,'2020-05-26 21:35:11','2020-05-26 21:35:11','','checkout','','publish','closed','closed','','checkout','','','2020-05-26 21:35:11','2020-05-26 21:35:11','',0,'http://127.0.0.1:8080/?page_id=41',0,'page','',0),(42,1,'2020-05-26 21:35:11','2020-05-26 21:35:11','','checkout','','inherit','closed','closed','','41-revision-v1','','','2020-05-26 21:35:11','2020-05-26 21:35:11','',41,'http://127.0.0.1:8080/41-revision-v1/',0,'revision','',0),(43,1,'2020-05-26 21:35:28','2020-05-26 21:35:28','<!-- wp:shortcode -->\n[woocommerce_cart]\n<!-- /wp:shortcode -->','cart','','publish','closed','closed','','cart','','','2020-05-26 21:37:32','2020-05-26 21:37:32','',0,'http://127.0.0.1:8080/?page_id=43',0,'page','',0),(44,1,'2020-05-26 21:35:28','2020-05-26 21:35:28','','cart','','inherit','closed','closed','','43-revision-v1','','','2020-05-26 21:35:28','2020-05-26 21:35:28','',43,'http://127.0.0.1:8080/43-revision-v1/',0,'revision','',0),(45,1,'2020-05-26 21:37:32','2020-05-26 21:37:32','<!-- wp:shortcode -->\n[woocommerce_cart]\n<!-- /wp:shortcode -->','cart','','inherit','closed','closed','','43-revision-v1','','','2020-05-26 21:37:32','2020-05-26 21:37:32','',43,'http://127.0.0.1:8080/43-revision-v1/',0,'revision','',0),(46,1,'2020-06-02 20:35:55','2020-06-02 20:35:55','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:35:55','2020-06-02 20:35:55','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(47,1,'2020-06-02 20:36:07','2020-06-02 20:36:07','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\" current_day=\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:36:07','2020-06-02 20:36:07','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(48,1,'2020-06-02 20:36:43','2020-06-02 20:36:43','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\" default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:36:43','2020-06-02 20:36:43','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(49,1,'2020-06-02 20:38:22','2020-06-02 20:38:22','<!-- wp:shortcode -->\n[ea_bootstrap default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:38:22','2020-06-02 20:38:22','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(50,1,'2020-06-02 20:40:24','2020-06-02 20:40:24','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\" default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:40:24','2020-06-02 20:40:24','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(51,1,'2020-06-02 20:44:10','2020-06-02 20:44:10','<!-- wp:shortcode -->\n[ea_bootstrap default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:44:10','2020-06-02 20:44:10','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(52,1,'2020-06-02 20:47:31','2020-06-02 20:47:31','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"1\" default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:47:31','2020-06-02 20:47:31','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(53,1,'2020-06-02 20:47:56','2020-06-02 20:47:56','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\" default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:47:56','2020-06-02 20:47:56','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(54,1,'2020-06-02 20:49:27','2020-06-02 20:49:27','<!-- wp:shortcode -->\n[ea_bootstrap default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:49:27','2020-06-02 20:49:27','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(55,1,'2020-06-02 20:51:59','2020-06-02 20:51:59','<!-- wp:shortcode -->\n[ea_bootstrap cal_auto_select=\"0\" default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:51:59','2020-06-02 20:51:59','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(56,1,'2020-06-02 20:55:21','2020-06-02 20:55:21','<!-- wp:shortcode -->\n[ea_bootstrap default_date =\"+1d\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-06-02 20:55:21','2020-06-02 20:55:21','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(58,1,'2020-07-28 22:57:39','2020-07-28 20:57:39','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-07-28 22:57:39','2020-07-28 20:57:39','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(59,1,'2020-07-29 22:17:22','2020-07-29 20:17:22','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\" block_days=\"31-07-2020,03-08-2020\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-07-29 22:17:22','2020-07-29 20:17:22','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(60,1,'2020-07-29 22:27:27','2020-07-29 20:27:27','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\" block_days=\"2020-07-31,2020-08-03\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-07-29 22:27:27','2020-07-29 20:27:27','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(61,1,'2020-07-29 22:32:06','2020-07-29 20:32:06','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\" block_days=\"2020-07-31,2020-08-03\" block_days_tooltip=\"Vacation\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-07-29 22:32:06','2020-07-29 20:32:06','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(62,1,'2020-07-29 22:33:08','2020-07-29 20:33:08','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-07-29 22:33:08','2020-07-29 20:33:08','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(64,1,'2020-08-10 23:15:43','2020-08-10 21:15:43','<!-- wp:shortcode -->\n[ea_bootstrap location=\"1\" block_days=\"2020-08-11\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-08-10 23:15:43','2020-08-10 21:15:43','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(65,1,'2020-08-23 19:20:59','2020-08-23 17:20:59','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-08-23 19:20:59','2020-08-23 17:20:59','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(66,1,'2020-09-16 21:11:07','2020-09-16 19:11:07','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-09-16 21:11:07','2020-09-16 19:11:07','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(67,1,'2020-09-26 23:30:32','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2020-09-26 23:30:32','0000-00-00 00:00:00','',0,'http://127.0.0.1:8080/?p=67',0,'post','',0),(68,1,'2020-09-27 15:16:44','2020-09-27 13:16:44','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\" paypal_smart_button=\"1\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-09-27 15:16:44','2020-09-27 13:16:44','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(69,1,'2020-09-27 15:19:16','2020-09-27 13:19:16','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\" paypal_smart_button=\"0\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-09-27 15:19:16','2020-09-27 13:19:16','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(70,1,'2020-09-27 15:22:24','2020-09-27 13:22:24','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\" paypal_smart_button=\"true\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-09-27 15:22:24','2020-09-27 13:22:24','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0),(71,1,'2020-09-30 20:35:14','2020-09-30 18:35:14','<!-- wp:shortcode -->\n[ea_bootstrap block_days=\"2020-08-11\" select_placeholder=\"Select value\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->','Demo - Bootstrap','','inherit','closed','closed','','2-revision-v1','','','2020-09-30 20:35:14','2020-09-30 18:35:14','',2,'http://127.0.0.1:8080/2-revision-v1/',0,'revision','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (1,1,0),(40,2,0),(40,15,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,1),(2,2,'product_type','',0,1),(3,3,'product_type','',0,0),(4,4,'product_type','',0,0),(5,5,'product_type','',0,0),(6,6,'product_visibility','',0,0),(7,7,'product_visibility','',0,0),(8,8,'product_visibility','',0,0),(9,9,'product_visibility','',0,0),(10,10,'product_visibility','',0,0),(11,11,'product_visibility','',0,0),(12,12,'product_visibility','',0,0),(13,13,'product_visibility','',0,0),(14,14,'product_visibility','',0,0),(15,15,'product_cat','',0,1);
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_termmeta`
--

DROP TABLE IF EXISTS `wp_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_termmeta`
--

LOCK TABLES `wp_termmeta` WRITE;
/*!40000 ALTER TABLE `wp_termmeta` DISABLE KEYS */;
INSERT INTO `wp_termmeta` VALUES (1,15,'product_count_product_cat','1');
/*!40000 ALTER TABLE `wp_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'simple','simple',0),(3,'grouped','grouped',0),(4,'variable','variable',0),(5,'external','external',0),(6,'exclude-from-search','exclude-from-search',0),(7,'exclude-from-catalog','exclude-from-catalog',0),(8,'featured','featured',0),(9,'outofstock','outofstock',0),(10,'rated-1','rated-1',0),(11,'rated-2','rated-2',0),(12,'rated-3','rated-3',0),(13,'rated-4','rated-4',0),(14,'rated-5','rated-5',0),(15,'Uncategorized','uncategorized',0);
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'comment_shortcuts','false'),(8,1,'admin_color','fresh'),(9,1,'use_ssl','0'),(10,1,'show_admin_bar_front','true'),(11,1,'locale',''),(12,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(13,1,'wp_user_level','10'),(14,1,'dismissed_wp_pointers','wp496_privacy'),(15,1,'show_welcome_panel','1'),(16,1,'session_tokens','a:3:{s:64:\"cd1a46d3b089367ced1f661ecd6c29906d6d050303fa9c4a14a98d52792c251c\";a:4:{s:10:\"expiration\";i:1602488930;s:2:\"ip\";s:10:\"172.18.0.1\";s:2:\"ua\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36\";s:5:\"login\";i:1602316130;}s:64:\"3bc3b984c917cbfce3fd4a16e6288135f2cac4ecf823f48c5945a532765d26a7\";a:4:{s:10:\"expiration\";i:1602489957;s:2:\"ip\";s:10:\"172.18.0.1\";s:2:\"ua\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36\";s:5:\"login\";i:1602317157;}s:64:\"557b6a7c0bc5c59cc18603a864eb4d1273d43ea27495a83211756b535ca423ec\";a:4:{s:10:\"expiration\";i:1602578297;s:2:\"ip\";s:10:\"172.18.0.1\";s:2:\"ua\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36\";s:5:\"login\";i:1602405497;}}'),(17,1,'wp_user-settings','editor=html&libraryContent=browse'),(18,1,'wp_user-settings-time','1549191300'),(19,1,'wp_dashboard_quick_press_last_post_id','67'),(20,1,'community-events-location','a:1:{s:2:\"ip\";s:10:\"172.18.0.0\";}'),(21,1,'_woocommerce_tracks_anon_id','woo:QkEvWDrJGMKT+f6MOfMmAy/5'),(22,1,'wc_last_active','1602374400'),(23,1,'dismissed_woocommerce-admin_install_error_notice','1'),(24,1,'dismissed_no_secure_connection_notice','1'),(25,1,'_woocommerce_persistent_cart_1','a:1:{s:4:\"cart\";a:7:{s:32:\"3a74cb112ed0804ea26c79a81bb90fe5\";a:15:{s:9:\"ea_app_id\";s:2:\"91\";s:7:\"ea_date\";s:10:\"27/08/2020\";s:8:\"ea_start\";s:7:\"1:00 pm\";s:6:\"ea_end\";s:7:\"2:00 pm\";s:3:\"key\";s:32:\"3a74cb112ed0804ea26c79a81bb90fe5\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"58d070c6ecede93e6ce144c3521d5d19\";a:15:{s:9:\"ea_app_id\";s:2:\"97\";s:7:\"ea_date\";s:10:\"25/09/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"58d070c6ecede93e6ce144c3521d5d19\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"849e221f408b290ef03a1c464daaab1f\";a:15:{s:9:\"ea_app_id\";s:2:\"98\";s:7:\"ea_date\";s:10:\"30/09/2020\";s:8:\"ea_start\";s:7:\"7:00 pm\";s:6:\"ea_end\";s:7:\"8:00 pm\";s:3:\"key\";s:32:\"849e221f408b290ef03a1c464daaab1f\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"e584386c1082677abbb2b0b401c43c1a\";a:15:{s:9:\"ea_app_id\";s:2:\"99\";s:7:\"ea_date\";s:10:\"29/09/2020\";s:8:\"ea_start\";s:7:\"7:00 pm\";s:6:\"ea_end\";s:7:\"8:00 pm\";s:3:\"key\";s:32:\"e584386c1082677abbb2b0b401c43c1a\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"34fb3bb831a78664368ad972edd3f190\";a:15:{s:9:\"ea_app_id\";s:3:\"102\";s:7:\"ea_date\";s:10:\"30/09/2020\";s:8:\"ea_start\";s:7:\"2:00 pm\";s:6:\"ea_end\";s:7:\"3:00 pm\";s:3:\"key\";s:32:\"34fb3bb831a78664368ad972edd3f190\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"4749b1da6b87b404477932e46f5ac605\";a:15:{s:9:\"ea_app_id\";s:3:\"103\";s:7:\"ea_date\";s:10:\"27/09/2020\";s:8:\"ea_start\";s:7:\"4:00 pm\";s:6:\"ea_end\";s:7:\"5:00 pm\";s:3:\"key\";s:32:\"4749b1da6b87b404477932e46f5ac605\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"186bf104c4c2cc52b71f6c1a07f9822e\";a:10:{s:9:\"ea_app_id\";s:3:\"104\";s:7:\"ea_date\";s:10:\"22/10/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"186bf104c4c2cc52b71f6c1a07f9822e\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";}}}'),(28,1,'dismissed_install_notice','1');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$P$BFTSy8eRO7RZgv01XIv60Z.YoP7fs10','admin','nikolanbg@gmail.com','','2019-01-14 21:43:16','',0,'admin');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_admin_note_actions`
--

DROP TABLE IF EXISTS `wp_wc_admin_note_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_admin_note_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `note_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `query` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `actioned_text` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`action_id`),
  KEY `note_id` (`note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_admin_note_actions`
--

LOCK TABLES `wp_wc_admin_note_actions` WRITE;
/*!40000 ALTER TABLE `wp_wc_admin_note_actions` DISABLE KEYS */;
INSERT INTO `wp_wc_admin_note_actions` VALUES (1,1,'connect','Connect','?page=wc-addons&section=helper','unactioned',0,''),(4,2,'update-db_run','Update WooCommerce Database','http://127.0.0.1:8080/wp-admin/admin.php?page=easy_app_vacation&do_update_woocommerce=true&wc_db_update_nonce=bfa82a169c','unactioned',1,''),(5,2,'update-db_learn-more','Learn more about updates','https://docs.woocommerce.com/document/how-to-update-woocommerce/','unactioned',0,'');
/*!40000 ALTER TABLE `wp_wc_admin_note_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_admin_notes`
--

DROP TABLE IF EXISTS `wp_wc_admin_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_admin_notes` (
  `note_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `locale` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content_data` longtext COLLATE utf8mb4_unicode_520_ci,
  `status` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `source` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_reminder` datetime DEFAULT NULL,
  `is_snoozable` tinyint(1) NOT NULL DEFAULT '0',
  `layout` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `image` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'info',
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_admin_notes`
--

LOCK TABLES `wp_wc_admin_notes` WRITE;
/*!40000 ALTER TABLE `wp_wc_admin_notes` DISABLE KEYS */;
INSERT INTO `wp_wc_admin_notes` VALUES (1,'wc-admin-wc-helper-connection','info','en_US','Connect to WooCommerce.com','Connect to get important product notifications and updates.','{}','unactioned','woocommerce-admin','2020-10-11 09:13:54',NULL,0,'plain','',0,'info'),(2,'wc-update-db-reminder','update','en_US','WooCommerce database update required','WooCommerce has been updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient. Advanced users can alternatively update via <a href=\"https://github.com/woocommerce/woocommerce/wiki/Upgrading-the-database-using-WP-CLI\">WP CLI</a>.','{}','unactioned','woocommerce-core','2020-10-11 07:13:54',NULL,0,'plain','',0,'info');
/*!40000 ALTER TABLE `wp_wc_admin_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_category_lookup`
--

DROP TABLE IF EXISTS `wp_wc_category_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_category_lookup` (
  `category_tree_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`category_tree_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_category_lookup`
--

LOCK TABLES `wp_wc_category_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_category_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_category_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_customer_lookup`
--

DROP TABLE IF EXISTS `wp_wc_customer_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_customer_lookup` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date_last_active` timestamp NULL DEFAULT NULL,
  `date_registered` timestamp NULL DEFAULT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `postcode` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `city` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `state` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_customer_lookup`
--

LOCK TABLES `wp_wc_customer_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_customer_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_customer_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_download_log`
--

DROP TABLE IF EXISTS `wp_wc_download_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_download_log` (
  `download_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_ip_address` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  PRIMARY KEY (`download_log_id`),
  KEY `permission_id` (`permission_id`),
  KEY `timestamp` (`timestamp`),
  CONSTRAINT `fk_wp_wc_download_log_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `wp_woocommerce_downloadable_product_permissions` (`permission_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_download_log`
--

LOCK TABLES `wp_wc_download_log` WRITE;
/*!40000 ALTER TABLE `wp_wc_download_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_download_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_coupon_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_coupon_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_coupon_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `coupon_id` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`,`coupon_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_coupon_lookup`
--

LOCK TABLES `wp_wc_order_coupon_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_coupon_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_coupon_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_product_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_product_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_product_lookup` (
  `order_item_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variation_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_qty` int(11) NOT NULL,
  `product_net_revenue` double NOT NULL DEFAULT '0',
  `product_gross_revenue` double NOT NULL DEFAULT '0',
  `coupon_amount` double NOT NULL DEFAULT '0',
  `tax_amount` double NOT NULL DEFAULT '0',
  `shipping_amount` double NOT NULL DEFAULT '0',
  `shipping_tax_amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_product_lookup`
--

LOCK TABLES `wp_wc_order_product_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_product_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_product_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_stats`
--

DROP TABLE IF EXISTS `wp_wc_order_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_stats` (
  `order_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_items_sold` int(11) NOT NULL DEFAULT '0',
  `total_sales` double NOT NULL DEFAULT '0',
  `tax_total` double NOT NULL DEFAULT '0',
  `shipping_total` double NOT NULL DEFAULT '0',
  `net_total` double NOT NULL DEFAULT '0',
  `returning_customer` tinyint(1) DEFAULT NULL,
  `status` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `date_created` (`date_created`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_stats`
--

LOCK TABLES `wp_wc_order_stats` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_tax_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_tax_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_tax_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shipping_tax` double NOT NULL DEFAULT '0',
  `order_tax` double NOT NULL DEFAULT '0',
  `total_tax` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`,`tax_rate_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_tax_lookup`
--

LOCK TABLES `wp_wc_order_tax_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_tax_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_tax_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_product_meta_lookup`
--

DROP TABLE IF EXISTS `wp_wc_product_meta_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_product_meta_lookup` (
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `virtual` tinyint(1) DEFAULT '0',
  `downloadable` tinyint(1) DEFAULT '0',
  `min_price` decimal(19,4) DEFAULT NULL,
  `max_price` decimal(19,4) DEFAULT NULL,
  `onsale` tinyint(1) DEFAULT '0',
  `stock_quantity` double DEFAULT NULL,
  `stock_status` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT 'instock',
  `rating_count` bigint(20) DEFAULT '0',
  `average_rating` decimal(3,2) DEFAULT '0.00',
  `total_sales` bigint(20) DEFAULT '0',
  `tax_status` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT 'taxable',
  `tax_class` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  PRIMARY KEY (`product_id`),
  KEY `virtual` (`virtual`),
  KEY `downloadable` (`downloadable`),
  KEY `stock_status` (`stock_status`),
  KEY `stock_quantity` (`stock_quantity`),
  KEY `onsale` (`onsale`),
  KEY `min_max_price` (`min_price`,`max_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_product_meta_lookup`
--

LOCK TABLES `wp_wc_product_meta_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_product_meta_lookup` DISABLE KEYS */;
INSERT INTO `wp_wc_product_meta_lookup` VALUES (40,'',1,0,111.0000,111.0000,0,NULL,'instock',0,0.00,0,'taxable','');
/*!40000 ALTER TABLE `wp_wc_product_meta_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_reserved_stock`
--

DROP TABLE IF EXISTS `wp_wc_reserved_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_reserved_stock` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `stock_quantity` double NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_reserved_stock`
--

LOCK TABLES `wp_wc_reserved_stock` WRITE;
/*!40000 ALTER TABLE `wp_wc_reserved_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_reserved_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_tax_rate_classes`
--

DROP TABLE IF EXISTS `wp_wc_tax_rate_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_tax_rate_classes` (
  `tax_rate_class_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_class_id`),
  UNIQUE KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_tax_rate_classes`
--

LOCK TABLES `wp_wc_tax_rate_classes` WRITE;
/*!40000 ALTER TABLE `wp_wc_tax_rate_classes` DISABLE KEYS */;
INSERT INTO `wp_wc_tax_rate_classes` VALUES (1,'Reduced rate','reduced-rate'),(2,'Zero rate','zero-rate');
/*!40000 ALTER TABLE `wp_wc_tax_rate_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_webhooks`
--

DROP TABLE IF EXISTS `wp_wc_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_webhooks` (
  `webhook_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `delivery_url` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `secret` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `topic` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `api_version` smallint(4) NOT NULL,
  `failure_count` smallint(10) NOT NULL DEFAULT '0',
  `pending_delivery` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`webhook_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_webhooks`
--

LOCK TABLES `wp_wc_webhooks` WRITE;
/*!40000 ALTER TABLE `wp_wc_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_api_keys`
--

DROP TABLE IF EXISTS `wp_woocommerce_api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_api_keys` (
  `key_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `permissions` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `consumer_key` char(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `consumer_secret` char(43) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nonces` longtext COLLATE utf8mb4_unicode_520_ci,
  `truncated_key` char(7) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `consumer_key` (`consumer_key`),
  KEY `consumer_secret` (`consumer_secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_api_keys`
--

LOCK TABLES `wp_woocommerce_api_keys` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_attribute_taxonomies`
--

DROP TABLE IF EXISTS `wp_woocommerce_attribute_taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `attribute_label` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `attribute_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `attribute_orderby` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_attribute_taxonomies`
--

LOCK TABLES `wp_woocommerce_attribute_taxonomies` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_attribute_taxonomies` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_attribute_taxonomies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_downloadable_product_permissions`
--

DROP TABLE IF EXISTS `wp_woocommerce_downloadable_product_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_id` varchar(36) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `order_key` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_email` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `downloads_remaining` varchar(9) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`),
  KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  KEY `download_order_product` (`download_id`,`order_id`,`product_id`),
  KEY `order_id` (`order_id`),
  KEY `user_order_remaining_expires` (`user_id`,`order_id`,`downloads_remaining`,`access_expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_downloadable_product_permissions`
--

LOCK TABLES `wp_woocommerce_downloadable_product_permissions` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_downloadable_product_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_downloadable_product_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_log`
--

DROP TABLE IF EXISTS `wp_woocommerce_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `level` smallint(4) NOT NULL,
  `source` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `context` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`log_id`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_log`
--

LOCK TABLES `wp_woocommerce_log` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_order_itemmeta`
--

DROP TABLE IF EXISTS `wp_woocommerce_order_itemmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_order_itemmeta`
--

LOCK TABLES `wp_woocommerce_order_itemmeta` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_order_itemmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_order_itemmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_order_items`
--

DROP TABLE IF EXISTS `wp_woocommerce_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_order_items` (
  `order_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_name` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `order_item_type` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `order_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_order_items`
--

LOCK TABLES `wp_woocommerce_order_items` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_payment_tokenmeta`
--

DROP TABLE IF EXISTS `wp_woocommerce_payment_tokenmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_payment_tokenmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_token_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `payment_token_id` (`payment_token_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_payment_tokenmeta`
--

LOCK TABLES `wp_woocommerce_payment_tokenmeta` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokenmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokenmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_payment_tokens`
--

DROP TABLE IF EXISTS `wp_woocommerce_payment_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_payment_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_payment_tokens`
--

LOCK TABLES `wp_woocommerce_payment_tokens` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_sessions`
--

DROP TABLE IF EXISTS `wp_woocommerce_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_sessions` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` char(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `session_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `session_expiry` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_key` (`session_key`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_sessions`
--

LOCK TABLES `wp_woocommerce_sessions` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_sessions` DISABLE KEYS */;
INSERT INTO `wp_woocommerce_sessions` VALUES (11,'1','a:7:{s:4:\"cart\";s:4165:\"a:8:{s:32:\"3a74cb112ed0804ea26c79a81bb90fe5\";a:15:{s:9:\"ea_app_id\";s:2:\"91\";s:7:\"ea_date\";s:10:\"27/08/2020\";s:8:\"ea_start\";s:7:\"1:00 pm\";s:6:\"ea_end\";s:7:\"2:00 pm\";s:3:\"key\";s:32:\"3a74cb112ed0804ea26c79a81bb90fe5\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"58d070c6ecede93e6ce144c3521d5d19\";a:15:{s:9:\"ea_app_id\";s:2:\"97\";s:7:\"ea_date\";s:10:\"25/09/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"58d070c6ecede93e6ce144c3521d5d19\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"849e221f408b290ef03a1c464daaab1f\";a:15:{s:9:\"ea_app_id\";s:2:\"98\";s:7:\"ea_date\";s:10:\"30/09/2020\";s:8:\"ea_start\";s:7:\"7:00 pm\";s:6:\"ea_end\";s:7:\"8:00 pm\";s:3:\"key\";s:32:\"849e221f408b290ef03a1c464daaab1f\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"e584386c1082677abbb2b0b401c43c1a\";a:15:{s:9:\"ea_app_id\";s:2:\"99\";s:7:\"ea_date\";s:10:\"29/09/2020\";s:8:\"ea_start\";s:7:\"7:00 pm\";s:6:\"ea_end\";s:7:\"8:00 pm\";s:3:\"key\";s:32:\"e584386c1082677abbb2b0b401c43c1a\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"34fb3bb831a78664368ad972edd3f190\";a:15:{s:9:\"ea_app_id\";s:3:\"102\";s:7:\"ea_date\";s:10:\"30/09/2020\";s:8:\"ea_start\";s:7:\"2:00 pm\";s:6:\"ea_end\";s:7:\"3:00 pm\";s:3:\"key\";s:32:\"34fb3bb831a78664368ad972edd3f190\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"4749b1da6b87b404477932e46f5ac605\";a:15:{s:9:\"ea_app_id\";s:3:\"103\";s:7:\"ea_date\";s:10:\"27/09/2020\";s:8:\"ea_start\";s:7:\"4:00 pm\";s:6:\"ea_end\";s:7:\"5:00 pm\";s:3:\"key\";s:32:\"4749b1da6b87b404477932e46f5ac605\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"186bf104c4c2cc52b71f6c1a07f9822e\";a:15:{s:9:\"ea_app_id\";s:3:\"104\";s:7:\"ea_date\";s:10:\"22/10/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"186bf104c4c2cc52b71f6c1a07f9822e\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}s:32:\"c214c7c2bdd38b6ea75a68adcb50343a\";a:15:{s:9:\"ea_app_id\";s:3:\"105\";s:7:\"ea_date\";s:10:\"16/10/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"c214c7c2bdd38b6ea75a68adcb50343a\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}}\";s:11:\"cart_totals\";s:408:\"a:15:{s:8:\"subtotal\";s:6:\"888.00\";s:12:\"subtotal_tax\";d:0;s:14:\"shipping_total\";s:4:\"0.00\";s:12:\"shipping_tax\";i:0;s:14:\"shipping_taxes\";a:0:{}s:14:\"discount_total\";i:0;s:12:\"discount_tax\";i:0;s:19:\"cart_contents_total\";s:6:\"888.00\";s:17:\"cart_contents_tax\";i:0;s:19:\"cart_contents_taxes\";a:0:{}s:9:\"fee_total\";s:4:\"0.00\";s:7:\"fee_tax\";i:0;s:9:\"fee_taxes\";a:0:{}s:5:\"total\";s:6:\"888.00\";s:9:\"total_tax\";d:0;}\";s:15:\"applied_coupons\";s:6:\"a:0:{}\";s:22:\"coupon_discount_totals\";s:6:\"a:0:{}\";s:26:\"coupon_discount_tax_totals\";s:6:\"a:0:{}\";s:21:\"removed_cart_contents\";s:6:\"a:0:{}\";s:8:\"customer\";s:707:\"a:26:{s:2:\"id\";s:1:\"1\";s:13:\"date_modified\";s:0:\"\";s:8:\"postcode\";s:0:\"\";s:4:\"city\";s:0:\"\";s:9:\"address_1\";s:0:\"\";s:7:\"address\";s:0:\"\";s:9:\"address_2\";s:0:\"\";s:5:\"state\";s:0:\"\";s:7:\"country\";s:2:\"GB\";s:17:\"shipping_postcode\";s:0:\"\";s:13:\"shipping_city\";s:0:\"\";s:18:\"shipping_address_1\";s:0:\"\";s:16:\"shipping_address\";s:0:\"\";s:18:\"shipping_address_2\";s:0:\"\";s:14:\"shipping_state\";s:0:\"\";s:16:\"shipping_country\";s:2:\"GB\";s:13:\"is_vat_exempt\";s:0:\"\";s:19:\"calculated_shipping\";s:0:\"\";s:10:\"first_name\";s:0:\"\";s:9:\"last_name\";s:0:\"\";s:7:\"company\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:5:\"email\";s:19:\"nikolanbg@gmail.com\";s:19:\"shipping_first_name\";s:0:\"\";s:18:\"shipping_last_name\";s:0:\"\";s:16:\"shipping_company\";s:0:\"\";}\";}',1602489049),(13,'1f94ce990aaf50628b91e9ff6fa7541f','a:7:{s:4:\"cart\";s:527:\"a:1:{s:32:\"c214c7c2bdd38b6ea75a68adcb50343a\";a:15:{s:9:\"ea_app_id\";s:3:\"105\";s:7:\"ea_date\";s:10:\"16/10/2020\";s:8:\"ea_start\";s:8:\"12:00 pm\";s:6:\"ea_end\";s:7:\"1:00 pm\";s:3:\"key\";s:32:\"c214c7c2bdd38b6ea75a68adcb50343a\";s:10:\"product_id\";i:40;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";s:13:\"line_tax_data\";a:2:{s:8:\"subtotal\";a:0:{}s:5:\"total\";a:0:{}}s:13:\"line_subtotal\";d:111;s:17:\"line_subtotal_tax\";i:0;s:10:\"line_total\";d:111;s:8:\"line_tax\";i:0;}}\";s:11:\"cart_totals\";s:408:\"a:15:{s:8:\"subtotal\";s:6:\"111.00\";s:12:\"subtotal_tax\";d:0;s:14:\"shipping_total\";s:4:\"0.00\";s:12:\"shipping_tax\";i:0;s:14:\"shipping_taxes\";a:0:{}s:14:\"discount_total\";i:0;s:12:\"discount_tax\";i:0;s:19:\"cart_contents_total\";s:6:\"111.00\";s:17:\"cart_contents_tax\";i:0;s:19:\"cart_contents_taxes\";a:0:{}s:9:\"fee_total\";s:4:\"0.00\";s:7:\"fee_tax\";i:0;s:9:\"fee_taxes\";a:0:{}s:5:\"total\";s:6:\"111.00\";s:9:\"total_tax\";d:0;}\";s:15:\"applied_coupons\";s:6:\"a:0:{}\";s:22:\"coupon_discount_totals\";s:6:\"a:0:{}\";s:26:\"coupon_discount_tax_totals\";s:6:\"a:0:{}\";s:21:\"removed_cart_contents\";s:6:\"a:0:{}\";s:8:\"customer\";s:687:\"a:26:{s:2:\"id\";s:1:\"0\";s:13:\"date_modified\";s:0:\"\";s:8:\"postcode\";s:0:\"\";s:4:\"city\";s:0:\"\";s:9:\"address_1\";s:0:\"\";s:7:\"address\";s:0:\"\";s:9:\"address_2\";s:0:\"\";s:5:\"state\";s:0:\"\";s:7:\"country\";s:2:\"GB\";s:17:\"shipping_postcode\";s:0:\"\";s:13:\"shipping_city\";s:0:\"\";s:18:\"shipping_address_1\";s:0:\"\";s:16:\"shipping_address\";s:0:\"\";s:18:\"shipping_address_2\";s:0:\"\";s:14:\"shipping_state\";s:0:\"\";s:16:\"shipping_country\";s:2:\"GB\";s:13:\"is_vat_exempt\";s:0:\"\";s:19:\"calculated_shipping\";s:0:\"\";s:10:\"first_name\";s:0:\"\";s:9:\"last_name\";s:0:\"\";s:7:\"company\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:5:\"email\";s:0:\"\";s:19:\"shipping_first_name\";s:0:\"\";s:18:\"shipping_last_name\";s:0:\"\";s:16:\"shipping_company\";s:0:\"\";}\";}',1602178057);
/*!40000 ALTER TABLE `wp_woocommerce_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zone_locations`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zone_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zone_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` bigint(20) unsigned NOT NULL,
  `location_code` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `location_type` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `location_id` (`location_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zone_locations`
--

LOCK TABLES `wp_woocommerce_shipping_zone_locations` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zone_methods`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zone_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) unsigned NOT NULL,
  `instance_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_id` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `method_order` bigint(20) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zone_methods`
--

LOCK TABLES `wp_woocommerce_shipping_zone_methods` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zones`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zones` (
  `zone_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `zone_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zones`
--

LOCK TABLES `wp_woocommerce_shipping_zones` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_tax_rate_locations`
--

DROP TABLE IF EXISTS `wp_woocommerce_tax_rate_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_code` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `location_type` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_tax_rate_locations`
--

LOCK TABLES `wp_woocommerce_tax_rate_locations` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rate_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rate_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_tax_rates`
--

DROP TABLE IF EXISTS `wp_woocommerce_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_rate_country` varchar(2) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `tax_rate` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) unsigned NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT '0',
  `tax_rate_shipping` int(1) NOT NULL DEFAULT '1',
  `tax_rate_order` bigint(20) unsigned NOT NULL,
  `tax_rate_class` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_id`),
  KEY `tax_rate_country` (`tax_rate_country`),
  KEY `tax_rate_state` (`tax_rate_state`(2)),
  KEY `tax_rate_class` (`tax_rate_class`(10)),
  KEY `tax_rate_priority` (`tax_rate_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_tax_rates`
--

LOCK TABLES `wp_woocommerce_tax_rates` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 11:18:50
