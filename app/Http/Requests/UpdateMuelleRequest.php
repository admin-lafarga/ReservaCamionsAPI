<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMuelleRequest extends FormRequest
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
        $muelleId = $this->route('muelle')?->muelle_id;

        return [
            'descripcion' => 'required|string|max:255',
            'nombre' => 'required|string|max:255|unique:muelles,nombre,' . $muelleId . ',muelle_id',
            'color' => 'required|string|max:255',
            'empresa_lfycs_id' => 'required|exists:empresas_lfycs,empresa_lfycs_id',
        ];
    }
}
