<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gift;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gifts = [
            [
                'name' => 'Tostadora',
                'price' => 49.99,
                'image_url' => 'https://example.com/tostadora.jpg'
            ],
            [
                'name' => 'Cafetera',
                'price' => 129.99,
                'image_url' => 'https://example.com/cafetera.jpg'
            ]
        ];

        foreach ($gifts as $gift) {
            Gift::create($gift);
        }
    }
}
