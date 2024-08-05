<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class AddressSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'street' => 'Av. España',
                'number' => 600,
                'postal_code' => 'B1748',
                'floor' => 2,
                'apartment' => 3,
                'province' => 'Buenos Aires',
                'city' => 'General Rodríguez'
            ],
            [
                'user_id' => 2,
                'street' => 'Rawson',
                'number' => 760,
                'postal_code' => 'B1748',
                'floor' => null,
                'apartment' => null,
                'province' => 'Buenos Aires',
                'city' => 'General Rodríguez'
            ],
            [
                'user_id' => 1,
                'street' => 'Irigoyen',
                'number' => 240,
                'postal_code' => 'B6700',
                'floor' => null,
                'apartment' => null,
                'province' => 'Buenos Aires',
                'city' => 'Luján'
            ],
        ];

        $table = $this->table('address');
        $table->insert($data)->saveData();
    }
}