<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gift;

class GiftSeeder extends Seeder
{
    public function run(): void
    {
        $gifts = [
            [
                'name' => 'KitchenAid Artisan',
                'price' => 499.99,
                'image_url' => '/storage/gifts/kitchenaid.jpg',
                'product_url' => 'https://www.amazon.es/dp/B08TQZQFL9',
                'stock' => 1
            ],
            [
                'name' => 'Set de Sartenes Le Creuset',
                'price' => 299.99,
                'image_url' => '/storage/gifts/lecreuset.jpg',
                'product_url' => 'https://www.elcorteingles.es/hogar/MP_0031033_000000000000636044/',
                'stock' => 1
            ],
            [
                'name' => 'Robot Aspirador Roomba',
                'price' => 399.99,
                'image_url' => '/storage/gifts/roomba.jpg',
                'product_url' => 'https://www.mediamarkt.es/es/product/_robot-aspirador-irobot-roomba-j7-wifi',
                'stock' => 1
            ],
            // Añade más regalos aquí
        ];

        foreach ($gifts as $gift) {
            Gift::create($gift);
        }
    }
}
