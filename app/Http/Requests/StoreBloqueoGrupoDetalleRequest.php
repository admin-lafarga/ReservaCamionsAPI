<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloqueoGrupoDetalleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_proveedor_id' => 'required|exists:proveedores,tipo_proveedor_id',
            'usuario_id'        => 'required|exists:users,id',
            'cantidad_total'    => 'required|integer|min:1',
            'cantidad_disponible' => 'required|integer|min:0|lte:cantidad_total',
            'fecha_desde'       => 'required|date',
            'fecha_hasta'       => 'required|date|after_or_equal:fecha_desde',
            'activo'            => 'boolean',
        ];
    }
}
