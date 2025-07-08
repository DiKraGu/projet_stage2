<?php

namespace Database\Seeders;

use App\Models\LotStockAdmin;
use App\Models\Produit;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LotStockAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $produits = Produit::all();

        foreach ($produits as $produit) {
            LotStockAdmin::create([
                'produit_id' => $produit->id,
                'quantite_recue' => rand(10, 100),
                'quantite_disponible' => rand(0, 100),
                'prix_unitaire' => rand(5, 200),
                'date_reception' => Carbon::now()->subDays(rand(0, 60)),
                'date_expiration' => Carbon::now()->addDays(rand(-30, 90)),
            ]);
        }
    }
}
