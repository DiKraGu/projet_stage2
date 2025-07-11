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
    // Liste des livraisons
    public function index()
    {
        $livraisons = LivraisonEtablissement::with('etablissement', 'menu')
            ->latest()
            ->paginate(10);

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

    // Stocker une nouvelle livraison
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'etablissement_id' => 'required|exists:etablissements,id',
    //         'menu_id' => 'required|exists:menus,id',
    //         'date_livraison' => 'nullable|date',
    //         'statut' => 'nullable|in:en_attente,livrée,annulée',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Récupère les produits liés au menu
    //         $produits = DB::table('menu_produit')
    //             ->where('menu_id', $request->menu_id)
    //             ->join('produits', 'produits.id', '=', 'menu_produit.produit_id')
    //             ->select('produits.id', 'produits.nom', 'menu_produit.quantite_utilisee as total')
    //             ->get();

    //         if ($produits->isEmpty()) {
    //             dd('Aucun produit trouvé pour ce menu.');
    //             return back()->withErrors(['produits' => 'Aucun produit trouvé pour ce menu.'])->withInput();
    //         }

    //         // Crée la livraison
    //         $livraison = LivraisonEtablissement::create([
    //             'etablissement_id' => $request->etablissement_id,
    //             'menu_id' => $request->menu_id,
    //             'date_livraison' => $request->date_livraison,
    //             'statut' => $request->statut ?? 'en_attente',
    //         ]);

    //         // Gestion stock et lots
    //         foreach ($produits as $produit) {
    //             $quantiteDemandee = (int) $produit->total;
    //             $lots = LotStockAdmin::where('produit_id', $produit->id)
    //                 ->where('quantite_disponible', '>', 0)
    //                 ->whereDate('date_expiration', '>=', now())
    //                 ->orderBy('date_reception')
    //                 ->get();

    //             $reste = $quantiteDemandee;

    //             foreach ($lots as $lot) {
    //                 if ($reste <= 0) break;

    //                 $aPrendre = min($lot->quantite_disponible, $reste);
    //                 $lot->decrement('quantite_disponible', $aPrendre);

    //                 DetailLivraisonEtablissement::create([
    //                     'livraison_etablissement_id' => $livraison->id,
    //                     'produit_id' => $produit->id,
    //                     'lot_stock_admin_id' => $lot->id,
    //                     'quantite_livree' => $aPrendre,
    //                 ]);

    //                 MouvementStock::create([
    //                     'type' => 'sortie',
    //                     'produit_id' => $produit->id,
    //                     'lot_stock_admin_id' => $lot->id,
    //                     'quantite' => $aPrendre,
    //                     'date' => now(),
    //                     'origine' => 'livraison',
    //                 ]);

    //                 $reste -= $aPrendre;
    //             }

    //             if ($reste > 0) {
    //                 DB::rollBack();
    //                 dd("Stock insuffisant pour le produit ID {$produit->id} - Reste: $reste");
    //                 return back()->withErrors(["produits.{$produit->id}.quantite" => "Stock insuffisant pour le produit : $produit->nom."])->withInput();
    //             }
    //         }




    //         DB::commit();

    //         return redirect()->route('admin.livraisons.index')->with('success', 'Livraison enregistrée avec succès.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error($e);
    //         // return back()->with('error', 'Erreur lors de la création de la livraison.')->withInput();
    //         return back()->withErrors(['exception' => $e->getMessage()])->withInput();

    //     }
    // }

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
    public function show(LivraisonEtablissement $livraison)
    {
        $livraison->load('etablissement', 'details.produit', 'details.lotStockAdmin');
        return view('admin.livraisons.show', compact('livraison'));
    }

    // Annuler une livraison (changer le statut)
    public function annuler(LivraisonEtablissement $livraison)
    {
        if ($livraison->statut !== 'annulée') {
            $livraison->update(['statut' => 'annulée']);
            return redirect()->route('admin.livraisons.index')->with('success', 'Livraison annulée avec succès.');
        }
        return redirect()->route('admin.livraisons.index')->with('error', 'La livraison est déjà annulée.');
    }

    // Interdire édition
    public function edit()
    {
        return redirect()->route('admin.livraisons.index')->with('error', 'Modification non autorisée.');
    }

    public function update()
    {
        return back()->with('error', 'Modification désactivée.');
    }

    public function marquerCommeLivree(LivraisonEtablissement $livraison)
{
    if ($livraison->statut !== 'en_attente') {
        return back()->with('error', 'Cette livraison ne peut pas être modifiée.');
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
                return back()->withErrors(["produits.{$produit->id}.quantite" => "Stock insuffisant pour le produit : $produit->nom."]);
            }
        }

        $livraison->update(['statut' => 'livrée']);

        DB::commit();
        return redirect()->route('admin.livraisons.show', $livraison->id)->with('success', 'Livraison marquée comme livrée avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e);
        return back()->withErrors(['exception' => $e->getMessage()]);
    }
}

}
