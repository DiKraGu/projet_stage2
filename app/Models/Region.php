<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function villes() {
        return $this->hasMany(Ville::class);
    }

    public function etablissements()
{
    return $this->hasManyThrough(
        \App\Models\Etablissement::class,
        \App\Models\Ville::class
    );
}

    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
