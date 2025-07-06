<?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\Region;
// use Illuminate\Http\Request;

// class RegionController extends Controller
// {
//     public function index()
//     {
//         $regions = Region::withCount('etablissements')->get();
//         return view('admin.regions.index', compact('regions'));
//     }

//     public function create()
//     {
//         return view('admin.regions.create');
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'nom' => 'required|string|unique:regions,nom',
//         ]);

//         Region::create([
//             'nom' => $request->nom,
//         ]);

//         return redirect()->route('admin.regions.index')->with('success', 'Région ajoutée');
//     }

//     public function edit(Region $region)
//     {
//         return view('admin.regions.edit', compact('region'));
//     }

//     public function update(Request $request, Region $region)
//     {
//         $request->validate([
//             'nom' => 'required|string|unique:regions,nom,' . $region->id,
//         ]);

//         $region->update([
//             'nom' => $request->nom,
//         ]);

//         return redirect()->route('admin.regions.index')->with('success', 'Région modifiée');
//     }

//     public function destroy(Region $region)
//     {
//         $region->delete();
//         return redirect()->route('admin.regions.index')->with('success', 'Région supprimée');
//     }
// }


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        // Charger les régions avec le nombre d'établissements via les villes
    // $regions = Region::withCount(['villes', 'etablissements'])->get();
        $regions = Region::withCount('villes')->with(['villes.provinces.etablissements'])->get();

        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:regions,nom',
        ]);

        Region::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Région ajoutée');
    }
 
    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'nom' => 'required|string|unique:regions,nom,' . $region->id,
        ]);

        $region->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Région modifiée');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Région supprimée');
    }
}
