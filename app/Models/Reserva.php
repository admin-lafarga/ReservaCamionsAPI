<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    /** @use HasFactory<\Database\Factories\ReservaFactory> */
    use HasFactory;

    protected $primaryKey = 'reserva_id';

    public function getRouteKeyName()
    {
        return 'reserva_id';
    }

    protected $fillable = [
        'tipo_camion_id',
        'material1_id',
        'material2_id',
        'proveedor_id',
        'transportista_id',
        'muelle_id',
        'estado_id',
        'empresa_lfycs_id',
        'cantidad1',
        'cantidad2',
        'pedido1',
        'pedido2',
        'matricula_camion',
        'inicio',
        'fin',
        'duracion',
        'aduana',
        'notas',
        'telefono',
        'es_replanificada',
    ];

    public function tipoCamion()
    {
        return $this->belongsTo(TipoCamion::class, 'tipo_camion_id');
    }

    public function material1()
    {
        return $this->belongsTo(Material::class, 'material1_id');
    }

    public function material2()
    {
        return $this->belongsTo(Material::class, 'material2_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function transportista()
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    public function muelle()
    {
        return $this->belongsTo(Muelle::class, 'muelle_id');
    }


    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }


    public function documentos()
    {
        return $this->hasMany(DocumentosReserva::class, 'reserva_id');
    }

    public function empresa_lfycs()
    {
        return $this->belongsTo(EmpresaLfycs::class, 'empresa_lfycs_id');
    }
}
