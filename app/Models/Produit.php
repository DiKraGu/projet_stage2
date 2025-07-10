<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'categorie_id', 'fournisseur_id'];

    public function categorie()
{
    return $this->belongsTo(Categorie::class);
}
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lotsStockAdmin()
    {
        return $this->hasMany(LotStockAdmin::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_produit')
                    ->withPivot('quantite_utilisee')
                    ->withTimestamps();
    }

        public function livraisons()
    {
        return $this->hasMany(DetailLivraisonEtablissement::class);
    }

}
