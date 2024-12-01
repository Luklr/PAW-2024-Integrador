<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CasePcSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Ruta imagenes
        $imagesPath = '/images/components/';

        // Generador de imagenes
        $randomImages = function () use ($imagesPath) {
            $strings = ["casecpu-1.png", "casecpu-2.png", "casecpu-3.png"];
            return $imagesPath . $strings[array_rand($strings)];
        };

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

        // Mapeo de tipos de gabinete a tipos de fuente de poder
        $caseToPowerSupplyMap = [
            "ATX Desktop" => "ATX",
            "ATX Full Tower" => "ATX",
            "ATX Mid Tower" => "ATX",
            "ATX Mini Tower" => "ATX",
            "ATX Test Bench" => "ATX",
            "HTPC" => "ATX",
            "MicroATX Desktop" => "ATX",
            "MicroATX Mid Tower" => "ATX",
            "MicroATX Mini Tower" => "ATX",
            "MicroATX Slim" => "Flex ATX",
            "Mini ITX Desktop" => "Mini ITX",
            "Mini ITX Test Bench" => "Mini ITX",
            "Mini ITX Tower" => "Mini ITX",
        ];

        $id = 31656; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'casePc',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
                "path_img" => $randomImages(),
            ];

            // Determinar el valor para external_volume
            $externalVolume = (float)$row[6];
            if ($externalVolume === 0) {
                $externalVolume = $randomVolume(); // Valor aleatorio entre 20, 40 y 60
            }

            // Determinar el tipo de fuente de poder basado en el tipo de gabinete
            $caseType = $row[2];
            $powerSupplyType = $caseToPowerSupplyMap[$caseType] ?? null; // Validar mapeo

            if ($powerSupplyType === null) {
                throw new RuntimeException("No se encontró un mapeo para el tipo de gabinete: $caseType");
            }

            // Datos para la tabla 'casePc'
            $casePcData[] = [
                'component_id' => $id,
                'side_panel' => $row[5],
                'external_volume' => $externalVolume,
                'type' => $powerSupplyType, // Se incluye el tipo de fuente de poder
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'casePc'
        $this->table('casePc')->insert($casePcData)->saveData();
    }
}