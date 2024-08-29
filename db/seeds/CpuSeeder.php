<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CpuSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta imagenes
        $imagesPath = '/images/components/';

        // Generador de imagenes
        $randomImages = function () use ($imagesPath) {
            $strings = ["cpu-1.png", "cpu-2.png", "cpu-3.webp"];
            return $imagesPath . $strings[array_rand($strings)];
        };

        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/cpu.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $cpuData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 40698; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'cpu',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
                "path_img" => $randomImages(),
            ];

            // Determinar el tamaño (size)
            $graphics = $row[6]!=="" ? $row[6] : null;

            // Datos para la tabla 'cpuFan'
            $cpuData[] = [
                'component_id' => $id,
                "core_count" => (int)$row[2],
                "core_clock" => (float)$row[3],
                "boost_clock" => (float)$row[4],
                'graphics' => $graphics,
                "socket" => $row[8]
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'cpuFan'
        $this->table('cpu')->insert($cpuData)->saveData();
    }
}
