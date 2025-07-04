<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Region;

class DashboardController extends Controller
{
    public function index()
    {
        $nbRegions = Region::count();
        $nbEtablissements = Etablissement::count();

        return view('admin.dashboard', compact('nbRegions', 'nbEtablissements'));
    }
}
