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
            'address' => 'La Farinera Sant Lluis, N-2, Km 761,2 Pont de Molins',
            'maps_url' => 'https://maps.app.goo.gl/iy8Jyg741az36Umf9',
            'date' => '2025-06-14',
            'start_time' => '23:00',
        ]);
    }
}
