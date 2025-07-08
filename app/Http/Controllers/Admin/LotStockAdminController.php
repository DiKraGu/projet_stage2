<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LotStockAdmin;
use App\Models\Produit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginationLengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LotStockAdminController extends Controller
{


public function index(Request $request)
{
    $query = LotStockAdmin::with('produit');

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

    // ✅ Filtrage par date de réception
    if ($request->filled('date_reception')) {
        $query->whereDate('date_reception', $request->date_reception);
    }

    // ✅ Filtrage par date d'expiration
    if ($request->filled('date_expiration')) {
        $query->whereDate('date_expiration', $request->date_expiration);
    }

    // Récupération des lots
    $lotsNonPagines = $query->get();

    // Tri personnalisé (périmé -> épuisé -> actif)
    $lotsTries = $lotsNonPagines->sortBy(function ($lot) {
        if ($lot->isExpired()) return 0;
        if ($lot->quantite_disponible == 0) return 1;
        return 2;
    })->values();

    // Pagination manuelle
    $page = $request->get('page', 1);
    $perPage = 10;
    $lots = new \Illuminate\Pagination\LengthAwarePaginator(
        $lotsTries->forPage($page, $perPage),
        $lotsTries->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

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

public function alerts(Request $request)
{
    $now = now();
    $soon = $now->copy()->addDays(7);

    $lots = LotStockAdmin::with('produit')->get();

    $alerts = collect();

    // Stock faible < 5 mais > 0
    $lowStock = $lots->filter(fn($lot) => $lot->quantite_disponible < 5 && $lot->quantite_disponible > 0);
    foreach ($lowStock as $lot) {
        $alerts->push([
            'lot' => $lot,
            'type' => 'stock_faible',
            'message' => 'Stock faible',
        ]);
    }

    // Bientôt périmé (date_expiration dans 7 jours)
    $almostExpired = $lots->filter(fn($lot) => $lot->date_expiration >= $now && $lot->date_expiration <= $soon);
    foreach ($almostExpired as $lot) {
        $alerts->push([
            'lot' => $lot,
            'type' => 'bientot_perime',
            'message' => 'Bientôt périmé',
        ]);
    }

    // Périmé
    $expired = $lots->filter(fn($lot) => $lot->date_expiration < $now);
    foreach ($expired as $lot) {
        $alerts->push([
            'lot' => $lot,
            'type' => 'perime',
            'message' => 'Périmé',
        ]);
    }

    // Épuisé (quantité_disponible = 0)
    $outOfStock = $lots->filter(fn($lot) => $lot->quantite_disponible == 0);
    foreach ($outOfStock as $lot) {
        $alerts->push([
            'lot' => $lot,
            'type' => 'epuise',
            'message' => 'Épuisé',
        ]);
    }

    // Filtrage par type d'alerte si demandé
    if ($request->filled('type')) {
        $alerts = $alerts->filter(fn($alert) => $alert['type'] === $request->type);
    }

    // Trier par type puis date_expiration
    $alerts = $alerts->sortBy([
        ['type', 'asc'],
        ['lot.date_expiration', 'asc'],
    ])->values();

    // Pagination manuelle
    $perPage = 10;
    $page = $request->get('page', 1);
    $total = $alerts->count();

    // Slice la collection pour la page courante
    $alertsForPage = $alerts->slice(($page - 1) * $perPage, $perPage)->values();

    // Créer le paginator
    $paginatedAlerts = new LengthAwarePaginator(
        $alertsForPage,
        $total,
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('admin.alerts.index', ['alerts' => $paginatedAlerts]);
}

}
