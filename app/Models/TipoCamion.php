<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCamion extends Model
{
    /** @use HasFactory<\Database\Factories\TipusCamioFactory> */
    use HasFactory;
    protected $table = 'tipo_camiones';
    protected $hidden = ['pivot'];

    protected $primaryKey = 'tipo_camion_id';

    public function getRouteKeyName()
    {
        return 'tipo_camion_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
        'tiempo_descarga_1',
        'bloqueo_muelles'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'tipo_camion_id');
    }

    public function materiales(){
        return $this->belongsToMany(Material::class, 'material_tipo_camion', 'tipo_camion_id', 'material_id');
    }
}
