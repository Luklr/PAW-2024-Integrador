<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Order extends AbstractMigration
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
        $table->addColumn('id_user', 'integer')
            ->addColumn('type', 'string', ['limit' => 20])
            ->addColumn('date', 'date')
            ->addColumn('total', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('state', 'string', ['limit' => 20])
            ->addForeignKey('id_user', 'user', 'id', ['update' => 'NO_ACTION'])
            ->create();
    }
}
