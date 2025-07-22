<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    /** @use HasFactory<\Database\Factories\TipusUsuariFactory> */
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'rol_id';

    public function getRouteKeyName()
    {
        return 'rol_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'rol_id');
    }

}
