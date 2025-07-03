<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotStockAdmin extends Model
{
    protected $fillable = [
        'produit_id', 'quantite_recue', 'prix_unitaire', 'date_expiration', 'quantite_disponible', 'date_reception'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function detailsLivraison()
    {
        return $this->hasMany(DetailLivraisonEtablissement::class, 'lot_stock_admin_id');
    }
}
