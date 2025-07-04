<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('fournisseur')->get();
        return view('admin.produits.index', compact('produits'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::all();
        return view('admin.produits.create_edit', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'nullable',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
        ]);

        Produit::create($request->all());
        return redirect()->route('admin.produits.index')->with('success', 'Produit ajouté.');
    }

    public function edit(Produit $produit)
    {
        $fournisseurs = Fournisseur::all();
        return view('admin.produits.create_edit', compact('produit', 'fournisseurs'));
    }

    public function update(Request $request, Produit $produit)
    {
        $produit->update($request->all());
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé.');
    }
}
