<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CasePcSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/case_pc.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $casePcData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        // Función para generar un valor aleatorio entre 20, 40 y 60
        $randomVolume = function () {
            $values = [20, 40, 60];
            return $values[array_rand($values)];
        };

        $id = 31656; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'casePc',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
            ];

            // Determinar el valor para external_volume
            $externalVolume = (float)$row[6];
            if ($externalVolume === 0) {
                $externalVolume = $randomVolume(); // Valor aleatorio entre 20, 40 y 60
            }

            // Datos para la tabla 'casePc'
            $casePcData[] = [
                'component_id' => $id,
                'type' => $row[2],
                'side_panel' => $row[5],
                'external_volume' => $externalVolume,
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'casePc'
        $this->table('casePc')->insert($casePcData)->saveData();
    }
}
