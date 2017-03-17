<?php

use Phinx\Seed\AbstractSeed;

class PagesSeeder extends AbstractSeed
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
                'title' => 'About Bluz Framework',
                'alias' => 'about',
                'content' => '<p>Bluz Lightweight PHP Framework!</p>',
                'keywords' => 'php, php framework, bluz framework',
                'description' => 'About Bluz',
                'created' => date('Y-m-d H:i:s'),
                'userId' => 1
            ]
        ];

        $pages = $this->table('pages');
        $pages->insert($data)
            ->save();
    }
}
