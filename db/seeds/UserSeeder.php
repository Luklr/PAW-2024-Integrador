<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'username' => 'User',
                'email' => 'user@example.com',
                'password' => hash('sha256', "12345678"),
                'role' => 'user',
                'name' => 'User',
                'lastname' => '1',
                // 'address_id' => 1
            ],
            [
                'username' => 'Admin',
                'email' => 'admin@example.com',
                'password' => hash('sha256', "12345678"),
                'role' => 'admin',
                'name' => 'Admin',
                'lastname' => '1',
                // 'address_id' => 1
            ],
        ];

        $table = $this->table('user');
        $table->insert($data)->saveData();
    }
}
