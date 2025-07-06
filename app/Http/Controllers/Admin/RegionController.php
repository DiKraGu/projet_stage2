<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    // public function index()
    // {
    //     // Charger les régions avec le nombre d'établissements via les villes
    // // $regions = Region::withCount(['villes', 'etablissements'])->get();
    //     $regions = Region::withCount('villes')->with(['villes.provinces.etablissements'])->paginate(10);

    //     return view('admin.regions.index', compact('regions'));
    // }

    public function index(Request $request)
{
    $query = Region::withCount('villes')->with(['villes.provinces.etablissements']);

    if ($request->filled('search')) {
        $query->where('nom', 'like', '%' . $request->search . '%');
    }

    $regions = $query->paginate(10);

    return view('admin.regions.index', compact('regions'));
}

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:regions,nom',
        ], [
            'nom.unique' => 'Cette région existe déjà.',
            'nom.required' => 'Le nom de la région est obligatoire.',
        ]);


        Region::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Région ajoutée');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'nom' => 'required|string|unique:regions,nom,' . $region->id,
        ], [
            'nom.unique' => 'Une autre région porte déjà ce nom.',
            'nom.required' => 'Le nom de la région est obligatoire.',
        ]);

        $region->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Région modifiée');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Région supprimée');
    }
}
