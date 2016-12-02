<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
use Phinx\Migration\AbstractMigration;

/**
 * InitDb
 */
class InitDb extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('SET storage_engine=INNODB;');
        $this->execute('CREATE TABLE users (id BIGINT(20) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT, login VARCHAR(255), email VARCHAR(255), created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, updated TIMESTAMP NOT NULL, status VARCHAR(32) DEFAULT \'disabled\' NOT NULL);');
        $this->execute('CREATE UNIQUE INDEX users_login_uindex ON users (login);');
        $this->execute('CREATE UNIQUE INDEX users_email_uindex ON users (email);');
        $this->execute('CREATE TABLE users_actions (userId BIGINT(20) unsigned NOT NULL, code VARCHAR(32) NOT NULL, action VARCHAR(32) NOT NULL, params LONGTEXT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, expired TIMESTAMP NOT NULL, CONSTRAINT users_actions_userId_code_pk PRIMARY KEY (userId, code), CONSTRAINT users_actions_users_id_fk FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE UNIQUE INDEX users_actions_userId_action_uindex ON users_actions (userId, action);');
        $this->execute('CREATE TABLE auth ( userId BIGINT(20) unsigned NOT NULL, provider VARCHAR(64) NOT NULL, foreignKey VARCHAR(255) NOT NULL, token VARCHAR(64) NOT NULL, tokenSecret VARCHAR(64) NOT NULL, tokenType VARCHAR(32) NOT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, updated TIMESTAMP NOT NULL, expired TIMESTAMP NOT NULL, CONSTRAINT auth_userId_provider_pk PRIMARY KEY (userId, provider), CONSTRAINT auth_users_id_fk FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE TABLE acl_roles ( id INT(10) unsigned NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT acl_roles_id_name_pk PRIMARY KEY (id, name));');
        $this->execute('CREATE UNIQUE INDEX acl_roles_name_uindex ON acl_roles (name);');
        $this->execute('CREATE TABLE acl_privileges ( roleId INT(10) unsigned NOT NULL, module VARCHAR(32) NOT NULL, privilege VARCHAR(32) NOT NULL, CONSTRAINT acl_privileges_acl_roles_id_fk FOREIGN KEY (roleId) REFERENCES acl_roles (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE UNIQUE INDEX acl_privileges_roleId_module_privilege_uindex ON acl_privileges (roleId, module, privilege);');
        $this->execute('CREATE TABLE acl_users_roles ( userId BIGINT(20) unsigned NOT NULL, roleId INT(10) unsigned NOT NULL, CONSTRAINT acl_users_roles_userId_roleId_pk PRIMARY KEY (userId, roleId), CONSTRAINT acl_users_roles_users_id_fk FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT acl_users_roles_acl_roles_id_fk FOREIGN KEY (roleId) REFERENCES acl_roles (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE TABLE pages ( id INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT, userId BIGINT(20) unsigned, title LONGTEXT NOT NULL, alias VARCHAR(255) NOT NULL, content LONGTEXT, keywords LONGTEXT, description LONGTEXT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, updated TIMESTAMP NOT NULL, CONSTRAINT pages_users_id_fk FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE UNIQUE INDEX pages_alias_uindex ON pages (alias);');
        $this->execute('CREATE TABLE com_settings ( id INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT, alias VARCHAR(255) NOT NULL, options LONGTEXT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, updated TIMESTAMP NOT NULL, countPerPage SMALLINT(6) DEFAULT \'10\' NOT NULL, relatedTable VARCHAR(64));');
        $this->execute('CREATE UNIQUE INDEX com_settings_alias_uindex ON com_settings (alias);');
        $this->execute('CREATE TABLE com_content ( id BIGINT(20) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT, settingsId INT(10) unsigned NOT NULL, foreignKey INT(10) unsigned NOT NULL, userId BIGINT(20) unsigned NOT NULL, parentId BIGINT(20) unsigned, content LONGTEXT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, updated TIMESTAMP NOT NULL,  status VARCHAR(255) DEFAULT \'active\' NOT NULL, CONSTRAINT com_content_com_settings_id_fk FOREIGN KEY (settingsId) REFERENCES com_settings (id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT com_content_users_id_fk FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT com_content_com_content_id_fk FOREIGN KEY (parentId) REFERENCES com_content (id) ON DELETE CASCADE ON UPDATE CASCADE);');
        $this->execute('CREATE INDEX comments_target ON com_content (settingsId, foreignKey);');

        $this->execute('LOCK TABLES `users` WRITE;');
        $this->execute('INSERT INTO `users` (`id`, `login`, `email`, `created`, `updated`, `status`) VALUES (1,\'system\',NULL,\'2012-11-09 07:37:58\',NULL,\'disabled\'), (2,\'admin\',NULL,\'2012-11-09 07:38:41\',NULL,\'active\');');
        $this->execute('UNLOCK TABLES;');
        $this->execute('LOCK TABLES `acl_roles` WRITE;');
        $this->execute('INSERT INTO `acl_roles` (`id`, `name`, `created`) VALUES (1,\'system\',\'2012-11-09 07:37:31\'), (2,\'admin\',\'2012-11-09 07:37:33\'), (3,\'member\',\'2012-11-09 07:37:37\'), (4,\'guest\',\'2012-11-09 07:37:44\');');
        $this->execute('UNLOCK TABLES;');
        $this->execute('LOCK TABLES `acl_privileges` WRITE;');
        $this->execute('INSERT INTO `acl_privileges` (`roleId`, `module`, `privilege`) VALUES (2,\'acl\',\'Management\'), (2,\'acl\',\'View\'), (2,\'cache\',\'Management\'), (2,\'dashboard\',\'Dashboard\'), (2,\'pages\',\'Management\'), (2,\'system\',\'Info\'), (2,\'users\',\'EditEmail\'), (2,\'users\',\'EditPassword\'), (2,\'users\',\'Management\'), (2,\'users\',\'ViewProfile\'), (3,\'users\',\'EditEmail\'), (3,\'users\',\'EditPassword\'), (3,\'users\',\'ViewProfile\'), (4,\'users\',\'ViewProfile\');');
        $this->execute('UNLOCK TABLES;');
        $this->execute('LOCK TABLES `acl_users_roles` WRITE;');
        $this->execute('INSERT INTO `acl_users_roles` (`userId`, `roleId`) VALUES (1,1), (2,2);');
        $this->execute('UNLOCK TABLES;');
        $this->execute('LOCK TABLES `auth` WRITE;');
        $this->execute('INSERT INTO `auth` (`userId`, `provider`, `foreignKey`, `token`, `tokenSecret`, `tokenType`, `created`) VALUES (2,\'equals\',\'admin\',\'$2y$10$4a454775178c3f89d510fud2T.xtw01Ir.Jo.91Dr3nL2sz3OyVpK\',\'\',\'access\',\'2012-11-09 07:40:46\');');
        $this->execute('UNLOCK TABLES;');
        $this->execute('LOCK TABLES `pages` WRITE;');
        $this->execute('INSERT INTO `pages` (`id`, `title`, `alias`, `content`, `keywords`, `description`, `created`, `updated`, `userId`) VALUES (1,\'About Bluz Framework\',\'about\',\'<p>Bluz Lightweight PHP Framework!</p>\',\'about bluz framework\',\'About Bluz\',\'2012-04-09 18:34:03\',\'2014-05-12 11:01:03\', 1);');
        $this->execute('UNLOCK TABLES;');
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('com_content');
        $this->dropTable('com_settings');
        $this->dropTable('pages');
        $this->dropTable('acl_users_roles');
        $this->dropTable('acl_privileges');
        $this->dropTable('acl_roles');
        $this->dropTable('auth');
        $this->dropTable('users_actions');
        $this->dropTable('users');
    }
}
