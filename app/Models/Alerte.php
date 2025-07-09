<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = ['lot_stock_admin_id', 'type', 'statut'];

    public function lot()
    {
        return $this->belongsTo(LotStockAdmin::class, 'lot_stock_admin_id');
    }
}
