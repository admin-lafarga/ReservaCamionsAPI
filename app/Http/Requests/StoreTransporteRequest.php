<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransporteRequest extends FormRequest
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
            'proveedor_id'        => 'required|exists:proveedores,proveedor_id',
            'nombre'              => 'required|string|max:255|unique:transportes,nombre',
            'abreviatura'         => 'required|string|max:255',
            'NIF'                 => 'required|string|max:50|unique:transportes,NIF',
            'PIN'                 => 'required|string|max:50|unique:transportes,PIN',
            'nombre_contacto'     => 'required|string|max:255',
            'email'               => 'required|email|max:255|unique:transportes,email',
            'tel1'                => ['required', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'tel2'                => ['nullable', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'alert'               => 'boolean',
            'estado'              => 'boolean',
            'puede_gestionar'     => 'boolean',
        ];
    }
}
