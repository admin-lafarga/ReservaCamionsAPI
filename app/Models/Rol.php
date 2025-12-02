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
    protected $hidden = ['pivot'];

    public function getRouteKeyName()
    {
        return 'rol_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'usuario_roles', 'rol_id', 'usuario_id');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permisos', 'rol_id', 'permiso_id');
    }
}
