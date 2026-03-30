<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBloqueoMuelleRequest extends FormRequest
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
            'muelle_id' => 'nullable|integer|exists:muelles,muelle_id',
            'asunto' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin' => 'required|date|after:inicio',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'muelle_id.required' => 'El muelle es obligatorio.',
            'muelle_id.exists' => 'El muelle seleccionado no existe.',
            'asunto.required' => 'El asunto del bloqueo es obligatorio.',
            'asunto.max' => 'El asunto no puede tener más de 255 caracteres.',
            'inicio.required' => 'La fecha de inicio es obligatoria.',
            'inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fin.required' => 'La fecha de fin es obligatoria.',
            'fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }
}
