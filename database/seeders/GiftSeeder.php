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
                'name' => 'Aspiradora sin cables Dyson Gen5detect™ Absolute',
                'price' => 809.00,
                'image_url' => 'https://dyson-h.assetsadobe2.com/is/image/content/dam/dyson/leap-petite-global/products/floorcare/sticks/418/pdp/iron-purple/418-PDP-Video-Thumbnail-Purple.jpg?$responsive$&cropPathE=desktop&fit=stretch,1&wid=1920',
                'product_url' => 'https://www.dyson.es/aspiradoras/sin-cable/gen5detect/absolute-niquel-morado',
                'stock' => 1
            ],
            [
                'name' => 'Xiaomi Robot Vacuum X20 Max',
                'price' => 649.99,
                'image_url' => 'https://i02.appmifile.com/mi-com-product/fly-birds/xiaomi-robot-vacuum-x20-max/PC/header_bg.jpg?f=webp',
                'product_url' => 'https://www.mi.com/es/product/xiaomi-robot-vacuum-x20-max/',
                'stock' => 1
            ],
            [
                'name' => 'Lattissima One Porcelain White',
                'price' => 289.90,
                'image_url' => 'https://cb.scene7.com/is/image/Crate/Lttssma1EVODlngSWhtSSS21_VND',
                'product_url' => 'https://www.nespresso.com/ecom/medias/sys_master/public/31372890439710/Global-All-OL-Lattissima-One-White-Front-View-Horizontale-Without-Caps-square-2021-2026.jpg?impolicy=medium&imwidth=775',
                'stock' => 1
            ],
            // Añade más regalos aquí
        ];

        foreach ($gifts as $gift) {
            Gift::create($gift);
        }
    }
}
