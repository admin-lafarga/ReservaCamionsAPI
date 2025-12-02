<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentosReserva extends Model
{
    protected $table = 'documentos_reserva';
    protected $primaryKey = 'documento_reserva_id';

    public function getRouteKeyName()
    {
        return 'documento_reserva_id';
    }

    protected $fillable = [
        'reserva_id',
        'url',
        'nombre'
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }



}
