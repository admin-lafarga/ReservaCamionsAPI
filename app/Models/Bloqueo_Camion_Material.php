<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloqueo_Camion_Material extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueigCamioMaterialFactory> */
    use HasFactory;
    protected $table = 'bloqueo_camion_materiales';

    protected $primaryKey = 'bloqueo_camion_material_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_camion_material_id';
    }

    protected $fillable = [
        'proveedor_id',
        'material_id',
        'usuario_id',
        'inicio',
        'fin',
        'cantidad'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

}
