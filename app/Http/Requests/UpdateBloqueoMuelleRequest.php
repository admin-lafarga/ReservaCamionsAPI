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
            'muelle_id' => 'required|integer|exists:muelles,muelle_id',
            'asunto' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',            
            // --- IGNORE --- 
            
        ];
    }
}
