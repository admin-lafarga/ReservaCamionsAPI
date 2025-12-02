<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueoGrupoMaterialDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueoGrupoMaterialDetalleFactory> */
    use HasFactory;
    protected $primaryKey = 'bloqueo_grupo_detalle_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_grupo_detalle_id';
    }

    protected $fillable = [
        'bloqueo_grupo_id',
        'material_id',
    ];
    
    public function BloqueoGrupoMaterial()
    {
        return $this->belongsTo(BloqueoGrupoMaterial::class, 'bloqueo_grupo_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
