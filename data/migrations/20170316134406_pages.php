<?php

use Phinx\Migration\AbstractMigration;

class Pages extends AbstractMigration
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
        $pages = $this->table('pages');
        $pages
            ->addColumn('userId', 'integer')
            ->addColumn('title', 'text')
            ->addColumn('alias', 'string', ['length' => 255])
            ->addColumn('content', 'text')
            ->addColumn('keywords', 'text')
            ->addColumn('description', 'text')
            ->addTimestamps('created', 'updated')
            ->addForeignKey('userId', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->addIndex(['alias'], ['unique' => true])
            ->create();
    }
}
