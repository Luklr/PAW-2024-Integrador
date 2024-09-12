<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class MemorySeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta imagenes
        $imagesPath = '/images/components/';

        // Generador de imagenes
        $randomImages = function () use ($imagesPath) {
            $strings = ["memory-1.png", "memory-2.png", "memory-3.png"];
            return $imagesPath . $strings[array_rand($strings)];
        };

        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/memory.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $memoryData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 10564; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'memory',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
                "path_img" => $randomImages(),
            ];
            $parts = explode(',', $row[3]);
            $modules = (int)$parts[0];
            $size = (int)$parts[1];
            // Datos para la tabla 'memory'
            $memoryData[] = [
                'component_id' => $id,
                'speed' => (int)$row[2],
                'modules' => $modules,
                'size' => $size
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'memory'
        $this->table('memory')->insert($memoryData)->saveData();
    }
}
