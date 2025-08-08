<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    /** @use HasFactory<\Database\Factories\TransportFactory> */
    use HasFactory;

    protected $primaryKey = 'transporte_id';

    public function getRouteKeyName()
    {
        return 'transporte_id';
    }

    protected $fillable = [
        'proveedor_id',
        'nombre',
        'abreviatura',
        'NIF',
        'PIN',
        'nombre_contacto',
        'email',
        'tel1',
        'tel2',
        'alert',
        'estado',
        'puede_gestionar'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'transporte_id');
    }
}
