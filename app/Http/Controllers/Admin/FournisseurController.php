<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::with('produits')->get();
        return view('admin.fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('admin.fournisseurs.create_edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'contact' => 'nullable'
        ]);

        Fournisseur::create($request->all());
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.fournisseurs.create_edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $fournisseur->update($request->all());
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
