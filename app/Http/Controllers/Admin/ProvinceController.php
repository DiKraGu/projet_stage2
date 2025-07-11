<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Region;
use App\Models\Ville;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{

public function index(Request $request)
{
    $villes = Ville::has('provinces')->orderBy('nom')->get();
    $regions = Region::has('villes.provinces')->orderBy('nom')->get();

    $query = Province::with('ville.region')
        ->withCount('etablissements');

    if ($request->filled('search')) {
        $query->where('nom', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->filled('ville_id')) {
        $query->where('ville_id', $request->ville_id);
    }

    if ($request->filled('region_id')) {
        $query->whereHas('ville.region', function ($q) use ($request) {
            $q->where('id', $request->region_id);
        });
    }

    $provinces = $query->paginate(10);

    return view('admin.provinces.index', compact('provinces', 'villes', 'regions'));
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

    // Vérifie si une province avec ce nom existe déjà, quelle que soit la ville
    $exist = Province::where('nom', $request->nom)->exists();

    if ($exist) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['nom' => 'Une province avec ce nom existe déjà']);
    }

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

    // Vérifie s'il existe une autre province (≠ ID) avec le même nom, dans n'importe quelle ville
    $exist = Province::where('nom', $request->nom)
        ->where('id', '!=', $province->id)
        ->exists();

    if ($exist) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['nom' => 'Une province avec ce nom existe déjà']);
    }

    $province->update([
        'nom' => $request->nom,
        'ville_id' => $request->ville_id,
    ]);

    return redirect()->route('admin.provinces.index')->with('success', 'Province modifiée avec succès.');
}



    public function destroy(Province $province)
    {
        $province->delete();
        return redirect()->route('admin.provinces.index')->with('success', 'Province supprimée avec succès.');
    }
}
