<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveidorFactory> */
    use HasFactory;

    protected $table = 'proveedores';
    
    protected $primaryKey = 'proveedor_id';

    public function getRouteKeyName()
    {
        return 'proveedor_id';
    }

    protected $fillable = [
        'email_notificaciones',
        'tipo_proveedor_id',
        'entidad_id',
        'email',
        'contraseña',
        'codigo_sap'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }

    public function tipoProveedor()
    {
        return $this->belongsTo(TipoProveedor::class, 'tipo_proveedor_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'proveedor_id');
    }

    public function Carriers()
    {
        return $this->hasMany(Transportista::class, 'proveedor_id');
    }

}
