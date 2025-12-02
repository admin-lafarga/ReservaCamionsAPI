<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Transportista extends Model
{
    /** @use HasFactory<\Database\Factories\TransportFactory> */
    use HasFactory;

    protected $primaryKey = 'transportista_id';

    public function getRouteKeyName()
    {
        return 'transportista_id';
    }

    protected $fillable = [
        'puede_gestionar',
        'entidad_id',
        'email',
        'contraseña'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'transportista_id');
    }
}
