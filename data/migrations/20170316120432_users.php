<?php

use Phinx\Migration\AbstractMigration;

class Users extends AbstractMigration
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
        $users = $this->table('users');
        $users
            ->addColumn('login', 'string', ['length' => 255])
            ->addColumn('email', 'string', ['length' => 255, 'null' => true])
            ->addTimestamps('created', 'updated')
            ->addColumn('status', 'string', ['length' => 32, 'default' => 'disabled'])
            ->addIndex(['login'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create();

        $actions = $this->table('users_actions', ['id' => false, 'primary_key' => ['userId', 'code']]);
        $actions
            ->addColumn('userId', 'integer')
            ->addColumn('code', 'string', ['length' => 32])
            ->addColumn('action', 'string', ['length' => 32])
            ->addColumn('params', 'text')
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
            ->addColumn('expired', 'timestamp')
            ->addForeignKey('userId', $users, 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();

        $auth = $this->table('auth', ['id' => false, 'primary_key' => ['userId', 'provider']]);
        $auth
            ->addColumn('userId', 'integer')
            ->addColumn('provider', 'string', ['length' => 64])
            ->addColumn('foreignKey', 'string', ['length' => 255])
            ->addColumn('token', 'string', ['length' => 255])
            ->addColumn('tokenSecret', 'string', ['length' => 255])
            ->addColumn('tokenType', 'string', ['length' => 64])
            ->addTimestamps('created', 'updated')
            ->addColumn('expired', 'timestamp')
            ->addForeignKey('userId', $users, 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();
    }
}
