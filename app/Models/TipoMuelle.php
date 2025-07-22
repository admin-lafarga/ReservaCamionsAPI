<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMuelle extends Model
{
    /** @use HasFactory<\Database\Factories\TipusMollFactory> */
    use HasFactory;

    protected $primaryKey = 'tipo_muelle_id';

    public function getRouteKeyName()
    {
        return 'tipo_muelle_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
        'materiales_acceptados',
        'estado'
    ];
}
