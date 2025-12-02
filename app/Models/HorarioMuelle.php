<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioMuelle extends Model
{
    /** @use HasFactory<\Database\Factories\MollHorariFactory> */
    use HasFactory;
    
    protected $table = 'horarios_muelles';
    protected $primaryKey = 'horarios_muelle_id';

    public function getRouteKeyName()
    {
        return 'horarios_muelle_id';
    }

    protected $fillable = [
        'muelle_id',
        'dia_semana',
        'inicio',
        'fin',
    ];

    public function muelle()
    {
        return $this->belongsTo(Muelle::class, 'muelle_id');
    }
}
