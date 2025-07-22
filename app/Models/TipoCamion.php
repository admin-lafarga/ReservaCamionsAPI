<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCamion extends Model
{
    /** @use HasFactory<\Database\Factories\TipusCamioFactory> */
    use HasFactory;
    protected $table = 'tipo_camiones';

    protected $primaryKey = 'tipo_camion_id';

    public function getRouteKeyName()
    {
        return 'tipo_camion_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
        'materiales',
        'timpo_descarga_a',
        'timpo_descarga_b',
        'muelles_permitidos',
        'estado',
        'bloqueo_muelles'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'tipo_camion_id');
    }
}
