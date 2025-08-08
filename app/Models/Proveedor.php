<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'codigo_sap',
        'nombre',
        'abreviatura',
        'NIF',
        'PIN',
        'nombre_contacto',
        'email',
        'notificaciones_email',
        'tel1',
        'tel2',
        'alerta',
        'estado',
        'tipo_proveedor_id'
    ];

    public function tipoProveedor()
    {
        return $this->belongsTo(TipoProveedor::class, 'tipo_proveedor_id');
    }

    // public function bloqueosCamionMaterial()
    // {
    //     return $this->hasMany(BloqueoCamionMaterial::class, 'proveedor_id');
    // }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'proveedor_id');
    }

    public function transportes()
    {
        return $this->hasMany(Transporte::class, 'proveedor_id');
    }

}
