<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class VideoCardSeeder extends AbstractSeed
{
    public function run(): void
    {
        $csvFile = __DIR__ . '/csv/video_card.csv';
        $rows = array_map('str_getcsv', file($csvFile));
        
        // La primera fila se asume que son los headers
        $headers = array_shift($rows);

        // Array para guardar los datos que serán insertados en las tablas
        $componentsData = [];
        $videoCardsData = [];

        $randomStock = function () {
            return rand(10, 50);
        };

        $id = 1; // ID inicial

        foreach ($rows as $row) {
            // Datos para la tabla 'component'
            $componentsData[] = [
                'description' => $row[0],
                'type' => 'videoCard',
                'price' => (float)$row[1],
                'stock' => $randomStock(),
            ];

            // Datos para la tabla 'videoCard'
            $videoCardsData[] = [
                'component_id' => $id,
                'chipset' => $row[2],
                'memory' => (int)$row[3],
                'core_clock' => (int)$row[4],
                'boost_clock' => (int)$row[5],
            ];

            $id++;
        }

        // Insertar datos en la tabla 'component'
        $this->table('component')->insert($componentsData)->saveData();

        // Insertar datos en la tabla 'videoCard'
        $this->table('videoCard')->insert($videoCardsData)->saveData();
    }
}
