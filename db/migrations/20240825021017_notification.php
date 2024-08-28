<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Notification extends AbstractMigration
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
    public function change()
    {
        $table = $this->table('notification');
        $table->addColumn('user_id', 'integer')
              ->addColumn('seen', 'boolean', ['default' => false])
              ->addColumn('notification_type_id', 'integer')
              ->addColumn('order_id', 'integer', ['null' => true])
              ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('notification_type_id', 'notification_type', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('user_id', 'user', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('order_id', 'order', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
