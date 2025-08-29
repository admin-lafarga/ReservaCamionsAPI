<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restriccion extends Model
{
    /** @use HasFactory<\Database\Factories\RestriccioFactory> */
    use HasFactory;
    protected $table = 'restricciones';

    protected $primaryKey = 'restriccion_id';

    public function getRouteKeyName()
    {
        return 'restriccion_id';
    }

    protected $fillable = [
        'muelle_id',
        'muelle_restringido_id'
    ];

    public function muelle1()
    {
        return $this->belongsTo(Muellse::class, 'muelle_id');
    }

    public function muelle2()
    {
        return $this->belongsTo(Muelle::class, 'muelle_restringido_id');
    }

}
