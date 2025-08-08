<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMuelleRequest extends FormRequest
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
            'descripcion' => 'required|string|max:255',
            'zona' => 'required|string|max:255',
            'nombre_muelle' => 'required|string|max:255|unique:muelles,nombre_muelle',
            'color' => 'required|string|max:255',
            'numero' => 'required|numeric|max:255|unique:muelles,numero',
            'estado' => 'required|boolean',
            'abierto_festivos' => 'required|boolean',
            'cantidad_acceptada' => 'required|numeric',
            'empresa_id' => 'required|exists:empresas,empresa_id',
        ];
    }
}
