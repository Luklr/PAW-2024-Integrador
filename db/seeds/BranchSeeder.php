<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BranchSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Assembl Gral Rodríguez',
                'street' => 'Av. Irigoyen',
                'number' => 1050,
                'locality' => 'General Rodríguez'
            ],
            [
                'name' => 'Assembl Luján',
                'street' => 'Av. España',
                'number' => 760,
                'locality' => 'Luján'
            ],
            [
                'name' => 'Assembl Moreno',
                'street' => 'San Martín',
                'number' => 430,
                'locality' => 'Moreno'
            ],
            [
                'name' => 'Assembl Mercedes',
                'street' => 'Belgrano',
                'number' => 390,
                'locality' => 'Mercedes'
            ],
        ];

        $table = $this->table('branch');
        $table->insert($data)->saveData();
    }
}