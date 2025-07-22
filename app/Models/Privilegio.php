<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    /** @use HasFactory<\Database\Factories\PrivilegiFactory> */
    use HasFactory;

    protected $primaryKey = 'privilegio_id';

    public function getRouteKeyName()
    {
        return 'privilegio_id';
    }

    protected $fillable = [
        'id_tipo_usuario',
        'view',
        'canEdit',
        'canEditAll',
        'canAdd'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_tipo_usuario');
    }
}
