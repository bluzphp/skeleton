<?php

use Phinx\Migration\AbstractMigration;

class AclData extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'system'
            ],
            [
                'id' => 2,
                'name' => 'admin'
            ],
            [
                'id' => 3,
                'name' => 'member'
            ],
            [
                'id' => 4,
                'name' => 'guest'
            ],
        ];
        $this->table('acl_roles')->insert($roles)->save();

        $privileges = [
            [
                'roleId' => 2,
                'module' => 'acl',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'acl',
                'privilege' => 'View'
            ],
            [
                'roleId' => 2,
                'module' => 'api',
                'privilege' => 'Users/Id'
            ],
            [
                'roleId' => 2,
                'module' => 'api',
                'privilege' => 'Users/Profile'
            ],
            [
                'roleId' => 2,
                'module' => 'cache',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'dashboard',
                'privilege' => 'Dashboard'
            ],
            [
                'roleId' => 2,
                'module' => 'pages',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'system',
                'privilege' => 'Info'
            ],
            [
                'roleId' => 2,
                'module' => 'users',
                'privilege' => 'EditEmail'
            ],
            [
                'roleId' => 2,
                'module' => 'users',
                'privilege' => 'EditPassword'
            ],
            [
                'roleId' => 2,
                'module' => 'users',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'users',
                'privilege' => 'ViewProfile'
            ],
            [
                'roleId' => 3,
                'module' => 'api',
                'privilege' => 'Users/Profile'
            ],
            [
                'roleId' => 3,
                'module' => 'users',
                'privilege' => 'EditEmail'
            ],
            [
                'roleId' => 3,
                'module' => 'users',
                'privilege' => 'EditPassword'
            ],
            [
                'roleId' => 3,
                'module' => 'users',
                'privilege' => 'ViewProfile'
            ],
        ];
        $this->table('acl_privileges')->insert($privileges)->save();

        $usersRoles = [
            [
                'userId' => 1,
                'roleId' => 1
            ],
            [
                'userId' => 2,
                'roleId' => 2
            ],
            [
                'userId' => 3,
                'roleId' => 3
            ],
        ];
        $this->table('acl_users_roles')->insert($usersRoles)->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM acl_users_roles');
        $this->execute('DELETE FROM acl_privileges');
        $this->execute('DELETE FROM acl_roles');
    }
}
