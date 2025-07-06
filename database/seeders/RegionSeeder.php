<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'Tanger-Tétouan-Al Hoceïma',
            'L\'Oriental',
            'Fès-Meknès',
            'Rabat-Salé-Kénitra',
            'Béni Mellal-Khénifra',
            'Casablanca-Settat',
            'Marrakech-Safi',
            'Drâa-Tafilalet',
            'Souss-Massa',
            'Guelmim-Oued Noun',
            'Laâyoune-Sakia El Hamra',
            'Dakhla-Oued Ed-Dahab',
        ];

        foreach ($regions as $nom) {
            Region::firstOrCreate(['nom' => $nom]);
        }

    }
}
