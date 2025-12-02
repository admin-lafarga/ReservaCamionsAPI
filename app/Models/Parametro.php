<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametros';
    protected $primaryKey = 'clave';
    public $incrementing = false;
    protected $keyType = 'string';


    public function getRouteKeyName()
    {
        return 'clave';
    }

    protected $fillable = [
        'clave',
        'valor',
    ];
}
