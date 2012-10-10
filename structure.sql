/*
SQLyog Enterprise v10.3 
MySQL - 5.1.56-log : Database - p_bluz
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `acl_privileges` */

CREATE TABLE `acl_privileges` (
  `roleId` bigint(20) NOT NULL,
  `module` varchar(255) NOT NULL,
  `privilege` varchar(255) NOT NULL,
  UNIQUE KEY `role_privilege` (`roleId`,`module`,`privilege`),
  CONSTRAINT `FK_acl_privileges_2_acl_roles` FOREIGN KEY (`roleId`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `acl_roles` */

CREATE TABLE `acl_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Group name for virtual',
  PRIMARY KEY (`id`,`name`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `acl_usersToRoles` */

CREATE TABLE `acl_usersToRoles` (
  `userId` bigint(20) NOT NULL,
  `roleId` bigint(20) NOT NULL,
  PRIMARY KEY (`userId`,`roleId`),
  KEY `FK_acl_2_roles` (`roleId`),
  CONSTRAINT `FK_acl_2_roles` FOREIGN KEY (`roleId`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_acl_2_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `pages` */

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `alias` varchar(255) NOT NULL COMMENT 'Key for permalinks',
  `content` longtext,
  `keywords` text COMMENT 'Meta Keywords',
  `description` text COMMENT 'Meta Description',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userId` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Author, can be zero',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`alias`),
  KEY `FK_pages_to_users` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `rcl_userToResource` */

CREATE TABLE `rcl_userToResource` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `resourceId` bigint(20) DEFAULT NULL,
  `resourceType` varchar(255) NOT NULL,
  `userId` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `rcl_userToResourceToPrivilege` */

CREATE TABLE `rcl_userToResourceToPrivilege` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `resourceId` bigint(20) DEFAULT NULL,
  `resourceType` varchar(255) DEFAULT NULL,
  `userId` bigint(20) NOT NULL,
  `privilege` varchar(255) NOT NULL,
  `flag` enum('allow','deny') NOT NULL DEFAULT 'deny',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(512) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` enum('active','pending','disabled') NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
