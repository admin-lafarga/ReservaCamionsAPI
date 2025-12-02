<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:materiales,nombre',
            'codigo_sap' => 'required|string|max:255|unique:materiales,codigo_sap',
            'muelles' => 'required|array',
            'muelles.*.muelle_id' => 'required|integer|exists:muelles,muelle_id',
            'tipo_camiones' => 'required|array',
            'tipo_camiones.*.tipo_camion_id' => 'required|integer|exists:tipo_camiones,tipo_camion_id',
        ];
    }
}
