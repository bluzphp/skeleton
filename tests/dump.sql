/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

INSERT INTO `auth` (`userId`, `provider`, `foreignKey`, `token`, `tokenSecret`, `tokenType`, `created`, `updated`, `expired`)
VALUES ('2','token','admin','$2y$10$wkZxb1sp8TsRXNL2s5KjGuFD58hGJQy4oyihm8xo7OBtV2uH7hQUu','c8ab812795bb6a2784e30527d5b167fc','access','2015-01-01 00:00:00',NULL,'2025-01-01 00:00:00');

