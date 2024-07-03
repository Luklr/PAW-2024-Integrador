<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateComponent extends AbstractMigration
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
        $table = $this->table('component');
        $table
            ->addColumn('description', 'string', ['limit' => 512])
            ->addColumn('type', 'string', ['limit' => 20])
            ->addColumn('price', 'float')
            ->addColumn('stock', 'integer')
            ->addColumn("path_img", "string", ["limit" => 255, "null" => true])
            ->create();
    }
}
