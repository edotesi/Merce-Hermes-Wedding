<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceremony;

class CeremonySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ceremony::create([
            'name' => 'Ceremony',
            'address' => 'Plaça Mossèn Cinto Verdaguer, 1 Castelló d’Empúries',
            'maps_url' => 'https://maps.app.goo.gl/CqBnFYKpLvKMSMNr8',
            'date' => '2025-06-14',
            'start_time' => '15:30'
        ]);
    }
}
