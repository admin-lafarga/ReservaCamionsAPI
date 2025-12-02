<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaLfycs extends Model
{
    /** @use HasFactory<\Database\Factories\EmpresaFactory> */
    use HasFactory;

    protected $table = 'empresas_lfycs';
    protected $primaryKey = 'empresa_lfycs_id';

    public function getRouteKeyName()
    {
        return 'empresa_lfycs_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function muelles()
    {
        return $this->hasMany(Muelle::class, 'empresa_lfycs_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'empresa_lfycs_id');
    }
}
