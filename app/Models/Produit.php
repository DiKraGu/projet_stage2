<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = ['nom', 'description', 'fournisseur_id'];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lotsStockAdmin()
    {
        return $this->hasMany(LotStockAdmin::class);
    }
}
