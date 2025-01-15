<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    public function run(): void
    {
        Party::create([
            'name' => 'Fiesta',
            'description' => 'Descripción de la fiesta...',
            'address' => 'Dirección de la fiesta',
            'maps_url' => 'https://maps.google.com/?q=...',
            'start_time' => '22:00',
            'end_time' => '04:00'
        ]);
    }
}
