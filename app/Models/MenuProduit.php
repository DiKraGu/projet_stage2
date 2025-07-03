<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MenuProduit extends Pivot
{
    protected $fillable = ['menu_id', 'produit_id', 'quantite_utilisee'];
}
