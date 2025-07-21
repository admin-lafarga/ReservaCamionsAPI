<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;
    protected $table = 'status';

    protected $primaryKey = 'status_id';

    public function getRouteKeyName()
    {
        return 'status_id';
    }
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'status_id');
    }
}
