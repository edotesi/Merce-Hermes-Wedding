<?php

namespace Database\Seeders;

use App\Models\Appetizer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WeddingInfoSeeder::class,
            CeremonySeeder::class,
            ReceptionSeeder::class,
            GiftSeeder::class,
            PartySeeder::class,
            AppetizerSeeder::class,
            FeastSeeder::class,
        ]);
    }
}
