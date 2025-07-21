<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horari extends Model
{
    /** @use HasFactory<\Database\Factories\HorariFactory> */
    use HasFactory;

    protected $fillable = [
        'hora_Inici',
        'hora_fi',
        'txt',    
    ];
}
