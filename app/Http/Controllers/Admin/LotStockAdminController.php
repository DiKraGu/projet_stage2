<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LotStockAdmin;
use App\Models\Produit;
use Illuminate\Http\Request;

class LotStockAdminController extends Controller
{


//     public function index(Request $request)
// {
//     $query = LotStockAdmin::with('produit')->orderBy('date_reception', 'desc');

//     // Filtrage par nom de produit
//     if ($request->filled('search')) {
//         $query->whereHas('produit', function ($q) use ($request) {
//             $q->where('nom', 'like', '%' . $request->search . '%');
//         });
//     }

//     // Filtrage par état
//     if ($request->filled('etat')) {
//         $etat = $request->etat;
//         $query->where(function ($q) use ($etat) {
//             if ($etat === 'actif') {
//                 $q->where('quantite_disponible', '>', 0)
//                   ->where('date_expiration', '>=', now()->toDateString());
//             } elseif ($etat === 'perime') {
//                 $q->where('date_expiration', '<', now()->toDateString());
//             } elseif ($etat === 'epuise') {
//                 $q->where('quantite_disponible', '=', 0);
//             }
//         });
//     }

//     $lots = $query->get();

//     return view('admin.stocks.index', compact('lots'));
// }

public function index(Request $request)
{
    $query = LotStockAdmin::with('produit')->orderBy('date_reception', 'desc');

    // Filtrage par nom de produit
    if ($request->filled('search')) {
        $query->whereHas('produit', function ($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->search . '%');
        });
    }

    // Filtrage par état
    if ($request->filled('etat')) {
        $etat = $request->etat;
        $query->where(function ($q) use ($etat) {
            if ($etat === 'actif') {
                $q->where('quantite_disponible', '>', 0)
                  ->where('date_expiration', '>=', now()->toDateString());
            } elseif ($etat === 'perime') {
                $q->where('date_expiration', '<', now()->toDateString());
            } elseif ($etat === 'epuise') {
                $q->where('quantite_disponible', '=', 0);
            }
        });
    }

    $lots = $query->get()
        ->sortBy(function ($lot) {
            if ($lot->isExpired()) return 0;          // périmé en premier
            if ($lot->quantite_disponible == 0) return 1; // épuisé ensuite
            return 2;                                // actif en dernier
        })
        ->values();

    return view('admin.stocks.index', compact('lots'));
}



    public function create()
    {
        $produits = Produit::all();
        return view('admin.stocks.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite_recue' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_expiration' => 'required|date',
            'date_reception' => 'required|date',
        ]);

        LotStockAdmin::create([
            'produit_id' => $request->produit_id,
            'quantite_recue' => $request->quantite_recue,
            'quantite_disponible' => $request->quantite_recue,
            'prix_unitaire' => $request->prix_unitaire,
            'date_expiration' => $request->date_expiration,
            'date_reception' => $request->date_reception,
        ]);

        return redirect()->route('admin.stocks.index')->with('success', 'Lot ajouté avec succès.');
    }

    public function edit(LotStockAdmin $stock)
    {
        $produits = Produit::all();
        return view('admin.stocks.edit', compact('stock', 'produits'));
    }

    // public function update(Request $request, LotStockAdmin $stock)
    // {
    //     $request->validate([
    //         'quantite_disponible' => 'required|integer|min:0',
    //     ]);

    //     $stock->update([
    //         'quantite_disponible' => $request->quantite_disponible,
    //     ]);

    //     return redirect()->route('admin.stocks.index')->with('success', 'Stock mis à jour.');
    // }

    public function update(Request $request, LotStockAdmin $stock)
{
    $validated = $request->validate([
        'quantite_recue' => 'required|integer|min:0',
        'quantite_disponible' => 'required|integer|min:0|max:' . $request->quantite_recue,
        'prix_unitaire' => 'required|numeric|min:0',
        'date_reception' => 'required|date',
        'date_expiration' => 'required|date|after_or_equal:date_reception',
    ]);

    $stock->update($validated);

    return redirect()->route('admin.stocks.index')->with('success', 'Lot mis à jour avec succès.');
}


    public function destroy(LotStockAdmin $stock)
    {
        $stock->delete();
        return redirect()->route('admin.stocks.index')->with('success', 'Lot supprimé.');
    }
}
