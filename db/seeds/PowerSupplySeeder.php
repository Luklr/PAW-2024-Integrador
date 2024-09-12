<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PowerSupplySeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta imagenes
        $imagesPath = '/images/components/';

        // Generador de imagenes
        $randomImages = function () use ($imagesPath) {
            $strings = ["powersupply-1.png", "powersupply-2.png", "powersupply-3.png"];
            return $imagesPath . $strings[array_rand($strings)];
        };

        // Ruta del archivo CSV
        $csvFile = __DIR__ . '/csv/power_supply.csv';

        // Leer el contenido del archivo CSV
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $powerSupplyData = [];

        // Generador de datos aleatorios para el stock
        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 37609; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'powerSupply',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
                "path_img" => $randomImages(),
            ];

            // Determinar si es modular (true/false)
            $isModular = strtolower($row[5]) === 'false' ? false : true;

            // Datos para la tabla 'powerSupply'
            $powerSupplyData[] = [
                'component_id' => $id,
                'type' => $row[2],
                'efficiency' => $row[3],
                'wattage' => (int)$row[4],
                'modular' => $isModular,
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'powerSupply'
        $this->table('powerSupply')->insert($powerSupplyData)->saveData();
    }
}
