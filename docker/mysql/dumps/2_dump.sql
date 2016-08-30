# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.27)
# Database: bluz
# Generation Time: 2012-11-09 06:45:40 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table acl_privileges
# ------------------------------------------------------------

LOCK TABLES `acl_privileges` WRITE;
/*!40000 ALTER TABLE `acl_privileges` DISABLE KEYS */;

INSERT INTO `acl_privileges` (`roleId`, `module`, `privilege`)
VALUES
  (2,'acl','Management'),
  (2,'acl','View'),
  (2,'cache','Management'),
  (2,'categories','Management'),
  (2,'dashboard','Dashboard'),
  (2,'media','Management'),
  (2,'media','Upload'),
  (2,'options','Management'),
  (2,'pages','Management'),
  (2,'system','Info'),
  (2,'users','EditEmail'),
  (2,'users','EditPassword'),
  (2,'users','Management'),
  (2,'users','ViewProfile'),
  (3,'media','Upload'),
  (3,'users','EditEmail'),
  (3,'users','EditPassword'),
  (3,'users','ViewProfile'),
  (4,'users','ViewProfile')
;

/*!40000 ALTER TABLE `acl_privileges` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table acl_roles
# ------------------------------------------------------------

LOCK TABLES `acl_roles` WRITE;
/*!40000 ALTER TABLE `acl_roles` DISABLE KEYS */;

INSERT INTO `acl_roles` (`id`, `name`, `created`)
VALUES
	(1,'system','2012-11-09 07:37:31'),
  (2,'admin','2012-11-09 07:37:33'),
	(3,'member','2012-11-09 07:37:37'),
	(4,'guest','2012-11-09 07:37:44');

/*!40000 ALTER TABLE `acl_roles` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table acl_users_roles
# ------------------------------------------------------------

LOCK TABLES `acl_users_roles` WRITE;
/*!40000 ALTER TABLE `acl_users_roles` DISABLE KEYS */;

INSERT INTO `acl_users_roles` (`userId`, `roleId`)
VALUES
	(1,1),
	(2,2);

/*!40000 ALTER TABLE `acl_users_roles` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table auth
# ------------------------------------------------------------

LOCK TABLES `auth` WRITE;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;

INSERT INTO `auth` (`userId`, `provider`, `foreignKey`, `token`, `tokenSecret`, `tokenType`, `created`)
VALUES
	(2,'equals','admin','$2y$10$4a454775178c3f89d510fud2T.xtw01Ir.Jo.91Dr3nL2sz3OyVpK','','access','2012-11-09 07:40:46');

/*!40000 ALTER TABLE `auth` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table com_content
# ------------------------------------------------------------

# Dump of table com_settings
# ------------------------------------------------------------

# Dump of table pages
# ------------------------------------------------------------
insert into `pages` (`id`, `title`, `alias`, `content`, `keywords`, `description`, `created`, `updated`, `userId`) values('1','About Bluz Framework','about','<p>Bluz Lightweight PHP Framework!</p>','about bluz framework','About Bluz','2012-04-09 18:34:03','2014-05-12 11:01:03','0');

# Dump of table users
# ------------------------------------------------------------

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `login`, `email`, `created`, `updated`, `status`)
VALUES
	(1,'system',NULL,'2012-11-09 07:37:58',NULL,'disabled'),
	(2,'admin',NULL,'2012-11-09 07:38:41',NULL,'active');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
