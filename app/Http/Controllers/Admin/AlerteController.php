<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\LotStockAdmin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlerteController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Générer automatiquement les alertes avant affichage
    //     $this->verifierEtGenererAlertes();

    //     $query = Alerte::with('lot.produit')->orderBy('created_at', 'desc');

    //     if ($request->filled('type')) {
    //         $query->where('type', $request->type);
    //     }

    //     if ($request->filled('statut')) {
    //         $query->where('statut', $request->statut);
    //     }

    //     $alerts = $query->paginate(10);

    //     return view('admin.alertes.index', compact('alerts'));
    // }

public function index(Request $request)
{
    // 🔄 Générer automatiquement les alertes
    $this->verifierEtGenererAlertes();

    $query = Alerte::with('lot.produit')->orderBy('created_at', 'desc');

    // 💡 Si aucun filtre n'est présent, on applique statut=active par défaut
    if (!$request->has('statut')) {
        $query->where('statut', 'active');
    } else {
        $query->where('statut', $request->statut);
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $alerts = $query->paginate(10);

    return view('admin.alertes.index', compact('alerts'));
}


    // public function ignore($id)
    // {
    //     $alerte = Alerte::findOrFail($id);
    //     $alerte->update(['statut' => 'ignoree']);

    //     return redirect()->back()->with('success', 'Alerte ignorée.');
    // }

    public function ignore($id)
{
    $alerte = Alerte::findOrFail($id);
    $alerte->update(['statut' => 'ignoree']);

    return redirect()->route('admin.alerts', ['statut' => 'active'])
        ->with('success', 'Alerte ignorée.');
}


    public function destroy($id)
    {
        Alerte::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Alerte supprimée.');
    }

    // Génère les alertes depuis les lots
    private function verifierEtGenererAlertes()
    {
        $lots = LotStockAdmin::with('produit')->get();
        $now = Carbon::now();

        foreach ($lots as $lot) {
            // Alerte périmé
            if ($lot->date_expiration < $now) {
                $this->creerAlerte($lot, 'perime');
            }
            // Alerte bientôt périmé (dans 7 jours)
            elseif ($lot->date_expiration->isBetween($now, $now->copy()->addDays(7))) {
                $this->creerAlerte($lot, 'bientot_perime');
            }
            // Alerte stock faible (ex: < 10)
            if ($lot->quantite_disponible > 0 && $lot->quantite_disponible < 10) {
                $this->creerAlerte($lot, 'stock_faible');
            }
            // Alerte épuisé
            if ($lot->quantite_disponible == 0) {
                $this->creerAlerte($lot, 'epuise');
            }
        }
    }

    // private function creerAlerte($lot, $type)
    // {
    //     // Évite de dupliquer les alertes actives
    //     Alerte::firstOrCreate([
    //         'lot_stock_admin_id' => $lot->id,
    //         'type' => $type,
    //         'statut' => 'active',
    //     ]);
    // }

    private function creerAlerte($lot, $type)
{
    // Ne pas recréer si une alerte existe déjà pour ce lot + ce type (peu importe le statut)
    $existe = Alerte::where('lot_stock_admin_id', $lot->id)
        ->where('type', $type)
        ->exists();

    if (!$existe) {
        Alerte::create([
            'lot_stock_admin_id' => $lot->id,
            'type' => $type,
            'statut' => 'active',
        ]);
    }
}

    public function massAction(Request $request)
{
    $ids = $request->selected_alerts;
    if (!$ids) {
        return back()->with('success', 'Aucune alerte sélectionnée.');
    }

    if ($request->action === 'ignore') {
        Alerte::whereIn('id', $ids)->update(['statut' => 'ignoree']);
return redirect()->route('admin.alerts', ['statut' => 'active'])->with('success', 'Alertes ignorées.');
    }

    if ($request->action === 'delete') {
        Alerte::whereIn('id', $ids)->delete();
        return redirect()->route('admin.alerts', ['statut' => 'active'])->with('success', 'Alertes supprimées.');

        // return back()->with('success', 'Alertes supprimées.');
    }

    // return back();
}

}
