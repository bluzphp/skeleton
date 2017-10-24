<?php

use Phinx\Migration\AbstractMigration;

class UsersData extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $data = [
            [
                'id' => 1,
                'login' => 'system',
                'email' => 'system@localhost',
                'created' => date('Y-m-d H:i:s'),
                'status' => 'disabled'
            ],
            [
                'id' => 2,
                'login' => 'admin',
                'email' => 'admin@localhost',
                'created' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ],
            [
                'id' => 3,
                'login' => 'member',
                'email' => 'member@localhost',
                'created' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ],
        ];

        $users = $this->table('users');
        $users->insert($data)
            ->save();

        $data = [
            [
                'userId' => 2,
                'provider' => 'equals',
                'foreignKey' => 'admin',
                'token' => '$2y$10$4a454775178c3f89d510fud2T.xtw01Ir.Jo.91Dr3nL2sz3OyVpK', // admin
                'tokenType' => 'access',
                'created' => date('Y-m-d H:i:s')
            ],
            [
                'userId' => 3,
                'provider' => 'equals',
                'foreignKey' => 'member',
                'token' => '$2y$10$poVyazyQKXlfsGuUwxj/su.w0nnNJKzgyQyAnN3zjx9In3BaBeusq', // member
                'tokenType' => 'access',
                'created' => date('Y-m-d H:i:s')
            ],
        ];

        $auth = $this->table('auth');
        $auth->insert($data)
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM auth');
        $this->execute('DELETE FROM users');
    }
}
