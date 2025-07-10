<?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\Menu;
// use App\Models\Produit;
// use App\Models\Categorie;
// use App\Models\Etablissement;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;

// class MenuController extends Controller
// {
//     // public function index()
//     // {
//     //     $menus = Menu::with('etablissement')->latest('semaine')->paginate(10);
//     //     return view('admin.menus.index', compact('menus'));
//     // }

// public function index()
// {
//     $menus = Menu::with(['etablissement.province.ville'])->latest('semaine')->paginate(10);
//     return view('admin.menus.index', compact('menus'));
// }

//     public function create()
//     {
//         $etablissements = Etablissement::all();
//         $categories = Categorie::all();
//         $produits = Produit::with('categorie')->get();

//         return view('admin.menus.create', compact('etablissements', 'categories', 'produits'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'etablissement_id' => 'required|exists:etablissements,id',
//             'semaine' => 'required|date',
//             'semaine_fin' => 'required|date|after_or_equal:semaine',
//             'menus' => 'required|array',
//         ]);

//         $menu = Menu::create([
//             'etablissement_id' => $request->etablissement_id,
//             'semaine' => $request->semaine,
//             'semaine_fin' => $request->semaine_fin,
//         ]);

//         $pivotData = [];
//         foreach ($request->menus as $jour => $repasData) {
//             foreach ($repasData as $type_repas => $produits) {
//                 foreach ($produits as $produitId => $selected) {
//                     $quantite = $request->input("quantites.$jour.$type_repas.$produitId");
//                     if ($selected && $quantite > 0) {
//                         $pivotData[] = [
//                             'menu_id' => $menu->id,
//                             'produit_id' => $produitId,
//                             'jour' => $jour,
//                             'type_repas' => $type_repas,
//                             'quantite_utilisee' => $quantite,
//                             'created_at' => now(),
//                             'updated_at' => now(),
//                         ];
//                     }
//                 }
//             }
//         }

//         DB::table('menu_produit')->insert($pivotData);

//         return redirect()->route('admin.menus.index')->with('success', 'Menu de la semaine créé avec succès.');
//     }

//     public function edit(Menu $menu)
//     {
//         $etablissements = Etablissement::all();
//         $categories = Categorie::all();
//         $produits = Produit::with('categorie')->get();

//         $selected = [];
//         foreach ($menu->produits as $produit) {
//             $pivot = $produit->pivot;
//             $selected[$pivot->jour][$pivot->type_repas][$produit->id] = [
//                 'quantite' => $pivot->quantite_utilisee
//             ];
//         }

//         return view('admin.menus.edit', compact('menu', 'etablissements', 'categories', 'produits', 'selected'));
//     }

//     public function update(Request $request, Menu $menu)
//     {
//         $request->validate([
//             'etablissement_id' => 'required|exists:etablissements,id',
//             'semaine' => 'required|date',
//             'semaine_fin' => 'required|date|after_or_equal:semaine',
//             'menus' => 'required|array',
//         ]);

//         $menu->update([
//             'etablissement_id' => $request->etablissement_id,
//             'semaine' => $request->semaine,
//             'semaine_fin' => $request->semaine_fin,
//         ]);

//         DB::table('menu_produit')->where('menu_id', $menu->id)->delete();

//         $pivotData = [];
//         foreach ($request->menus as $jour => $repasData) {
//             foreach ($repasData as $type_repas => $produits) {
//                 foreach ($produits as $produitId => $selected) {
//                     $quantite = $request->input("quantites.$jour.$type_repas.$produitId");
//                     if ($selected && $quantite > 0) {
//                         $pivotData[] = [
//                             'menu_id' => $menu->id,
//                             'produit_id' => $produitId,
//                             'jour' => $jour,
//                             'type_repas' => $type_repas,
//                             'quantite_utilisee' => $quantite,
//                             'created_at' => now(),
//                             'updated_at' => now(),
//                         ];
//                     }
//                 }
//             }
//         }

//         DB::table('menu_produit')->insert($pivotData);

//         return redirect()->route('admin.menus.index')->with('success', 'Menu mis à jour avec succès.');
//     }

//     public function destroy(Menu $menu)
//     {
//         $menu->delete();
//         return redirect()->route('admin.menus.index')->with('success', 'Menu supprimé avec succès.');
//     }

//     public function pdf(Menu $menu)
//     {
//         $etablissement = $menu->etablissement;
//         $produits = $menu->produits()->get()->groupBy('pivot.jour')->map(function ($groupJour) {
//             return $groupJour->groupBy('pivot.type_repas');
//         });

//         $pdf = PDF::loadView('admin.menus.pdf', compact('menu', 'etablissement', 'produits'));
//         return $pdf->download('menu_semaine_' . $menu->semaine . '.pdf');
//     }
// }


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['etablissement.province.ville'])->latest('semaine')->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $etablissements = Etablissement::all();
        $categories = Categorie::all();
        $produits = Produit::with('categorie')->get();

        return view('admin.menus.create', compact('etablissements', 'categories', 'produits'));
    }

public function store(Request $request)
{
    $request->validate([
        'etablissement_id' => 'required|exists:etablissements,id',
        'semaine' => 'required|date',
        'semaine_fin' => 'required|date|after_or_equal:semaine',
        'menus' => 'nullable|array',
        'quantites' => 'nullable|array',
    ]);

    // Vérifier si un menu pour cette semaine et établissement existe déjà
    $exists = Menu::where('etablissement_id', $request->etablissement_id)
        ->where('semaine', $request->semaine)
        ->exists();

    if ($exists) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['semaine' => 'Un menu pour cette semaine existe déjà pour cet établissement.','semaine_fin' => 'Un menu pour cette semaine existe déjà pour cet établissement.',
]);
    }

    // Créer le menu
    $menu = Menu::create([
        'etablissement_id' => $request->etablissement_id,
        'semaine' => $request->semaine,
        'semaine_fin' => $request->semaine_fin,
    ]);

    $pivotData = [];

    if ($request->filled('menus')) {
        foreach ($request->menus as $jour => $repasData) {
            foreach ($repasData as $type_repas => $produits) {
                foreach ($produits as $produitId => $selected) {
                    if ($selected) {
                        $quantite = $request->input("quantites.$jour.$type_repas.$produitId");

                        // Si quantité est null ou vide, on met 0
                        if (is_null($quantite) || $quantite === '') {
                            $quantite = 0;
                        }

                        $pivotData[] = [
                            'menu_id' => $menu->id,
                            'produit_id' => $produitId,
                            'jour' => $jour,
                            'type_repas' => $type_repas,
                            'quantite_utilisee' => $quantite,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        if (!empty($pivotData)) {
            DB::table('menu_produit')->insert($pivotData);
        }
    }

    return redirect()->route('admin.menus.index')->with('success', 'Menu de la semaine créé avec succès.');
}


    public function edit(Menu $menu)
    {
        $etablissements = Etablissement::all();
        $categories = Categorie::all();
        $produits = Produit::with('categorie')->get();

        $selected = [];
        foreach ($menu->produits as $produit) {
            $pivot = $produit->pivot;
            $selected[$pivot->jour][$pivot->type_repas][$produit->id] = [
                'quantite' => $pivot->quantite_utilisee
            ];
        }

        return view('admin.menus.edit', compact('menu', 'etablissements', 'categories', 'produits', 'selected'));
    }

    // public function update(Request $request, Menu $menu)
    // {
    //     // $request->validate([
    //     //     'etablissement_id' => 'required|exists:etablissements,id',
    //     //     'semaine' => 'required|date',
    //     //     'semaine_fin' => 'required|date|after_or_equal:semaine',
    //     //     'menus' => 'nullable|array',
    //     // ]);

    //     $request->validate([
    //         'etablissement_id' => 'required|exists:etablissements,id',
    //         'semaine' => 'required|date',
    //         'semaine_fin' => 'required|date|after_or_equal:semaine',
    //         'menus' => 'nullable|array',
    //         'quantites' => 'nullable|array',
    //     ]);
    //     $menu->update([
    //         'etablissement_id' => $request->etablissement_id,
    //         'semaine' => $request->semaine,
    //         'semaine_fin' => $request->semaine_fin,
    //     ]);

    //     DB::table('menu_produit')->where('menu_id', $menu->id)->delete();

    //     if ($request->filled('menus')) {
    //         $pivotData = [];

    //         foreach ($request->menus as $jour => $repasData) {
    //             foreach ($repasData as $type_repas => $produits) {
    //                 foreach ($produits as $produitId => $selected) {
    //                     $quantite = $request->input("quantites.$jour.$type_repas.$produitId");
    //                     if ($selected && $quantite > 0) {
    //                         $pivotData[] = [
    //                             'menu_id' => $menu->id,
    //                             'produit_id' => $produitId,
    //                             'jour' => $jour,
    //                             'type_repas' => $type_repas,
    //                             'quantite_utilisee' => $quantite,
    //                             'created_at' => now(),
    //                             'updated_at' => now(),
    //                         ];
    //                     }
    //                 }
    //             }
    //         }

    //         if (!empty($pivotData)) {
    //             DB::table('menu_produit')->insert($pivotData);
    //         }
    //     }

    //     return redirect()->route('admin.menus.index')->with('success', 'Menu mis à jour avec succès.');
    // }

    public function update(Request $request, Menu $menu)
{
    $request->validate([
        'etablissement_id' => 'required|exists:etablissements,id',
        'semaine' => 'required|date',
        'semaine_fin' => 'required|date|after_or_equal:semaine',
        'menus' => 'nullable|array',
        'quantites' => 'nullable|array',
    ]);

    $menu->update([
        'etablissement_id' => $request->etablissement_id,
        'semaine' => $request->semaine,
        'semaine_fin' => $request->semaine_fin,
    ]);

    DB::table('menu_produit')->where('menu_id', $menu->id)->delete();

    if ($request->filled('menus')) {
        $pivotData = [];

        foreach ($request->menus as $jour => $repasData) {
            foreach ($repasData as $type_repas => $produits) {
                foreach ($produits as $produitId => $selected) {
                    if ($selected) {
                        $quantite = $request->input("quantites.$jour.$type_repas.$produitId");

                        if (is_null($quantite) || $quantite === '') {
                            $quantite = 0;
                        }

                        $pivotData[] = [
                            'menu_id' => $menu->id,
                            'produit_id' => $produitId,
                            'jour' => $jour,
                            'type_repas' => $type_repas,
                            'quantite_utilisee' => $quantite,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        if (!empty($pivotData)) {
            DB::table('menu_produit')->insert($pivotData);
        }
    }

    return redirect()->route('admin.menus.index')->with('success', 'Menu mis à jour avec succès.');
}

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu supprimé avec succès.');
    }

    public function pdf(Menu $menu)
    {
        $etablissement = $menu->etablissement;
        $produits = $menu->produits()->get()->groupBy('pivot.jour')->map(function ($groupJour) {
            return $groupJour->groupBy('pivot.type_repas');
        });

        $pdf = PDF::loadView('admin.menus.pdf', compact('menu', 'etablissement', 'produits'));
        return $pdf->download('menu_semaine_' . $menu->semaine . '.pdf');
    }
}
