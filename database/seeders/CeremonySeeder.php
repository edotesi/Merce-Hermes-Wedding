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
            'name' => 'Iglesia Example',
            'address' => 'Calle Example, 123, Barcelona',
            'maps_url' => 'https://maps.google.com/?q=...',
            'date' => '2025-06-14',
            'time' => '17:00'
        ]);
    }
}
