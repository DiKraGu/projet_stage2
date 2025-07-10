<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivraisonEtablissement extends Model
{
    use HasFactory;

    protected $table = 'livraisons_etablissement';

     protected $fillable = [
        'etablissement_id',
        'menu_id',
        'date_livraison',
        'statut',
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function details()
    {
        return $this->hasMany(DetailLivraisonEtablissement::class, 'livraison_etablissement_id');
    }
}
