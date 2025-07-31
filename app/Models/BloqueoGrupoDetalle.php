<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueoGrupoDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueoGrupoDetalleFactory> */
    use HasFactory;
    protected $primaryKey = 'bloqueo_grupo_detalle_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_grupo_detalle_id';
    }

    protected $fillable = [
        'bloqueo_grupo_id',
        'material_id',
        'activo'
    ];
    
    public function bloqueoGrupo()
    {
        return $this->belongsTo(BloqueoGrupo::class, 'bloqueo_grupo_id', 'bloqueo_grupo_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }
}
