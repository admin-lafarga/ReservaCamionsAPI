<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;


class BloqueoMuelle extends Model
{
    use HasFactory;
    protected $primaryKey = 'bloqueo_muelle_id';

    public function getRouteKeyName()
    {
        return 'bloqueo_muelle_id';
    }

    protected $fillable = [
        'muelle_id',
        'asunto',
        'inicio',
        'fin',
    ];

    public function muelle()
    {
        return $this->belongsTo(Muelle::class, 'muelle_id', 'muelle_id');
    }


}
