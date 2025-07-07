<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Province;
use App\Models\Region;
use App\Models\Ville;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{

public function index(Request $request)
{
    $query = Etablissement::with('province.ville.region');

    // Filtrage par nom
    if ($request->filled('search')) {
        $query->where('nom', 'like', '%' . $request->search . '%');
    }

    // Filtrage par province
    if ($request->filled('province_id')) {
        $query->where('province_id', $request->province_id);
    }

    // Filtrage par ville (via relation)
    if ($request->filled('ville_id')) {
        $query->whereHas('province.ville', function ($q) use ($request) {
            $q->where('id', $request->ville_id);
        });
    }

    // Filtrage par région (via relation)
    if ($request->filled('region_id')) {
        $query->whereHas('province.ville.region', function ($q) use ($request) {
            $q->where('id', $request->region_id);
        });
    }

    $etablissements = $query->paginate(10);

    // Données pour les selects
    $provinces = Province::has('etablissements')->with('ville')->get();
    $villes = Ville::has('provinces.etablissements')->get();
    $regions = Region::has('villes.provinces.etablissements')->get();

    return view('admin.etablissements.index', compact('etablissements', 'provinces', 'villes', 'regions'));
}

    public function create()
    {
        // Récupérer toutes les provinces avec leur ville et région
        $provinces = Province::with('ville.region')->get();
        return view('admin.etablissements.create', compact('provinces'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required|string|max:255',
    //         'province_id' => 'required|exists:provinces,id',
    //     ]);

    //     Etablissement::create([
    //         'nom' => $request->nom,
    //         'province_id' => $request->province_id,
    //     ]);

    //     return redirect()->route('admin.etablissements.index')->with('success', 'Établissement ajouté avec succès.');
    // }

    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'province_id' => 'required|exists:provinces,id',
    ]);

    $exist = Etablissement::where('nom', $request->nom)->exists();

    if ($exist) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['nom' => 'Un établissement avec ce nom existe déjà.']);
    }

    Etablissement::create([
        'nom' => $request->nom,
        'province_id' => $request->province_id,
    ]);

    return redirect()->route('admin.etablissements.index')->with('success', 'Établissement ajouté avec succès.');
}

    public function edit(Etablissement $etablissement)
    {
        $provinces = Province::with('ville.region')->get();
        return view('admin.etablissements.edit', compact('etablissement', 'provinces'));
    }

    // public function update(Request $request, Etablissement $etablissement)
    // {
    //     $request->validate([
    //         'nom' => 'required|string|max:255',
    //         'province_id' => 'required|exists:provinces,id',
    //     ]);

    //     $etablissement->update([
    //         'nom' => $request->nom,
    //         'province_id' => $request->province_id,
    //     ]);

    //     return redirect()->route('admin.etablissements.index')->with('success', 'Établissement modifié avec succès.');
    // }

    public function update(Request $request, Etablissement $etablissement)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'province_id' => 'required|exists:provinces,id',
    ]);

    $exist = Etablissement::where('nom', $request->nom)
        ->where('id', '!=', $etablissement->id)
        ->exists();

    if ($exist) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['nom' => 'Un établissement avec ce nom existe déjà.']);
    }

    $etablissement->update([
        'nom' => $request->nom,
        'province_id' => $request->province_id,
    ]);

    return redirect()->route('admin.etablissements.index')->with('success', 'Établissement modifié avec succès.');
}


    public function destroy(Etablissement $etablissement)
    {
        $etablissement->delete();
        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement supprimé avec succès.');
    }
}
