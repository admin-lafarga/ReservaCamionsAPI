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
        'tipo_material1_id',
        'tipo_material2_id',
        'proveedor_id',
        'transporte_id',
        'muelle1_id',
        'muelle2_id',
        'status_id',
        'empresa_id',
        'cantidad1',
        'cantidad2',
        'pedido_LF',
        'matricula_camion',
        'inicio1',
        'fin1',
        'inicio2',
        'fin2',
        'es_aduana',
        'notas',
        'tel1',
        'duracion1',
        'duracion2'
    ];

    public function tipoCamion()
    {
        return $this->belongsTo(Tipo_Camion::class, 'tipo_camion_id');
    }

    public function tipoMaterial1()
    {
        return $this->belongsTo(Tipo_Material::class, 'tipo_material1_id');
    }

    public function tipoMaterial2()
    {
        return $this->belongsTo(Tipo_Material::class, 'tipo_material2_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function transporte()
    {
        return $this->belongsTo(Transporte::class, 'transporte_id');
    }

    public function muelle1()
    {
        return $this->belongsTo(Muelle::class, 'muelle1_id');
    }

    public function muelle2()
    {
        return $this->belongsTo(Muelle::class, 'muelle2_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentosReserva::class, 'reserva_id');
    }
}
