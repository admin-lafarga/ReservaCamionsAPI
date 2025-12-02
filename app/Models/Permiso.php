<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    
    protected $primaryKey = 'permiso_id';

    protected $hidden = ['pivot'];

    public function getRouteKeyName()
    {
        return 'permiso_id';
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_permiso', 'permiso_id', 'rol_id');
    }

}
