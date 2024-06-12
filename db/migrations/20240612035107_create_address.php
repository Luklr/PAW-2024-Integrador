<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAddress extends AbstractMigration
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
        $tableUsuario = $this->table("address");
        $tableUsuario
            ->addColumn("street", "string", ["limit" => 40])
            ->addColumn("number", "integer")
            ->addColumn("postal_code", "string", ["limit" => 20])
            ->addColumn("floor", "integer", ["null" => true])
            ->addColumn("apartment", "integer", ["null" => true])
            ->addColumn("province", "string", ["limit" => 40])
            ->addColumn("city", "string", ["limit" => 40])
            ->create();
    }
}
