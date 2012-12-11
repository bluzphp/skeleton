CREATE TABLE acl_privileges
(
    roleId int unsigned NOT NULL,
    module varchar(255) NOT NULL,
    privilege varchar(255) NOT NULL
);
CREATE TABLE acl_roles
(
    id int unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (id, name)
);
CREATE TABLE acl_usersToRoles
(
    userId bigint unsigned NOT NULL,
    roleId int unsigned NOT NULL,
    PRIMARY KEY (userId, roleId)
);
CREATE TABLE auth
(
    userId bigint unsigned NOT NULL,
    provider varchar(255) NOT NULL,
    foreignKey varchar(255) NOT NULL,
    token varchar(64) NOT NULL,
    tokenSecret varchar(64) NOT NULL,
    tokenType char(8) NOT NULL,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY (provider, foreignKey)
);
CREATE TABLE com_content
(
    id bigint unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    settingsId int unsigned NOT NULL,
    foreignKey int unsigned NOT NULL,
    userId bigint unsigned NOT NULL,
    parentId bigint unsigned,
    content longtext,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    status char(7) DEFAULT 'active' NOT NULL
);
CREATE TABLE com_settings
(
    id int unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    alias varchar(255) NOT NULL,
    options longtext,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    countPerPage smallint DEFAULT 10 NOT NULL,
    relatedTable varchar(64)
);
CREATE TABLE pages
(
    id int unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title longtext NOT NULL,
    alias varchar(255) NOT NULL,
    content longtext,
    keywords longtext,
    description longtext,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    userId bigint unsigned
);
CREATE TABLE users
(
    id bigint unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    login varchar(255),
    email text,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    status char(8) DEFAULT 'disabled' NOT NULL
);
CREATE TABLE users_actions
(
    userId bigint unsigned NOT NULL,
    code varchar(255) NOT NULL,
    action char(11) NOT NULL,
    created timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    expired timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY (userId, code)
);
ALTER TABLE acl_privileges ADD FOREIGN KEY ( roleId ) REFERENCES acl_roles ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE UNIQUE INDEX role_privilege ON acl_privileges ( roleId, module, privilege );
CREATE UNIQUE INDEX unique_name ON acl_roles ( name );
ALTER TABLE acl_usersToRoles ADD FOREIGN KEY ( roleId ) REFERENCES acl_roles ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE acl_usersToRoles ADD FOREIGN KEY ( userId ) REFERENCES users ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE auth ADD FOREIGN KEY ( userId ) REFERENCES users ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE UNIQUE INDEX UNIQUE_user_provider ON auth ( userId, provider );
ALTER TABLE com_content ADD FOREIGN KEY ( id ) REFERENCES com_content ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE com_content ADD FOREIGN KEY ( settingsId ) REFERENCES com_settings ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE com_content ADD FOREIGN KEY ( userId ) REFERENCES users ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE INDEX comments_target ON com_content ( settingsId, foreignKey );
CREATE INDEX FK_comments_to_users ON com_content ( userId );
CREATE UNIQUE INDEX com_aliases_unique ON com_settings ( alias );
ALTER TABLE pages ADD FOREIGN KEY ( userId ) REFERENCES users ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE UNIQUE INDEX `unique` ON pages ( alias );
CREATE INDEX FK_pages_to_users ON pages ( userId );
CREATE UNIQUE INDEX UNIQUE_login ON users ( login );
ALTER TABLE users_actions ADD FOREIGN KEY ( userId ) REFERENCES users ( id ) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE UNIQUE INDEX UNIQUE_userId_action ON users_actions ( userId, action );
