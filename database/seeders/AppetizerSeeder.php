<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appetizer;

class AppetizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appetizer::create([
            'name' => 'Appetizer',
            'address' => 'La Farinera Sant Lluis, N-2, Km 761,2 Pont de Molins',
            'maps_url' => 'https://maps.app.goo.gl/iy8Jyg741az36Umf9',
            'date' => '2025-06-14',
            'start_time' => '18:00'
        ]);
    }
}
