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
            'name' => 'Restaurante Example',
            'address' => 'Calle Example, 456, Barcelona',
            'maps_url' => 'https://maps.google.com/?q=...',
            'time' => '18:30'
        ]);
    }
}
