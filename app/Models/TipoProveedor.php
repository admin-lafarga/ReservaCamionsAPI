<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProveedor extends Model
{
    /** @use HasFactory<\Database\Factories\TipusProveidorFactory> */
    use HasFactory;
    
    protected $table = 'tipo_proveedores';

    protected $primaryKey = 'tipo_proveedor_id';

    public function getRouteKeyName()
    {
        return 'tipo_proveedor_id';
    }

    protected $fillable = [
        'nombre',
    ];

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class, 'tipo_proveedor_id');
    }

}
