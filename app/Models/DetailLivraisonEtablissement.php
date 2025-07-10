<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLivraisonEtablissement extends Model
{
    use HasFactory;

    protected $table = 'detail_livraison_etablissement';

        protected $fillable = [
        'livraison_etablissement_id', 'produit_id', 'lot_stock_admin_id', 'quantite_livree'
    ];

    public function livraison()
    {
        return $this->belongsTo(LivraisonEtablissement::class, 'livraison_etablissement_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lotStockAdmin()
    {
        return $this->belongsTo(LotStockAdmin::class, 'lot_stock_admin_id');
    }

}
