<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'region_id'];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function etablissements() {
        return $this->hasMany(Etablissement::class);
    }
}
