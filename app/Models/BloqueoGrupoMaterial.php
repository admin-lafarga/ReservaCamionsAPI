<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueoGrupoMaterial extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueoGrupoMaterialFactory> */
    use HasFactory;

    protected $table = 'bloqueo_grupo_materiales';
    protected $primaryKey = 'bloqueo_grupo_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_grupo_id';
    }

    protected $fillable = [
        'bloqueo_grupo_id',
        'tipo_proveedor_id',
        'cantidad_total',
        'cantidad_disponible',
        'inicio',
        'fin',
    ];

    public function tipoproveedor()
    {
        return $this->belongsTo(TipoProveedor::class, 'tipo_proveedor_id', 'tipo_proveedor_id');
    }

    public function detalles()
    {
        return $this->hasMany(BloqueoGrupoMaterialDetalle::class, 'bloqueo_grupo_id');
    }
}
