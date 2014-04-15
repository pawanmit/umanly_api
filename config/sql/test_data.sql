# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.15)
# Database: umanly
# Generation Time: 2014-04-15 21:37:41 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table chat_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_status`;

CREATE TABLE `chat_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(25) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `chat_status` WRITE;
/*!40000 ALTER TABLE `chat_status` DISABLE KEYS */;

INSERT INTO `chat_status` (`id`, `status`, `description`)
VALUES
	(1,'waiting_for_response','User sent chat request to another user and is awaiting response.'),
	(2,'waiting_to_reply','User has received chat request but has not accepted or denied it yet.'),
	(3,'chatting','User is chatting with another user.'),
	(4,'available','User is available for chat'),
	(5,'unavailable','User is unavailable to chat');

/*!40000 ALTER TABLE `chat_status` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `longitude` decimal(11,8) NOT NULL DEFAULT '-1.00000000',
  `latitude` decimal(10,8) NOT NULL DEFAULT '-1.00000000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_location_idx` (`user_id`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;

INSERT INTO `location` (`id`, `user_id`, `longitude`, `latitude`)
VALUES
	(1,1,-122.40599100,37.78558700),
	(2,2,-122.39974090,37.79063820);

/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `hometown` varchar(100) DEFAULT NULL,
  `facebook_link` varchar(100) DEFAULT NULL,
  `facebook_username` varchar(255) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `availability` tinyint(1) DEFAULT '1',
  `birthday` varchar(12) DEFAULT NULL,
  `chat_status` varchar(25) NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_idx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `first_name`, `last_name`, `gender`, `hometown`, `facebook_link`, `facebook_username`, `password`, `availability`, `birthday`, `chat_status`)
VALUES
	(1,'mitpawan@gmail.com','Pawan','Mittal','male','Dublin, California','https://www.facebook.com/pawanmittal','pawanmittal','',1,'11/26/1978','available'),
	(2,'anamika.grad@gmail.com','Anamika','Aggarwal','female','Punjab','https://www.facebook.com/anamika.aggarwal.182','anamika.aggarwal.182','',1,'09/22/1982','available'),
	(4,'pawan_mittal@gmail.com','SuperMan','mittal','male','delhi','www.wired.com',NULL,'',0,NULL,'available');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
