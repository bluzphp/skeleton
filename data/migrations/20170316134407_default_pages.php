<?php

use Phinx\Migration\AbstractMigration;

class DefaultPages extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
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

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM pages');
    }
}
