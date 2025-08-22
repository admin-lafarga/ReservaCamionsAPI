<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muelle extends Model
{
    /** @use HasFactory<\Database\Factories\MollFactory> */
    use HasFactory;
    protected $table = 'muelles';

    protected $primaryKey = 'muelle_id';

    public function getRouteKeyName()
    {
        return 'muelle_id';
    }

    protected $fillable = [
        'empresa_id',
        'nombre_muelle',
        'descripcion',
        'numero',
        'zona',
        'abierto_festivos',
        'color',
        'estado',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario_Muelle::class, 'muelle_id');
    }

    public function reservas1()
    {
        return $this->hasMany(Reserva::class, 'muelle1_id');
    }

    public function reservas2()
    {
        return $this->hasMany(Reserva::class, 'muelle2_id');
    }
}
