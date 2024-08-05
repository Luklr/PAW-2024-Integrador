<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrder extends AbstractMigration
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
        $table = $this->table('order');
        $table->addColumn('order_date', 'datetime')
              ->addColumn('delivery_date', 'datetime')
              ->addColumn('order_price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('delivery_price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('user_id', 'integer')
              ->addColumn('branch_id', 'integer', ['null' => true])
              ->addColumn('status', 'string', ['limit' => 100])
              ->addColumn("address_id", "integer", ["null" => true])
              ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('branch_id', 'branch', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->addForeignKey('address_id', 'address', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
