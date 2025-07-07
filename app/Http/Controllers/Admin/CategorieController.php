<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

    public function index()
{
    // On récupère les catégories avec le nombre de produits
    $categories = \App\Models\Categorie::withCount('produits')->paginate(10);
    return view('admin.categories.index', compact('categories'));
}

    public function create()
    {
        return view('admin.categories.create_edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:categories,nom|max:255',
        ]);

        Categorie::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie ajoutée.');
    }

    public function edit(Categorie $category)
    {
        return view('admin.categories.create_edit', ['categorie' => $category]);
    }

    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
        ]);

        $category->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
