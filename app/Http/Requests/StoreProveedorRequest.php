<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedorRequest extends FormRequest
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
            'tipo_proveedor_id' => 'required|exists:tipo_proveedores,tipo_proveedor_id',
            'codigo_sap' => 'required|string|max:255',
            'nombre' => 'required|string|max:255|unique:proveedores,nombre',
            'abreviatura' => 'required|string|max:255',
            'NIF' => 'required|string|max:50|unique:proveedores,NIF',
            'PIN' => 'required|string|max:50|unique:proveedores,PIN',
            'nombre_contacto'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:proveedores,email',
            'notificaciones_email' => 'required|email|max:255|unique:proveedores,notificaciones_email',
            'tel1' => ['required', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'tel2' => ['nullable', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'alerta' => 'boolean',
            'estado' => 'boolean',
        ];
    }
}
