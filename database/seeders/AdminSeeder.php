<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'nom' => 'admin',
                'prenom' => 'admin',
                'password' => Hash::make('password') // remplace si besoin
            ]
        );
    }
}
