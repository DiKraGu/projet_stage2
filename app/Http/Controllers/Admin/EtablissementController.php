<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Province;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{

    public function index(Request $request)
{
    $query = Etablissement::with('province.ville.region');

    if ($request->filled('search')) {
        $query->where('nom', 'like', '%' . $request->search . '%');
    }

    $etablissements = $query->paginate(10); // ou paginate(10) si tu veux de la pagination

    return view('admin.etablissements.index', compact('etablissements'));
}


    public function create()
    {
        // Récupérer toutes les provinces avec leur ville et région
        $provinces = Province::with('ville.region')->get();
        return view('admin.etablissements.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
        ]);

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

    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
        ]);

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
