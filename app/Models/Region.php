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

    public function etablissements()
    {
        return $this->hasMany(Etablissement::class);
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
