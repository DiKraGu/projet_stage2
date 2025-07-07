<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        // S'assurer qu'on a bien des catégories et des fournisseurs
        $categories = Categorie::all();
        $fournisseurs = Fournisseur::all();

        if ($categories->count() < 1 || $fournisseurs->count() < 1) {
            $this->command->warn('Aucune catégorie ou fournisseur trouvé. Seeder ignoré.');
            return;
        }

        foreach (range(1, 12) as $i) {
            Produit::create([
                'nom' => 'Produit' . $i,
                'description' => 'Description du Produit' . $i,
                'categorie_id' => $categories->random()->id,
                'fournisseur_id' => $fournisseurs->random()->id,
            ]);
        }
    }
}
