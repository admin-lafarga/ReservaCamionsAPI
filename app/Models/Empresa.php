<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /** @use HasFactory<\Database\Factories\EmpresaFactory> */
    use HasFactory;

    protected $primaryKey = 'empresa_id';

    public function getRouteKeyName()
    {
        return 'empresa_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function muelles()
    {
        return $this->hasMany(Muelle::class, 'empresa_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'empresa_id');
    }
}
