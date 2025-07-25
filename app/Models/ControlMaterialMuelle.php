<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlMaterialMuelle extends Model
{
    /** @use HasFactory<\Database\Factories\ControlMaterialMuelleFactory> */
    use HasFactory;

    protected $primaryKey = 'control_material_muelle_id';

    public function getRouteKeyName()
    {
        return 'control_material_muelle_id';
    }

    protected $fillable = [
        'material_id',
        'tipo_camion_id',
        'muelle_id'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function tipoCamion()
    {
        return $this->belongsTo(TipoCamion::class, 'tipo_camion_id', 'tipo_camion_id');
    }

    public function muelle()
    {
        return $this->belongsTo(Muelle::class, 'muelle_id', 'muelle_id');
    }
}
