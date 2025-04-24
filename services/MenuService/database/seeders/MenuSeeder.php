<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'name' => 'Nasi Goreng',
            'description' => 'Nasi goreng spesial dengan telur dan ayam',
            'price' => 25000,
            'category' => 'makanan'
        ]);

        Menu::create([
            'name' => 'Es Teh',
            'description' => 'Es teh manis segar',
            'price' => 5000,
            'category' => 'minuman'
        ]);

        Menu::create([
            'name' => 'Mie Ayam',
            'description' => 'Mie ayam lezat dengan pangsit',
            'price' => 12000,
            'category' => 'makanan'
        ]);
        
        Menu::create([
            'name' => 'Jus Alpukat',
            'description' => 'Jus alpukat segar dengan susu coklat',
            'price' => 10000,
            'category' => 'minuman'
        ]);
        
        Menu::create([
            'name' => 'Sate Ayam',
            'description' => 'Sate ayam dengan bumbu kacang',
            'price' => 18000,
            'category' => 'makanan'
        ]);
        
        Menu::create([
            'name' => 'Soto Ayam',
            'description' => 'Soto ayam hangat dengan nasi',
            'price' => 14000,
            'category' => 'makanan'
        ]);
        
        Menu::create([
            'name' => 'Air Mineral',
            'description' => 'Air mineral botol 600ml',
            'price' => 3000,
            'category' => 'minuman'
        ]);
        
        Menu::create([
            'name' => 'Ayam Geprek',
            'description' => 'Ayam geprek level pedas dengan nasi',
            'price' => 17000,
            'category' => 'makanan'
        ]);
        
        Menu::create([
            'name' => 'Teh Tarik',
            'description' => 'Teh tarik khas Malaysia',
            'price' => 7000,
            'category' => 'minuman'
        ]);
        
        Menu::create([
            'name' => 'Bakso',
            'description' => 'Bakso urat dengan kuah kaldu gurih',
            'price' => 13000,
            'category' => 'makanan'
        ]);

        // Tambahkan lebih banyak menu
    }
}
