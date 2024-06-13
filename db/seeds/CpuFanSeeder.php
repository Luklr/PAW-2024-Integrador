<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CpuFanSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/cpu_fan.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $cpuFanData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 29225; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'cpuFan',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
            ];

            // Determinar el tamaño (size)
            $size = isset($row[5]) ? (int)$row[5] : null;

            // Si 'size' está ausente o nulo, establecer en 240 o 360
            if ($size === null || $size === 0) {
                $size = rand(0, 1) ? 240 : 360; // Aleatoriamente entre 240 y 360
            }

            // Datos para la tabla 'cpuFan'
            $cpuFanData[] = [
                'component_id' => $id,
                'rpm' => (int)$row[2],
                'noise_level' => (int)$row[3],
                'size' => $size,
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'cpuFan'
        $this->table('cpuFan')->insert($cpuFanData)->saveData();
    }
}
