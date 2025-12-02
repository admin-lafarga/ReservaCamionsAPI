<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;
    protected $table = 'estados';

    protected $primaryKey = 'estado_id';

    public function getRouteKeyName()
    {
        return 'estado_id';
    }
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'estado_id');
    }
}
