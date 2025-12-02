<?php

namespace App\Http\Requests;

use Dotenv\Repository\RepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpresaLfycsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $empresaID = $this->route('empresa')?->empresa_lfycs_id;

        return [
            'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('empresas_lfycs', 'nombre')->ignore($empresaID, 'empresa_lfycs_id')],
            'descripcion' => 'string|max:255',
        ];
    }
}
