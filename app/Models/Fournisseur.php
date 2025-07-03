<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = ['nom', 'contact'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
