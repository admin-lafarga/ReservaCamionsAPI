<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;
    protected $table = 'materiales';

    protected $primaryKey = 'material_id';

    public function getRouteKeyName()
    {
        return 'material_id';
    }

    protected $fillable = [
        'codigo_sap',
        'nombre',
        '',
    ];


    public function reservas1()
    {
        return $this->hasMany(Reserva::class, 'material1_id');
    }

    public function reservas2()
    {
        return $this->hasMany(Reserva::class, 'material2_id');
    }

    public function tipo_camiones()
    {
        return $this->belongsToMany(TipoCamion::class, 'material_tipo_camiones', 'material_id', 'tipo_camion_id');
    }

    public function muelles()
    {
        return $this->belongsToMany(Muelle::class, 'material_muelles', 'material_id', 'muelle_id');
    }

    public function bloqueoMateriales()
    {
        return $this->hasMany(BloqueoGrupoMaterialDetalle::class, 'material_id');
    }
}
