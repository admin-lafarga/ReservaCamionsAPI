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
        'nombre_material',
        'estado',
        'camiones_permitidos',
        'muelles_permitidos',
        'max_concurrencia',
    ];

    public function bloqueosCamionMaterial()
    {
        return $this->hasMany(Bloqueo_Camion_Material::class, 'material_id');
    }

    public function bloqueosKgMaterial()
    {
        return $this->hasMany(Bloqueo_Kg_Material::class, 'material_id');
    }
}
