<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reception;

class ReceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reception::create([
            'name' => 'Feast',
            'address' => 'La Farinera Sant Lluis, N-2, Km 761,2 Pont de Molins',
            'maps_url' => 'https://maps.app.goo.gl/iy8Jyg741az36Umf9',
            'date' => '2025-06-14',
            'start_time' => '17:30'
        ]);
    }
}
