<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeddingInfo;

class WeddingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeddingInfo::create([
            'dress_code' => 'Formal - Se ruega no vestir de blanco'
        ]);
    }
}
