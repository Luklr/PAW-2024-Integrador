<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMonitor extends AbstractMigration
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
        $table = $this->table('monitor');
        $table
            ->addColumn('component_id', 'integer')
            ->addIndex(["component_id"],['unique' => true])
            ->addForeignKey('component_id', 'component', 'id', ['update' => 'NO_ACTION'])
            ->addColumn('screen_size', 'float')
            ->addColumn('resolution', 'string', ["limit" => 20])
            ->addColumn('refresh_rate', 'integer')
            ->addColumn('response_time', 'float')
            ->addColumn('panel_type', 'string', ["limit" => 20])
            ->addColumn('aspect_ratio', 'string', ["limit" => 20])
            ->create();
    }
}
