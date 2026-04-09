<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::create([
            'name' => 'Kopi Hitam',
            'category' => 'Minuman Brew',
            'price' => 15000,
            'description' => 'Kopi hitam asli',
        ]);

        Menu::create([
            'name' => 'Kopi Susu',
            'category' => 'Minuman Ringan',
            'price' => 18000,
            'description' => 'Kopi dengan susu segar',
        ]);

        Menu::create([
            'name' => 'Nasi Goreng',
            'category' => 'Makanan Berat',
            'price' => 25000,
            'description' => 'Nasi goreng spesial',
        ]);

        Menu::create([
            'name' => 'Kentang Goreng',
            'category' => 'Makanan Ringan',
            'price' => 12000,
            'description' => 'Kentang goreng renyah',
        ]);
    }
}
