<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';


use App\Http\Controllers\Admin\EtablissementController;
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\LivraisonController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LotStockAdminController;
use App\Http\Controllers\Admin\VilleController;
use App\Http\Controllers\Admin\ProvinceController;

// Routes publiques (auth admin)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
});

// Routes protégées par auth.admin middleware
Route::middleware(['auth.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('regions', RegionController::class);
    Route::resource('villes', VilleController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::resource('etablissements', EtablissementController::class);
    Route::resource('fournisseurs', FournisseurController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('produits', ProduitController::class);
    Route::resource('stocks', LotStockAdminController::class);
    // Route::resource('stocks', StockController::class);
    Route::resource('livraisons', LivraisonController::class);
    Route::resource('menus', MenuController::class);

});


