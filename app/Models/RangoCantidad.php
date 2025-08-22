<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RangoCantidad extends Model
{
    use HasFactory;

    protected $table = 'rango_cantidades';

    protected $primaryKey = 'rango_cantidad_id';

    public function getRouteKeyName()
    {
        return 'rango_cantidad_id';
    }

    protected $fillable = [
        'min_kg',
        'max_kg'
    ];
}
