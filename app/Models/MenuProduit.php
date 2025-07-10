<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuProduit extends Model
{
    use HasFactory;

    protected $table = 'menu_produit';

    protected $fillable = [
        'menu_id',
        'produit_id',
        'jour',
        'type_repas',
        'quantite_utilisee',
    ];


}
