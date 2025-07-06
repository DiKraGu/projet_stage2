<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Ville;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        // $provinces = Province::with('ville.region')->get();
        $provinces = Province::with('ville.region')
        ->withCount('etablissements') // ← ajoute ce lien
        ->get();
        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        $villes = Ville::with('region')->get();
        return view('admin.provinces.create', compact('villes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
        ]);

        Province::create([
            'nom' => $request->nom,
            'ville_id' => $request->ville_id,
        ]);

        return redirect()->route('admin.provinces.index')->with('success', 'Province ajoutée avec succès.');
    }

    public function edit(Province $province)
    {
        $villes = Ville::with('region')->get();
        return view('admin.provinces.edit', compact('province', 'villes'));
    }

    public function update(Request $request, Province $province)
    {
        $request->validate([
            'nom' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
        ]);

        $province->update([
            'nom' => $request->nom,
            'ville_id' => $request->ville_id,
        ]);
        return redirect()->route('admin.provinces.index')->with('success', 'Province mise à jour avec succès.');
    }

    public function destroy(Province $province)
    {
        $province->delete();
        return redirect()->route('admin.provinces.index')->with('success', 'Province supprimée avec succès.');
    }
}
