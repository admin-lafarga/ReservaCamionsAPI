<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
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
    // Validem que no hi hagi cap nom repetit, ja que no tindria cap mena de sentit que hi hagi més d'un status amb el mateix nom.
    public function rules(): array
    {
        return [
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('status', 'nombre')->ignore($this->route('status')->status_id, 'status_id'),
            ],
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'sometimes|required|boolean',
        ];
    }
}
