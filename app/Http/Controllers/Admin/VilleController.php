<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use App\Models\Region;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    public function index()
    {
        $villes = Ville::with('region')->get();
        return view('admin.villes.index', compact('villes'));
    }

    public function create()
    {
        $regions = Region::all();
        return view('admin.villes.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        Ville::create([
            'nom' => $request->nom,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('admin.villes.index')->with('success', 'Ville ajoutée avec succès.');
    }

    public function edit(Ville $ville)
    {
        $regions = Region::all();
        return view('admin.villes.edit', compact('ville', 'regions'));
    }

    public function update(Request $request, Ville $ville)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        $ville->update([
            'nom' => $request->nom,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('admin.villes.index')->with('success', 'Ville mise à jour avec succès.');
    }

    public function destroy(Ville $ville)
    {
        $ville->delete();
        return redirect()->route('admin.villes.index')->with('success', 'Ville supprimée avec succès.');
    }
}
