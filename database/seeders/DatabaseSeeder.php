<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\ServiceCategory;
use App\Models\BlogCategory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // ADMIN
        // ================================================================
        Admin::firstOrCreate(
            ['email' => 'admin@chennaismarcare.in'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('Admin@123!'),
                'role'     => 'super_admin',
                'phone'    => '9444900470',
            ]
        );

        // ================================================================
        // SERVICE CATEGORIES
        // ================================================================
        $ac = ServiceCategory::firstOrCreate(['slug' => 'ac'], [
            'name' => 'Air Conditioner', 'description' => 'Complete AC repair, installation, and maintenance services for all brands',
            'icon' => '❄️', 'color' => '#3B82F6', 'sort_order' => 1, 'is_active' => true,
            'meta_title' => 'AC Repair in Chennai | Fast & Reliable Air Conditioner Service',
            'meta_description' => 'Expert AC repair in Chennai. Split AC, window AC, cassette AC — all brands. Same-day service. 6-month warranty. Call +91 94449 00470!',
        ]);

        $fridge = ServiceCategory::firstOrCreate(['slug' => 'refrigerator'], [
            'name' => 'Refrigerator', 'description' => 'Expert fridge and deep freezer repair for all brands and models',
            'icon' => '🧊', 'color' => '#10B981', 'sort_order' => 2, 'is_active' => true,
            'meta_title' => 'Refrigerator Repair in Chennai | All Brands Serviced',
            'meta_description' => 'Professional refrigerator repair in Chennai. Samsung, LG, Whirlpool, Godrej & all brands. Fast doorstep service. 6-month warranty.',
        ]);

        $washing = ServiceCategory::firstOrCreate(['slug' => 'washing-machine'], [
            'name' => 'Washing Machine', 'description' => 'Front-load, top-load, and semi-automatic washing machine repair',
            'icon' => '👕', 'color' => '#8B5CF6', 'sort_order' => 3, 'is_active' => true,
            'meta_title' => 'Washing Machine Repair in Chennai | Front Load & Top Load',
            'meta_description' => 'Best washing machine repair service in Chennai. Same-day service, genuine spare parts, 6-month warranty.',
        ]);

        $microwave = ServiceCategory::firstOrCreate(['slug' => 'microwave-oven'], [
            'name' => 'Microwave Oven', 'description' => 'Microwave oven repair and maintenance services for all brands',
            'icon' => '🔥', 'color' => '#F97316', 'sort_order' => 4, 'is_active' => true,
            'meta_title' => 'Microwave Oven Repair in Chennai | All Brands Service',
            'meta_description' => 'Professional microwave oven repair in Chennai. Solo, Grill & Convection models serviced. Same-day doorstep repair.',
        ]);

        // ---- AC Services ----
        foreach ([
            ['name' => 'AC Gas Refilling',          'slug' => 'ac-gas-refilling',    'base_price' => 1200, 'duration_estimate' => '1–2 hours', 'features' => ['R22/R32/R410A refrigerant','Pressure check','Leak detection'],             'sort_order' => 1, 'is_featured' => true],
            ['name' => 'AC Service & Cleaning',     'slug' => 'ac-service-cleaning', 'base_price' => 499,  'duration_estimate' => '1 hour',    'features' => ['Deep filter clean','Coil wash','Performance check'],                        'sort_order' => 2, 'is_featured' => true],
            ['name' => 'AC PCB Repair',             'slug' => 'ac-pcb-repair',       'base_price' => 1500, 'duration_estimate' => '2–3 hours', 'features' => ['Motherboard diagnosis','Component replacement','Testing'],                  'sort_order' => 3, 'is_featured' => false],
            ['name' => 'AC Installation',           'slug' => 'ac-installation',     'base_price' => 1200, 'duration_estimate' => '2–4 hours', 'features' => ['Professional mounting','Pipe laying','Electrical connections'],            'sort_order' => 4, 'is_featured' => false],
            ['name' => 'AC Compressor Replacement', 'slug' => 'ac-compressor',       'base_price' => 5000, 'duration_estimate' => '3–4 hours', 'features' => ['OEM compressor','6-month warranty','Gas top-up included'],                  'sort_order' => 5, 'is_featured' => false],
        ] as $s) {
            $ac->services()->firstOrCreate(['slug' => $s['slug']], array_merge($s, ['warranty_days' => 180, 'is_active' => true]));
        }

        // ---- Refrigerator Services ----
        foreach ([
            ['name' => 'Fridge Not Cooling Fix',         'slug' => 'fridge-not-cooling',  'base_price' => 800,  'duration_estimate' => '1–2 hours', 'features' => ['Compressor test','Gas check','Thermostat check'],                    'sort_order' => 1, 'is_featured' => true],
            ['name' => 'Refrigerator Gas Charging',      'slug' => 'fridge-gas-charging', 'base_price' => 1200, 'duration_estimate' => '2 hours',   'features' => ['R600A/R134A refrigerant','Leak test','Vacuum process'],             'sort_order' => 2, 'is_featured' => false],
            ['name' => 'Fridge PCB / Thermostat Repair', 'slug' => 'fridge-pcb-repair',   'base_price' => 1200, 'duration_estimate' => '2 hours',   'features' => ['Diagnosis','Component replacement','Full test'],                   'sort_order' => 3, 'is_featured' => false],
            ['name' => 'Ice Maker Repair',               'slug' => 'ice-maker-repair',    'base_price' => 900,  'duration_estimate' => '1–2 hours', 'features' => ['Ice maker diagnosis','Water valve check','Testing'],               'sort_order' => 4, 'is_featured' => false],
        ] as $s) {
            $fridge->services()->firstOrCreate(['slug' => $s['slug']], array_merge($s, ['warranty_days' => 180, 'is_active' => true]));
        }

        // ---- Washing Machine Services ----
        foreach ([
            ['name' => 'WM Not Spinning Fix',           'slug' => 'wm-not-spinning',  'base_price' => 700,  'duration_estimate' => '1–2 hours', 'features' => ['Motor check','Belt inspection','Control board test'],               'sort_order' => 1, 'is_featured' => true],
            ['name' => 'WM Drum Bearing Replacement',   'slug' => 'wm-drum-bearing',  'base_price' => 1200, 'duration_estimate' => '2–3 hours', 'features' => ['OEM bearings','Seal replacement','Vibration test'],                  'sort_order' => 2, 'is_featured' => false],
            ['name' => 'WM Water Leakage Fix',          'slug' => 'wm-water-leakage', 'base_price' => 600,  'duration_estimate' => '1 hour',    'features' => ['Door seal check','Drain hose inspection','Pump test'],              'sort_order' => 3, 'is_featured' => false],
            ['name' => 'WM PCB / Control Panel Repair', 'slug' => 'wm-pcb-repair',    'base_price' => 1500, 'duration_estimate' => '2–3 hours', 'features' => ['Full diagnosis','Board repair or replace','Testing'],              'sort_order' => 4, 'is_featured' => false],
        ] as $s) {
            $washing->services()->firstOrCreate(['slug' => $s['slug']], array_merge($s, ['warranty_days' => 180, 'is_active' => true]));
        }

        // ---- Microwave Services ----
        foreach ([
            ['name' => 'Microwave Not Heating Fix',    'slug' => 'microwave-not-heating',       'base_price' => 800,  'duration_estimate' => '1–2 hours', 'features' => ['Magnetron check','High-voltage diode test','Capacitor inspection'],         'sort_order' => 1, 'is_featured' => true],
            ['name' => 'Microwave PCB Repair',         'slug' => 'microwave-pcb-repair',         'base_price' => 1200, 'duration_estimate' => '2 hours',   'features' => ['Control board diagnosis','Component replacement','Full functionality test'], 'sort_order' => 2, 'is_featured' => false],
            ['name' => 'Microwave Turntable Repair',   'slug' => 'microwave-turntable-repair',   'base_price' => 600,  'duration_estimate' => '1 hour',    'features' => ['Motor inspection','Coupler check','Roller ring alignment'],                  'sort_order' => 3, 'is_featured' => false],
            ['name' => 'Microwave Door Switch Repair', 'slug' => 'microwave-door-switch-repair', 'base_price' => 700,  'duration_estimate' => '1 hour',    'features' => ['Door latch inspection','Safety switch replacement','Testing'],              'sort_order' => 4, 'is_featured' => false],
        ] as $s) {
            $microwave->services()->firstOrCreate(['slug' => $s['slug']], array_merge($s, ['warranty_days' => 180, 'is_active' => true]));
        }

        // ---- Blog Categories ----
        BlogCategory::firstOrCreate(['slug' => 'ac-repair-tips'],         ['name' => 'AC Repair Tips']);
        BlogCategory::firstOrCreate(['slug' => 'refrigerator-care'],      ['name' => 'Refrigerator Care']);
        BlogCategory::firstOrCreate(['slug' => 'washing-machine-guide'],  ['name' => 'Washing Machine Guide']);
        BlogCategory::firstOrCreate(['slug' => 'microwave-oven-service'], ['name' => 'Microwave Oven Service']);

        $this->call(BlogPostSeeder::class);
    }
}