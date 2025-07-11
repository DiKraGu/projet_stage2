<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    LivraisonEtablissement,
    Etablissement,
    Menu,
    LotStockAdmin,
    DetailLivraisonEtablissement,
    MouvementStock
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LivraisonController extends Controller
{

// public function index(Request $request)
// {
//     $query = LivraisonEtablissement::with('etablissement', 'menu')->latest();

//     if ($request->filled('etablissement')) {
//         $query->whereHas('etablissement', function ($q) use ($request) {
//             $q->where('nom', 'like', '%' . $request->etablissement . '%');
//         });
//     }

//     if ($request->filled('menu_date')) {
//         $query->whereHas('menu', function ($q) use ($request) {
//             $q->whereDate('semaine', $request->menu_date);
//         });
//     }

//     if ($request->filled('livraison_date')) {
//         $query->whereDate('date_livraison', $request->livraison_date);
//     }

//     if ($request->filled('statut')) {
//         $query->where('statut', $request->statut);
//     }

//     $livraisons = $query->paginate(10);

//     return view('admin.livraisons.index', compact('livraisons'));
// }

public function index(Request $request)
{
    $query = LivraisonEtablissement::with('etablissement', 'menu')->latest();

    if ($request->filled('etablissement')) {
        $query->whereHas('etablissement', function ($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->etablissement . '%');
        });
    }

    if ($request->filled('menu_date')) {
        $date = $request->menu_date;

        $query->whereHas('menu', function ($q) use ($date) {
            $q->whereDate('semaine', '<=', $date)
              ->whereDate(DB::raw("DATE_ADD(semaine, INTERVAL 6 DAY)"), '>=', $date);
        });
    }

    if ($request->filled('livraison_date')) {
        $query->whereDate('date_livraison', $request->livraison_date);
    }

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    $livraisons = $query->paginate(10);

    return view('admin.livraisons.index', compact('livraisons'));
}


    // Formulaire création (avec filtre établissements/menus)
    public function create(Request $request)
    {
        $etablissements = Etablissement::all();

        $menus = collect();
        $produitsParMenu = collect();

        $etablissementId = $request->input('etablissement_id');
        $menuId = $request->input('menu_id');

        if ($etablissementId) {
            $menus = Menu::where('etablissement_id', $etablissementId)
                        ->orderBy('semaine', 'desc')
                        ->get();

            if ($menuId) {
                $produitsParMenu = DB::table('menu_produit')
                    ->join('produits', 'produits.id', '=', 'menu_produit.produit_id')
                    ->where('menu_produit.menu_id', $menuId)
                    ->select('produits.id', 'produits.nom', 'menu_produit.quantite_utilisee')
                    ->get();
            }
        }

        return view('admin.livraisons.create', compact('etablissements', 'menus', 'produitsParMenu'));
    }


    public function store(Request $request)
{
    $request->validate([
        'etablissement_id' => 'required|exists:etablissements,id',
        'menu_id' => 'required|exists:menus,id',
        'date_livraison' => 'nullable|date',
        'statut' => 'nullable|in:en_attente,livrée,annulée',
    ]);

    DB::beginTransaction();

    try {
        $produits = DB::table('menu_produit')
            ->where('menu_id', $request->menu_id)
            ->join('produits', 'produits.id', '=', 'menu_produit.produit_id')
            ->select('produits.id', 'produits.nom', 'menu_produit.quantite_utilisee as total')
            ->get();

        if ($produits->isEmpty()) {
            return back()->withErrors(['produits' => 'Aucun produit trouvé pour ce menu.'])->withInput();
        }

        $livraison = LivraisonEtablissement::create([
            'etablissement_id' => $request->etablissement_id,
            'menu_id' => $request->menu_id,
            'date_livraison' => $request->date_livraison,
            'statut' => $request->statut ?? 'en_attente',
        ]);

        // Gérer stock SEULEMENT si statut == livrée
        if ($livraison->statut === 'livrée') {
            foreach ($produits as $produit) {
                $quantiteDemandee = (int) $produit->total;
                $lots = LotStockAdmin::where('produit_id', $produit->id)
                    ->where('quantite_disponible', '>', 0)
                    ->whereDate('date_expiration', '>=', now())
                    ->orderBy('date_reception')
                    ->get();

                $reste = $quantiteDemandee;

                foreach ($lots as $lot) {
                    if ($reste <= 0) break;

                    $aPrendre = min($lot->quantite_disponible, $reste);
                    $lot->decrement('quantite_disponible', $aPrendre);

                    DetailLivraisonEtablissement::create([
                        'livraison_etablissement_id' => $livraison->id,
                        'produit_id' => $produit->id,
                        'lot_stock_admin_id' => $lot->id,
                        'quantite_livree' => $aPrendre,
                    ]);

                    MouvementStock::create([
                        'type' => 'sortie',
                        'produit_id' => $produit->id,
                        'lot_stock_admin_id' => $lot->id,
                        'quantite' => $aPrendre,
                        'date' => now(),
                        'origine' => 'livraison',
                    ]);

                    $reste -= $aPrendre;
                }

                if ($reste > 0) {
                    DB::rollBack();
                    return back()->withErrors(["produits.{$produit->id}.quantite" => "Stock insuffisant pour le produit : $produit->nom."])->withInput();
                }
            }
        }

        DB::commit();

        return redirect()->route('admin.livraisons.index')->with('success', 'Livraison enregistrée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e);
        return back()->withErrors(['exception' => $e->getMessage()])->withInput();
    }
}

    // Afficher une livraison en détail
    // public function show(LivraisonEtablissement $livraison)
    // {
    //     $livraison->load('etablissement', 'details.produit', 'details.lotStockAdmin');
    //     return view('admin.livraisons.show', compact('livraison'));
    // }

    // Afficher une livraison en détail avec catégorie des produits chargée
public function show(LivraisonEtablissement $livraison)
{
    $livraison->load([
        'etablissement',
        'menu',
        'details.produit.categorie',   // charger aussi la catégorie du produit
        'details.lotStockAdmin'
    ]);
    return view('admin.livraisons.show', compact('livraison'));
}

    public function annuler(LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'en_attente') {
        return back()->with('error', 'Seules les livraisons en attente peuvent être annulées.');
    }

    $livraison->update(['statut' => 'annulée']);

    return redirect()->route('admin.livraisons.index')->with('success', 'Livraison annulée avec succès.');
}


    public function edit(LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'en_attente') {
        return redirect()->route('admin.livraisons.index')->with('error', 'Seules les livraisons en attente peuvent être modifiées.');
    }

    return view('admin.livraisons.edit', compact('livraison'));
}

public function update(Request $request, LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'en_attente') {
        return redirect()->route('admin.livraisons.index')->with('error', 'Cette livraison ne peut plus être modifiée.');
    }

    $request->validate([
        'date_livraison' => 'nullable|date',
        'statut' => 'required|in:en_attente,livrée',
    ]);

    $livraison->update([
        'date_livraison' => $request->date_livraison,
        'statut' => $request->statut,
    ]);

    return redirect()->route('admin.livraisons.index')->with('success', 'Livraison modifiée avec succès.');
}


public function marquerCommeLivree(LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'en_attente') {
        return back()->with('error', 'Seules les livraisons en attente peuvent être marquées comme livrées.');
    }

    // Vérifier que les détails n'existent pas déjà
    if ($livraison->details()->exists()) {
        return back()->with('error', 'Cette livraison a déjà été traitée.');
    }

    DB::beginTransaction();
    try {
        $produits = DB::table('menu_produit')
            ->where('menu_id', $livraison->menu_id)
            ->join('produits', 'produits.id', '=', 'menu_produit.produit_id')
            ->select('produits.id', 'produits.nom', 'menu_produit.quantite_utilisee as total')
            ->get();

        foreach ($produits as $produit) {
            $quantiteDemandee = (int) $produit->total;
            $lots = LotStockAdmin::where('produit_id', $produit->id)
                ->where('quantite_disponible', '>', 0)
                ->whereDate('date_expiration', '>=', now())
                ->orderBy('date_reception')
                ->get();

            $reste = $quantiteDemandee;

            foreach ($lots as $lot) {
                if ($reste <= 0) break;

                $aPrendre = min($lot->quantite_disponible, $reste);
                $lot->decrement('quantite_disponible', $aPrendre);

                DetailLivraisonEtablissement::create([
                    'livraison_etablissement_id' => $livraison->id,
                    'produit_id' => $produit->id,
                    'lot_stock_admin_id' => $lot->id,
                    'quantite_livree' => $aPrendre,
                ]);

                MouvementStock::create([
                    'type' => 'sortie',
                    'produit_id' => $produit->id,
                    'lot_stock_admin_id' => $lot->id,
                    'quantite' => $aPrendre,
                    'date' => now(),
                    'origine' => 'livraison',
                ]);

                $reste -= $aPrendre;
            }

            if ($reste > 0) {
                DB::rollBack();
                return back()->with('error', "Stock insuffisant pour le produit : {$produit->nom}.");
            }
        }

        $livraison->update(['statut' => 'livrée']);
        DB::commit();

        return back()->with('success', 'La livraison a été marquée comme livrée.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur technique : ' . $e->getMessage());
    }
}

public function destroy(LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'annulée') {
        return back()->with('error', 'Seules les livraisons annulées peuvent être supprimées.');
    }

    $livraison->delete();

    return back()->with('success', 'Livraison supprimée avec succès.');
}


}
