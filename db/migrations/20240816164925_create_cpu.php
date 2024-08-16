<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCpu extends AbstractMigration
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
        $table = $this->table('cpu');
        $table
            ->addColumn('component_id', 'integer')
            ->addIndex(["component_id"],['unique' => true])
            ->addForeignKey('component_id', 'component', 'id', ['update' => 'NO_ACTION'])
            ->addColumn('socket', 'string', ['limit' => 30])
            ->addColumn('core_count', 'integer')
            ->addColumn('core_clock', 'float')
            ->addColumn('boost_clock', 'float')
            ->addColumn('graphics', 'string', ['null' => true])
            ->create();
    }
}
