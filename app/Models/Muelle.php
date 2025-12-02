<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muelle extends Model
{
    /** @use HasFactory<\Database\Factories\MollFactory> */
    use HasFactory;
    protected $table = 'muelles';
    protected $hidden = ['pivot'];

    protected $primaryKey = 'muelle_id';

    public function getRouteKeyName()
    {
        return 'muelle_id';
    }

    protected $fillable = [
        'empresa_lfycs_id',
        'nombre',
        'descripcion',
        'color',
        'estado',
    ];

    public function empresa_lfycs()
    {
        return $this->belongsTo(EmpresaLfycs::class, 'empresa_lfycs_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioMuelle::class, 'muelle_id');
    }

    public function reservas1()
    {
        return $this->hasMany(Reserva::class, 'muelle1_id');
    }

    public function reservas2()
    {
        return $this->hasMany(Reserva::class, 'muelle2_id');
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'material_muelles', 'muelle_id', 'material_id');
    }

    public function bloqueos()
    {
        return $this->hasMany(BloqueoMuelle::class, 'muelle_id');
    }
}
