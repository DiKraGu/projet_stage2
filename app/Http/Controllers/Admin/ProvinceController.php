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
        ->paginate(10);
        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        $villes = Ville::with('region')->get();
        return view('admin.provinces.create', compact('villes'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required|string',
    //         'ville_id' => 'required|exists:villes,id',
    //     ]);

    //     Province::create([
    //         'nom' => $request->nom,
    //         'ville_id' => $request->ville_id,
    //     ]);

    //     return redirect()->route('admin.provinces.index')->with('success', 'Province ajoutée avec succès.');
    // }

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

    // public function update(Request $request, Province $province)
    // {
    //     $request->validate([
    //         'nom' => 'required|string',
    //         'ville_id' => 'required|exists:villes,id',
    //     ]);

    //     $province->update([
    //         'nom' => $request->nom,
    //         'ville_id' => $request->ville_id,
    //     ]);
    //     return redirect()->route('admin.provinces.index')->with('success', 'Province mise à jour avec succès.');
    // }

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
