<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class MotherboardSeeder extends AbstractSeed
{
    public function run(): void
    {
        $csvFile = __DIR__ . '/csv/motherboard.csv';
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $motherboardsData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 6079; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'motherboard',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
            ];

            // Datos para la tabla 'motherboard'
            $motherboardsData[] = [
                'component_id' => $id,
                'socket' => $row[2],
                'memory_slots' => (int)$row[5],
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'motherboard'
        $this->table('motherboard')->insert($motherboardsData)->saveData();
    }
}
