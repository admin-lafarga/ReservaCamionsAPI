<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Entidad extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'entidades';
    protected $primaryKey = 'entidad_id';

    public function getRouteKeyName()
    {
        return 'entidad_id';
    }

    protected $fillable = [
        'nombre',
        'abreviatura',
        'nif',
        'pin',
        'nombre_contacto',
        'email',
        'email_notificaciones',
        'telefono1',
        'telefono2',
        'alerta',
        'idioma',
    ];

    protected $hidden = [
        'pin',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function proveedor()
    {
        return $this->hasMany(Proveedor::class, 'entidad_id');
    }

    public function Carrier()
    {
        return $this->hasMany(Transportista::class, 'entidad_id');
    }
}
