<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVideoCard extends AbstractMigration
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
        $table = $this->table('videoCard');
        $table
            ->addColumn('component_id', 'integer')
            ->addForeignKey('component_id', 'component', 'id', ['update' => 'NO_ACTION'])
            ->addColumn('chipset', 'string', ['limit' => 60])
            ->addColumn('memory', 'integer')
            ->addColumn('core_clock', 'integer')
            ->addColumn('boost_clock', 'integer')
            ->create();
    }
}
