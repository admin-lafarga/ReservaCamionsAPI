<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloqueo_Kg_Material extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueigKGMaterialFactory> */
    use HasFactory;
    protected $table = 'bloqueo_kg_materiales';

    protected $primaryKey = 'bloqueo_kg_material_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_kg_material_id';
    }

    protected $fillable = [
        'material_id',
        'usaurio_id',
        'tipo_proveedor_id',
        'cantidad',
        'inicio',
        'fin'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function tipoProveedor()
    {
        return $this->belongsTo(Tipo_Proveedor::class, 'tipo_proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usaurio_id');
    }

}
