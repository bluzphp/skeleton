/*
Navicat MySQL Data Transfer

Source Server         : connect for root(seryogin-ubnt.php.nixsolutions.com)
Source Server Version : 50537
Source Host           : localhost:3306
Source Database       : bluz.test

Target Server Type    : MYSQL
Target Server Version : 50537
File Encoding         : 65001

Date: 2014-06-17 17:07:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for media
-- ----------------------------
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) unsigned NOT NULL,
  `module` varchar(24) NOT NULL DEFAULT 'users',
  `title` longtext,
  `type` varchar(24) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `preview` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_users` (`userId`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=737 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of media
-- ----------------------------

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(512) DEFAULT NULL,
  `status` enum('active','disable','delete') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test
-- ----------------------------
INSERT INTO `test` VALUES ('10', 'Jonah', 'dictum@pharetra.ca', 'disable');
INSERT INTO `test` VALUES ('11', 'Connor', 'congue.In.scelerisque@Integervulputaterisus.ca', 'disable');
INSERT INTO `test` VALUES ('12', 'Jessica', 'imperdiet.ornare@iaculisnec.com', 'delete');
INSERT INTO `test` VALUES ('13', 'Derek', 'sollicitudin@morbitristique.edu', 'disable');
INSERT INTO `test` VALUES ('14', 'Daniel', 'dui@at.com', 'disable');
INSERT INTO `test` VALUES ('15', 'Lev', 'id@laciniaSedcongue.ca', 'active');
INSERT INTO `test` VALUES ('16', 'Aquila', 'ac@accumsanconvallis.edu', 'disable');
INSERT INTO `test` VALUES ('17', 'Morgan', 'facilisis.vitae.orci@felispurusac.edu', 'delete');
INSERT INTO `test` VALUES ('18', 'Libby', 'porttitor@Etiamligula.ca', 'disable');
INSERT INTO `test` VALUES ('19', 'Brian', 'vitae.aliquam@sollicitudinadipiscingligula.ca', 'delete');
INSERT INTO `test` VALUES ('20', 'Uriel', 'ipsum.nunc@ametnulla.org', 'delete');
INSERT INTO `test` VALUES ('21', 'Azalia Two', 'at@enimconsequatpurus.ca', 'delete');
INSERT INTO `test` VALUES ('22', 'Karina', 'eu.eros@nonummy.org', 'disable');
INSERT INTO `test` VALUES ('23', 'Samuel', 'tellus@Seddiamlorem.org', 'delete');
INSERT INTO `test` VALUES ('24', 'Urielle', 'mattis.Integer@Donec.com', 'active');
INSERT INTO `test` VALUES ('25', 'Jamal', 'adipiscing.elit.Etiam@consectetueradipiscing.ca', 'disable');
INSERT INTO `test` VALUES ('26', 'Garrison', 'urna.Nullam@Quisque.org', 'delete');
INSERT INTO `test` VALUES ('27', 'Skyler', 'placerat.Cras.dictum@tempor.org', 'disable');
INSERT INTO `test` VALUES ('28', 'Alexa', 'Nullam.enim@lacusvariuset.edu', 'delete');
INSERT INTO `test` VALUES ('29', 'Zena', 'nec.leo@nislarcuiaculis.com', 'disable');
INSERT INTO `test` VALUES ('30', 'Mary', 'sit.amet@vehicularisusNulla.ca', 'active');
INSERT INTO `test` VALUES ('31', 'Raven', 'Donec@tellus.ca', 'active');
INSERT INTO `test` VALUES ('32', 'Leigh', 'sem@nonfeugiat.ca', 'disable');
INSERT INTO `test` VALUES ('33', 'Ginger', 'Integer.mollis.Integer@vitaeorci.edu', 'delete');
INSERT INTO `test` VALUES ('34', 'Leonard', 'neque@malesuadafames.ca', 'active');
INSERT INTO `test` VALUES ('35', 'Abdul', 'aliquam.arcu@tinciduntorci.org', 'disable');
INSERT INTO `test` VALUES ('36', 'Robin', 'lacus.Etiam.bibendum@lectus.com', 'delete');
INSERT INTO `test` VALUES ('37', 'Elaine', 'dis.parturient@Aeneansed.ca', 'disable');
INSERT INTO `test` VALUES ('38', 'Allistair', 'amet.metus@Mauris.com', 'disable');
INSERT INTO `test` VALUES ('39', 'Alika', 'Lorem@velquam.com', 'active');
INSERT INTO `test` VALUES ('40', 'Wylie', 'dis.parturient@dolornonummy.edu', 'disable');
INSERT INTO `test` VALUES ('41', 'Lareina', 'et.rutrum@mi.org', 'delete');
INSERT INTO `test` VALUES ('42', 'Aurelia', 'augue.porttitor@vitaevelit.com', 'active');
INSERT INTO `test` VALUES ('43', 'Ivor', 'vitae.semper.egestas@egestas.edu', 'disable');
INSERT INTO `test` VALUES ('44', 'Mikayla', 'Nunc.ullamcorper@orcisem.com', 'active');
INSERT INTO `test` VALUES ('45', 'Nola', 'eget.lacus@tristique.org', 'delete');
INSERT INTO `test` VALUES ('46', 'Angela', 'Etiam.imperdiet.dictum@rhoncusProinnisl.com', 'active');
INSERT INTO `test` VALUES ('47', 'Dante', 'egestas.Aliquam.fringilla@Curabiturdictum.org', 'active');
INSERT INTO `test` VALUES ('48', 'Sybill', 'mauris@sodales.com', 'disable');
INSERT INTO `test` VALUES ('49', 'Quentin', 'molestie.in@felisNullatempor.org', 'disable');
INSERT INTO `test` VALUES ('50', 'Hyacinth', 'egestas.a@vestibulumnec.org', 'delete');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` char(8) NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', null, '2012-11-09 07:38:41', '2014-06-04 11:51:07', 'active');
