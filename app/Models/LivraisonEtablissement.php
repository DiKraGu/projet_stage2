<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivraisonEtablissement extends Model
{
    use HasFactory;

        protected $fillable = ['etablissement_id', 'semaine', 'date_livraison'];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function detailsLivraison()
    {
        return $this->hasMany(DetailLivraisonEtablissement::class);
    }
}
