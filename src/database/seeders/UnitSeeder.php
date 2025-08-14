<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            // Length

            [
                'name' => 'Meter',
                'symbol' => 'm',
                'description' => 'SI unit of length',
                'type' => 'length',
                'si_unit' => 'm',
                'conversion_factor' => 1,
                'dimension' => 'L',
                'system' => 'metric',
                'is_base_unit' => true,
                'category' => 'distance',
                'unit_system' => 'SI',
                'unit_group' => 'length',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kilometer',
                'symbol' => 'km',
                'description' => 'One thousand meters',
                'type' => 'length',
                'si_unit' => 'm',
                'conversion_factor' => 1000,
                'dimension' => 'L',
                'system' => 'metric',
                'is_base_unit' => false,
                'category' => 'distance',
                'unit_system' => 'SI',
                'unit_group' => 'length',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Centimeter',
                'symbol' => 'cm',
                'description' => 'One hundredth of a meter',
                'type' => 'length',
                'si_unit' => 'm',
                'conversion_factor' => 0.01,
                'dimension' => 'L',
                'system' => 'metric',
                'is_base_unit' => false,
                'category' => 'distance',
                'unit_system' => 'CGS',
                'unit_group' => 'length',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Inch',
                'symbol' => 'in',
                'description' => 'Imperial unit of length',
                'type' => 'length',
                'si_unit' => 'm',
                'conversion_factor' => 0.0254,
                'dimension' => 'L',
                'system' => 'imperial',
                'is_base_unit' => false,
                'category' => 'distance',
                'unit_system' => 'Imperial',
                'unit_group' => 'length',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Foot',
                'symbol' => 'ft',
                'description' => 'Imperial unit of length',
                'type' => 'length',
                'si_unit' => 'm',
                'conversion_factor' => 0.3048,
                'dimension' => 'L',
                'system' => 'imperial',
                'is_base_unit' => false,
                'category' => 'distance',
                'unit_system' => 'Imperial',
                'unit_group' => 'length',
                'is_active' => true,
                'sort_order' => 5,
            ],

            // Mass
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'description' => 'Base unit of mass in the metric system',
                'type' => 'mass',
                'si_unit' => 'kg',
                'conversion_factor' => 1,
                'dimension' => 'M',
                'system' => 'metric',
                'is_base_unit' => true,
                'category' => 'weight',
                'unit_system' => 'SI',
                'unit_group' => 'mass',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'description' => 'Metric unit of mass',
                'type' => 'mass',
                'si_unit' => 'kg',
                'conversion_factor' => 0.001,
                'dimension' => 'M',
                'system' => 'metric',
                'is_base_unit' => false,
                'category' => 'weight',
                'unit_system' => 'CGS',
                'unit_group' => 'mass',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Pound',
                'symbol' => 'lb',
                'description' => 'Imperial unit of mass',
                'type' => 'mass',
                'si_unit' => 'kg',
                'conversion_factor' => 0.45359237,
                'dimension' => 'M',
                'system' => 'imperial',
                'is_base_unit' => false,
                'category' => 'weight',
                'unit_system' => 'Imperial',
                'unit_group' => 'mass',
                'is_active' => true,
                'sort_order' => 8,
            ],

            // Time
            [
                'id' => (string) Str::uuid(),
                'name' => 'Second',
                'symbol' => 's',
                'description' => 'SI base unit of time',
                'type' => 'time',
                'si_unit' => 's',
                'conversion_factor' => 1,
                'dimension' => 'T',
                'system' => 'metric',
                'is_base_unit' => true,
                'category' => 'time',
                'unit_system' => 'SI',
                'unit_group' => 'time',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Minute',
                'symbol' => 'min',
                'description' => '60 seconds',
                'type' => 'time',
                'si_unit' => 's',
                'conversion_factor' => 60,
                'dimension' => 'T',
                'system' => 'metric',
                'is_base_unit' => false,
                'category' => 'time',
                'unit_system' => 'SI',
                'unit_group' => 'time',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Hour',
                'symbol' => 'h',
                'description' => '60 minutes',
                'type' => 'time',
                'si_unit' => 's',
                'conversion_factor' => 3600,
                'dimension' => 'T',
                'system' => 'metric',
                'is_base_unit' => false,
                'category' => 'time',
                'unit_system' => 'SI',
                'unit_group' => 'time',
                'is_active' => true,
                'sort_order' => 11,
            ]
        ];

        foreach ($units as $unitData) {
            Unit::updateOrCreate(
                ['id' => $unitData['id']],
                $unitData
            );
        }

        // Create a test unit
        $testUnit = Unit::create([
            'id' => (string) Str::uuid(),
            'name' => 'Test Unit',
            'symbol' => 'TU',
            'description' => 'Test unit for development',
            'type' => 'test',
            'is_active' => true,
        ]);

        // Create an admin user
        $admin = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'unit_id' => $testUnit->id,
        ]);

        // Assign admin role to the user
        $admin->assignRole('admin');

        // Update unit with created_by and updated_by
        $testUnit->update([
            'created_by_uuid' => $admin->uuid,
            'updated_by_uuid' => $admin->uuid,
        ]);

        // Create a test driver
        $driver = Driver::create([
            'id' => (string) Str::uuid(),
            'name' => 'Test Driver',
            'license_number' => 'DRV12345',
            'email' => 'driver@example.com',
            'phone' => '+1234567890',
            'status' => 'active',
            'unit_uuid' => $testUnit->id,
            'user_id' => $admin->uuid,
        ]);

        // Create some test sensors
        $sensors = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'Temperature Sensor 1',
                'type' => 'temperature',
                'model_number' => 'MODEL-TEM-001',
                'serial_number' => 'TEMP-001',
                'unit_uuid' => $testUnit->id,
                'status' => 'active',
                'last_calibration_date' => now()->subMonths(2),
                'next_calibration_date' => now()->addMonths(10),
                'specifications' => [
                    'accuracy' => '±0.5%',
                    'range' => '-40°C to 125°C',
                ],
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Pressure Sensor 1',
                'type' => 'pressure',
                'model_number' => 'MODEL-PRE-001',
                'serial_number' => 'PRESS-001',
                'unit_uuid' => $testUnit->id,
                'status' => 'active',
                'last_calibration_date' => now()->subMonths(1),
                'next_calibration_date' => now()->addMonths(11),
                'specifications' => [
                    'accuracy' => '±0.25%',
                    'range' => '0 to 1000 mbar',
                ],
            ],
        ];

        foreach ($sensors as $sensorData) {
            Sensor::create($sensorData);
        }
    }
}
