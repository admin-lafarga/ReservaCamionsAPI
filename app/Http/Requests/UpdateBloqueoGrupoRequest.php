<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBloqueoGrupoMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cantidad_total' => 'required|numeric|min:1',
            'cantidad_disponible' => 'required|numeric|min:0|lte:cantidad_total',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'tipo_proveedor_id' => 'required|exists:tipo_proveedores,tipo_proveedor_id',
            'detalles' => 'required|array|min:1',
            'detalles.*.material_id' => 'required|exists:materiales,material_id',
        ];
    }
}
