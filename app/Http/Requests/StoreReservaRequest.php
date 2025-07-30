<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
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
            'tipo_material1_id'   => ['required', 'integer', 'exists:tipo_materiales,tipo_material_id'],
            'tipo_material2_id'   => ['nullable', 'integer', 'exists:tipo_materiales,tipo_material_id', 'different:tipo_material1_id'],
            'proveedor_id'        => ['required', 'integer', 'exists:proveedores,proveedor_id'],
            'transporte_id'       => ['required', 'integer', 'exists:transportes,transporte_id'],
            'muelle1_id'          => ['required', 'integer', 'exists:muelles,muelle_id'],
            'muelle2_id'          => ['nullable', 'integer', 'exists:muelles,muelle_id', 'different:muelle1_id'],
            'status_id'           => ['required', 'integer', 'exists:status,status_id'],
            'cantidad1'           => ['required', 'numeric', 'min:0'],
            'cantidad2'           => ['nullable', 'numeric', 'min:0'],
            'pedido_LF'           => ['nullable', 'string', 'max:255'],
            'matricula_camion'    => ['required', 'string', 'max:50'],
            'inicio1'             => ['required', 'date'],
            'fin1'                => ['required', 'date', 'after_or_equal:inicio1'],
            'inicio2'             => ['nullable', 'date'],
            'fin2'                => ['nullable', 'date', 'after_or_equal:inicio2'],
            'es_aduana'           => ['boolean'],
            'notas'               => ['nullable', 'string'],
            'tel1'                => ['nullable', 'string', 'max:50'],
            'duracion1'           => ['required', 'numeric', 'min:0'],
            'duracion2'           => ['nullable', 'numeric', 'min:0'],
            'archivos' => ['nullable', 'array'],
            'archivos.*' => ['file', 'max:5120'],
        ];
    }
}
