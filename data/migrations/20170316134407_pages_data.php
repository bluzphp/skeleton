<?php

use Phinx\Migration\AbstractMigration;

class PagesData extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $data = [
            [
                'title' => 'About',
                'alias' => 'about',
                'content' => '<p>Describe your site here!</p>',
                'keywords' => 'about, help',
                'description' => 'About service',
                'created' => date('Y-m-d H:i:s'),
                'userId' => 1
            ],
            [
                'title' => 'Terms and conditions',
                'alias' => 'terms-and-conditions',
                'content' => '<p>Place your terms and conditions here!</p>',
                'keywords' => 'terms, conditions',
                'description' => 'Terms and conditions',
                'created' => date('Y-m-d H:i:s'),
                'userId' => 1
            ],
            [
                'title' => 'Legal notices',
                'alias' => 'legal-notices',
                'content' => '<p>Place legal notices here!</p>',
                'keywords' => 'legal notices',
                'description' => 'Legal notices',
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
