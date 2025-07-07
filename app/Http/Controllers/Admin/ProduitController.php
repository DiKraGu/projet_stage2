<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\Categorie;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
public function index(Request $request)
{
    $query = Produit::with(['fournisseur', 'categorie']);

    // if ($request->has('search') && !empty($request->search)) {
    //     $query->where('nom', 'LIKE', '%' . $request->search . '%');
    // }

    if ($request->filled('search')) {
        $query->where('nom', 'like', '%' . $request->search . '%');
    }

    $produits = $query->paginate(10);

    return view('admin.produits.index', compact('produits'));
}


    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $categories = Categorie::all();
        return view('admin.produits.create_edit', compact('fournisseurs', 'categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'categorie_id' => 'required|exists:categories,id',
    ]);

    // Vérifie si un produit avec ce nom existe déjà (peu importe la catégorie)
    if (Produit::where('nom', $request->nom)->exists()) {
        return redirect()->back()
            ->withErrors(['nom' => 'Un produit avec ce nom existe déjà.'])
            ->withInput();
    }

    Produit::create([
        'nom' => $request->nom,
        'description' => $request->description,
        'fournisseur_id' => $request->fournisseur_id,
        'categorie_id' => $request->categorie_id,
    ]);

    return redirect()->route('admin.produits.index')->with('success', 'Produit ajouté.');
}

    public function edit(Produit $produit)
    {
        $fournisseurs = Fournisseur::all();
        $categories = Categorie::all();
        return view('admin.produits.create_edit', compact('produit', 'fournisseurs', 'categories'));
    }


    public function update(Request $request, Produit $produit)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'categorie_id' => 'required|exists:categories,id',
    ]);

    // Vérifie si un autre produit a déjà ce nom
    $exists = Produit::where('nom', $request->nom)
                ->where('id', '!=', $produit->id)
                ->exists();

    if ($exists) {
        return redirect()->back()
            ->withErrors(['nom' => 'Un autre produit avec ce nom existe déjà.'])
            ->withInput();
    }

    $produit->update([
        'nom' => $request->nom,
        'description' => $request->description,
        'fournisseur_id' => $request->fournisseur_id,
        'categorie_id' => $request->categorie_id,
    ]);

    return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour.');
}

    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé.');
    }
}
