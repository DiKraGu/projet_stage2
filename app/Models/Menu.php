<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['etablissement_id', 'date'];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'menu_produit')
            ->withPivot('quantite_utilisee')
            ->withTimestamps();
    }
}
