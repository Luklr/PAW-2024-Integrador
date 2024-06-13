<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class InternalHardDriveSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/internal_hard_drive.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $internalHardDriveData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 23163; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'internalHardDrive',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
            ];

            // Datos para la tabla 'internalHardDrive'
            $internalHardDriveData[] = [
                'component_id' => $id,
                'type' => $row[4],
                'capacity' => (int)$row[2],
                'form_factor' => $row[6],
                'interface' => $row[7],
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'internalHardDrive'
        $this->table('internalHardDrive')->insert($internalHardDriveData)->saveData();
    }
}
