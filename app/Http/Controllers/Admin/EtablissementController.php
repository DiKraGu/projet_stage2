<?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\Etablissement;
// use App\Models\Region;
// use Illuminate\Http\Request;

// class EtablissementController extends Controller
// {
//     public function index()
//     {
//         $etablissements = Etablissement::with('region')->get();
//         return view('admin.etablissements.index', compact('etablissements'));
//     }

//     public function create()
//     {
//         $regions = Region::all();
//         return view('admin.etablissements.create', compact('regions'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'nom' => 'required|string',
//             'region_id' => 'required|exists:regions,id',
//         ]);

//         Etablissement::create($request->only(['nom', 'region_id']));
//         return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement ajouté');
//     }

//     public function edit(Etablissement $etablissement)
//     {
//         $regions = Region::all();
//         return view('admin.etablissements.edit', compact('etablissement', 'regions'));
//     }

//     public function update(Request $request, Etablissement $etablissement)
//     {
//         $request->validate([
//             'nom' => 'required|string',
//             'region_id' => 'required|exists:regions,id',
//         ]);

//         $etablissement->update($request->only(['nom', 'region_id']));
//         return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement modifié');
//     }

//     public function destroy(Etablissement $etablissement)
//     {
//         $etablissement->delete();
//         return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement supprimé');
//     }
// }

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Ville;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    public function index()
    {
        // On récupère aussi la ville et sa région via les relations
        $etablissements = Etablissement::with('ville.region')->get();
        return view('admin.etablissements.index', compact('etablissements'));
    }

    public function create()
    {
        $villes = Ville::with('region')->get();
        return view('admin.etablissements.create', compact('villes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
        ]);

        Etablissement::create($request->only(['nom', 'ville_id']));
        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement ajouté');
    }

    public function edit(Etablissement $etablissement)
    {
        $villes = Ville::with('region')->get();
        return view('admin.etablissements.edit', compact('etablissement', 'villes'));
    }

    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'nom' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
        ]);

        $etablissement->update($request->only(['nom', 'ville_id']));
        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement modifié');
    }

    public function destroy(Etablissement $etablissement)
    {
        $etablissement->delete();
        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement supprimé');
    }
}

