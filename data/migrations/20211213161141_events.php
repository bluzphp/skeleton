<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Events extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('events');
        $table
            ->addColumn('event', 'string', ['length' => 255, 'null' => false])
            ->addColumn('target', 'string', ['length' => 255, 'null' => false])
            ->addColumn('data', 'json', ['null' => true])
            ->addTimestamps('created', 'updated')
            ->create();
    }
}
