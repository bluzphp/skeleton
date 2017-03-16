<?php

use Phinx\Seed\AbstractSeed;

class AclSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
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
                'privilege' => 'Management'
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
        ];
        $this->table('acl_users_roles')->insert($usersRoles)->save();
    }
}
