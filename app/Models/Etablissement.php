<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonEtablissement::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
