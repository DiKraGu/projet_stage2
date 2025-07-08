<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'produit_id',
        'lot_stock_admin_id',
        'quantite',
        'date',
        'origine',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lot()
    {
        return $this->belongsTo(LotStockAdmin::class, 'lot_stock_admin_id');
    }
}
