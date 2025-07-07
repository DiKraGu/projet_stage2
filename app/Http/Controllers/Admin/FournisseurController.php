<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::with('produits')->paginate(10);
        return view('admin.fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('admin.fournisseurs.create_edit');
    }

public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'contact' => 'nullable|string|max:255',
    ]);

    $errors = [];

    // Vérifier si le nom existe déjà
    if (Fournisseur::where('nom', $request->nom)->exists()) {
        $errors['nom'] = 'Un fournisseur avec ce nom existe déjà.';
    }

    // Vérifier si le contact existe déjà (si non null)
    if ($request->contact && Fournisseur::where('contact', $request->contact)->exists()) {
        $errors['contact'] = 'Un fournisseur avec ce contact existe déjà.';
    }

    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }

    Fournisseur::create($request->all());
    return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès.');
}

    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.fournisseurs.create_edit', compact('fournisseur'));
    }

public function update(Request $request, Fournisseur $fournisseur)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'contact' => 'nullable|string|max:255',
    ]);

    $errors = [];

    // Vérifie si un autre fournisseur a le même nom
    if (Fournisseur::where('nom', $request->nom)->where('id', '!=', $fournisseur->id)->exists()) {
        $errors['nom'] = 'Un autre fournisseur avec ce nom existe déjà.';
    }

    // Vérifie si un autre fournisseur a le même contact
    if ($request->contact && Fournisseur::where('contact', $request->contact)->where('id', '!=', $fournisseur->id)->exists()) {
        $errors['contact'] = 'Un autre fournisseur avec ce contact existe déjà.';
    }

    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }

    $fournisseur->update($request->all());
    return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
}

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
