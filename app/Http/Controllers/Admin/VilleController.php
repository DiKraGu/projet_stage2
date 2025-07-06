<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use App\Models\Region;
use Illuminate\Http\Request;

class VilleController extends Controller
{

//     public function index(Request $request)
// {
//     // Récupérer toutes les régions pour la liste déroulante
//     $regions = Region::all();

//     $query = Ville::with('region', 'provinces.etablissements');

//     // Filtrer par région si choisi
//     if ($request->filled('region_id')) {
//         $query->where('region_id', $request->region_id);
//     }

//     // Filtrer par ville si choisi
//     if ($request->filled('ville_id')) {
//         $query->where('id', $request->ville_id);
//     }

//     // $villes = $query->withCount(['provinces'])->get();
//     $villes = $query->withCount(['provinces'])->paginate(10); // 10 éléments par page


//     return view('admin.villes.index', compact('villes', 'regions'));
// }

public function index(Request $request)
{
    // ✅ Récupérer uniquement les régions qui ont des villes, triées par nom
    $regions = Region::whereHas('villes')->orderBy('nom')->get();

    // ✅ Récupérer uniquement les villes enregistrées, triées par nom
    $villesFiltrage = Ville::orderBy('nom')->get();

    $query = Ville::with('region', 'provinces.etablissements');

    if ($request->filled('region_id')) {
        $query->where('region_id', $request->region_id);
    }

    if ($request->filled('ville_id')) {
        $query->where('id', $request->ville_id);
    }

    $villes = $query->withCount(['provinces'])->paginate(10);

    return view('admin.villes.index', compact('villes', 'regions', 'villesFiltrage'));
}



    public function create()
    {
        $regions = Region::all();
        return view('admin.villes.create', compact('regions'));
    }


//     public function store(Request $request)
// {
// $request->validate([
//     'nom' => [
//         'required',
//         'string',
//         'max:255',
//         function ($attribute, $value, $fail) use ($request) {
//             $exists = \App\Models\Ville::where('nom', $value)
//                 ->where('region_id', $request->region_id)
//                 ->exists();

//             if ($exists) {
//                 $fail('Une ville avec ce nom existe déjà dans cette région.');
//             }
//         },
//     ],
//     'region_id' => 'required|exists:regions,id',
// ]);

//     Ville::create([
//         'nom' => $request->nom,
//         'region_id' => $request->region_id,
//     ]);

//     return redirect()->route('admin.villes.index')->with('success', 'Ville ajoutée avec succès.');
// }

public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'region_id' => 'required|exists:regions,id',
    ]);

    // Vérifier si la ville existe déjà avec le même nom dans une autre région
    $villeExistante = Ville::where('nom', $request->nom)
        ->where('region_id', '!=', $request->region_id)
        ->first();

    if ($villeExistante) {
        return redirect()->back()
            ->withErrors(['nom' => 'Cette ville est déjà associée à une autre région.'])
            ->withInput();
    }

    // Vérifier si la ville existe déjà dans la même région (éviter les doublons exacts)
    $doublon = Ville::where('nom', $request->nom)
        ->where('region_id', $request->region_id)
        ->exists();

    if ($doublon) {
        return redirect()->back()
            ->withErrors(['nom' => 'Cette ville existe déjà dans cette région.'])
            ->withInput();
    }

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


//     public function update(Request $request, Ville $ville)
// {
// $request->validate([
//     'nom' => [
//         'required',
//         'string',
//         'max:255',
//         function ($attribute, $value, $fail) use ($request, $ville) {
//             $exists = \App\Models\Ville::where('nom', $value)
//                 ->where('region_id', $request->region_id)
//                 ->where('id', '!=', $ville->id)
//                 ->exists();

//             if ($exists) {
//                 $fail('Une autre ville avec ce nom existe déjà dans cette région.');
//             }
//         },
//     ],
//     'region_id' => 'required|exists:regions,id',
// ]);

//     $ville->update([
//         'nom' => $request->nom,
//         'region_id' => $request->region_id,
//     ]);

//     return redirect()->route('admin.villes.index')->with('success', 'Ville mise à jour avec succès.');
// }

public function update(Request $request, Ville $ville)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'region_id' => 'required|exists:regions,id',
    ]);

    // Vérifie s'il existe déjà une AUTRE ville avec le même nom dans la même région
    $exist = Ville::where('id', '!=', $ville->id)
        ->where('nom', $request->nom)
        ->where('region_id', $request->region_id)
        ->first();

    if ($exist) {
        return redirect()->back()
            ->withErrors(['nom' => 'Cette ville existe déjà dans cette région.'])
            ->withInput();
    }

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
