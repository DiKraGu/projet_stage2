<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuProduit extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'produit_id', 'quantite_utilisee'];

}
