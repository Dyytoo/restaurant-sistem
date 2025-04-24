<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'address' => 'Jl. Contoh No. 123'
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test No. 456'
        ]);

        User::create([
            'name' => 'Bob Williams',
            'email' => 'bob@example.com',
            'phone' => '083145678901',
            'address' => 'Jl. Anggrek No. 89'
        ]);
        
        User::create([
            'name' => 'Clara Davis',
            'email' => 'clara@example.com',
            'phone' => '084234567890',
            'address' => 'Jl. Melati No. 32'
        ]);
        
        User::create([
            'name' => 'Daniel Brown',
            'email' => 'daniel@example.com',
            'phone' => '085987654321',
            'address' => 'Jl. Kenanga No. 55'
        ]);
    }
}
