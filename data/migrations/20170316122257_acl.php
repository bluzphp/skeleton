<?php

use Phinx\Migration\AbstractMigration;

class Acl extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $roles = $this->table('acl_roles', ['primary_key' => ['id', 'code']]);
        $roles
            ->addColumn('name', 'string', ['length'=>255])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
            ->addIndex(['name'], ['unique' => true])
            ->create();

        $privileges = $this->table(
            'acl_privileges',
            [
                'id' => false,
                'primary_key' => ['roleId', 'module', 'privilege']
            ]
        );
        $privileges
            ->addColumn('roleId', 'integer')
            ->addColumn('module', 'string', ['length'=>32])
            ->addColumn('privilege', 'string', ['length'=>32])
            ->addForeignKey('roleId', $roles, 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();

        $usersRoles = $this->table('acl_users_roles', ['id' => false, 'primary_key' => ['userId', 'roleId']]);
        $usersRoles
            ->addColumn('userId', 'integer')
            ->addColumn('roleId', 'integer')
            ->addForeignKey('userId', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->addForeignKey('roleId', $roles, 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();


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
