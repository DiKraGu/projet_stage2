<?php

namespace Database\Seeders;

use App\Models\Ville;
use App\Models\Region;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        $villesParRegion = [
            'Rabat-Salé-Kénitra' => ['Rabat', 'Salé', 'Kénitra'],
            'Casablanca-Settat' => ['Casablanca', 'Mohammédia', 'Settat'],
            'Fès-Meknès' => ['Fès', 'Meknès', 'Ifrane'],
            'Tanger-Tétouan-Al Hoceïma' => ['Tanger', 'Tétouan', 'Al Hoceïma'],
            'Marrakech-Safi' => ['Marrakech', 'Safi', 'Essaouira'],
            'Souss-Massa' => ['Agadir', 'Tiznit', 'Taroudant'],
        ];

        foreach ($villesParRegion as $regionNom => $villes) {
            $region = Region::where('nom', $regionNom)->first();

            if ($region) {
                foreach ($villes as $villeNom) {
                    Ville::firstOrCreate([
                        'nom' => $villeNom,
                        'region_id' => $region->id,
                    ]);
                }
            }
        }
    }
}
