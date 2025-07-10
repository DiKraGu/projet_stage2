<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'etablissement_id',
        'jour_semaine',
        'semaine',
        'semaine_fin'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

public function produits()
{
    return $this->belongsToMany(Produit::class, 'menu_produit')
                ->withPivot('jour', 'type_repas', 'quantite_utilisee')
                ->withTimestamps();
}




}
