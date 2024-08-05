<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrderComponents extends AbstractMigration
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
        $table = $this->table('order_component');
        $table->addColumn('order_id', 'integer')
              ->addColumn('component_id', 'integer')
              ->addColumn('quantity', 'integer')
              ->addForeignKey('order_id', 'order', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('component_id', 'component', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
