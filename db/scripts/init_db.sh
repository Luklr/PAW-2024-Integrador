#!/bin/bash

# Create the database
./vendor/robmorgan/phinx/bin/phinx migrate
./vendor/robmorgan/phinx/bin/phinx seed:run -s VideoCardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MotherboardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MemorySeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s InternalHardDriveSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CpuFanSeeder