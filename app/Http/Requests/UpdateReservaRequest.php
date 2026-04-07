<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservaRequest extends FormRequest
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
            'tipo_camion_id'      => ['required', 'integer', 'exists:tipo_camiones,tipo_camion_id'],
            'material1_id'   => ['required', 'integer', 'exists:materiales,material_id'],
            'material2_id'   => ['nullable', 'integer', 'exists:materiales,material_id', 'different:material1_id'],
            'proveedor_id'        => ['required', 'integer', 'exists:proveedores,proveedor_id'],
            'transportista_id'       => ['nullable', 'integer', 'exists:transportistas,transportista_id'],
            'muelle_id'          => ['required', 'integer', 'exists:muelles,muelle_id'],
            'estado_id'           => ['required', 'integer', 'exists:estados,estado_id'],
            'cantidad1'           => ['required', 'numeric', 'min:0'],
            'cantidad2'           => ['nullable', 'numeric', 'min:0'],
            'pedido1'           => ['required', 'string', 'max:255'],
            'pedido2'           => ['nullable', 'string', 'max:255'],
            'matricula_camion'    => ['required', 'string', 'max:50'],
            'inicio'             => ['required', 'date'],
            'fin'                => ['required', 'date', 'after_or_equal:inicio'],
            'aduana'           => ['boolean'],
            'notas'               => ['nullable', 'string'],
            'telefono'                => ['nullable', 'string', 'max:50'],
            'duracion'           => ['required', 'integer', 'min:0'],
            'archivos' => ['nullable', 'array'],
            'archivos.*' => ['file', 'max:5120'],
        ];
    }
}
