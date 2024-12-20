#!/bin/bash

# Crear la base de datos
echo "Ejecutando migraciones de Phinx..."
./vendor/robmorgan/phinx/bin/phinx rollback -t 0
./vendor/robmorgan/phinx/bin/phinx migrate

echo "Ejecutando seeders de Phinx..."
./vendor/robmorgan/phinx/bin/phinx seed:run -s UserSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s VideoCardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MotherboardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MemorySeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s InternalHardDriveSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CpuFanSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CasePcSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s PowerSupplySeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s AddressSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s BranchSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CpuSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s NotificationTypeSeeder