<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    /** @use HasFactory<\Database\Factories\TipusMaterialFactory> */
    use HasFactory;
    protected $table = 'tipo_materiales';

    protected $primaryKey = 'tipo_material_id';

    public function getRouteKeyName()
    {
        return 'tipo_material_id';
    }

    protected $fillable = [
        'nombre',
        'tiempoD',
        'estado'
    ];
    public function reservasComoTipo1()
    {
        return $this->hasMany(Reserva::class, 'tipo_material1_id');
    }

    public function reservasComoTipo2()
    {
        return $this->hasMany(Reserva::class, 'tipo_material2_id');
    }
}
