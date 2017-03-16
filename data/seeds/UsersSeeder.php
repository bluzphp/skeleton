<?php

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
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
        $data = [
            [
                'id' => 1,
                'login' => 'system',
                'created' => date('Y-m-d H:i:s'),
                'status' => 'disabled'
            ],
            [
                'id' => 2,
                'login' => 'admin',
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
                'token' => '$2y$10$4a454775178c3f89d510fud2T.xtw01Ir.Jo.91Dr3nL2sz3OyVpK',
                'tokenType' => 'access',
                'created' => date('Y-m-d H:i:s')
            ]
        ];

        $auth = $this->table('auth');
        $auth->insert($data)
            ->save();
    }
}
