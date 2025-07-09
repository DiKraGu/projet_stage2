<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotStockAdmin extends Model
{
    use HasFactory;

        protected $fillable = [
        'produit_id', 'quantite_recue', 'prix_unitaire', 'date_expiration', 'quantite_disponible', 'date_reception'
    ];

    protected $casts = [
    'date_expiration' => 'datetime',
    'date_reception' => 'datetime',
];
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function detailsLivraison()
    {
        return $this->hasMany(DetailLivraisonEtablissement::class, 'lot_stock_admin_id');
    }

        public function isExpired()
    {
        return $this->date_expiration < now()->toDateString();
    }
}
