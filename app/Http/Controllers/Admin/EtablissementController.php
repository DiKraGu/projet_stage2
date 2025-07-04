<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Region;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    public function index()
    {
        $etablissements = Etablissement::with('region')->get();
        return view('admin.etablissements.index', compact('etablissements'));
    }

    public function create()
    {
        $regions = Region::all();
        return view('admin.etablissements.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        Etablissement::create($request->only(['nom', 'region_id']));
        return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement ajouté');
    }

    public function edit(Etablissement $etablissement)
    {
        $regions = Region::all();
        return view('admin.etablissements.edit', compact('etablissement', 'regions'));
    }

    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'nom' => 'required|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        $etablissement->update($request->only(['nom', 'region_id']));
        return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement modifié');
    }

    public function destroy(Etablissement $etablissement)
    {
        $etablissement->delete();
        return redirect()->route('admin.etablissements.index')->with('success', 'Etablissement supprimé');
    }
}
