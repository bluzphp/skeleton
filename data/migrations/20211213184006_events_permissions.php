<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EventsPermissions extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $data = [
            [
                'roleId' => 2,
                'module' => 'events',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'api',
                'privilege' => 'Events/Read'
            ],
            [
                'roleId' => 2,
                'module' => 'api',
                'privilege' => 'Events/Edit'
            ],
        ];

        $privileges = $this->table('acl_privileges');
        $privileges->insert($data)
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM acl_privileges WHERE module = "events"');
    }
}
