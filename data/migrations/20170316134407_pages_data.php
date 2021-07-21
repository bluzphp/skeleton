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
                'title' => 'Terms of Service',
                'alias' => 'terms-of-service',
                'content' => '<p>Place your Terms of Service here!</p>',
                'keywords' => 'terms, conditions',
                'description' => 'Terms of Service',
                'created' => date('Y-m-d H:i:s'),
                'userId' => 1
            ],
            [
                'title' => 'Privacy Policy',
                'alias' => 'privacy-policy',
                'content' => '<p>Place Privacy Policy here!</p>',
                'keywords' => 'privacy policy, legal notices',
                'description' => 'Privacy Policy',
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
