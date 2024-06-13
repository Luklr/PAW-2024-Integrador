#!/bin/bash

# Create the database
./vendor/robmorgan/phinx/bin/phinx rollback -t 0
./vendor/robmorgan/phinx/bin/phinx migrate
./vendor/robmorgan/phinx/bin/phinx seed:run -s UserSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s VideoCardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MotherboardSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s MemorySeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s InternalHardDriveSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CpuFanSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s CasePcSeeder
./vendor/robmorgan/phinx/bin/phinx seed:run -s PowerSuplySeeder