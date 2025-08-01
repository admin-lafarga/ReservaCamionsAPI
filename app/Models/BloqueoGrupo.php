<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueoGrupo extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueoGrupoFactory> */
    use HasFactory;

    protected $primaryKey = 'bloqueo_grupo_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_grupo_id';
    }

    protected $fillable = [
        'tipo_proveedor_id',
        'cantidad_total',
        'cantidad_disponible',
        'fecha_desde',
        'fecha_hasta',
        'usuario_id',
        'activo'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'tipo_proveedor_id', 'tipo_proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(BloqueoGrupoDetalle::class, 'bloqueo_grupo_id');
    }
}
