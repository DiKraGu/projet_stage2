<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Etablissement;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Province;
use App\Models\Region;
use App\Models\Ville;

class DashboardController extends Controller
{
    public function index()
    {
        $nbRegions = Region::count();
        $nbEtablissements = Etablissement::count();
        $nbFournisseurs = Fournisseur::count();
        $nbProduits = Produit::count();
        $nbVilles = Ville::count();
        $nbProvinces = Province::count();
        $nbCategories = Categorie::count();



        return view('admin.dashboard', compact(
            'nbRegions',
            'nbEtablissements',
            'nbFournisseurs',
            'nbProduits',
            'nbVilles',
            'nbProvinces',
            'nbCategories'
        ));
    }
}
