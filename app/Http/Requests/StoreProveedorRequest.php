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
            'email_notificaciones' => 'required|email|max:255|unique:proveedores,email_notificaciones',
            'entidad' => 'required|array',
            'entidad.nombre' => 'required|string|max:255',
            'entidad.abreviatura' => 'nullable|string|max:10',
            'entidad.nif' => 'required|string|max:50',
            'entidad.pin' => 'required|string|max:255',
            'entidad.email' => 'required|email',
            'entidad.telefono1' => 'nullable|string|max:20',
            'entidad.telefono2' => 'nullable|string|max:20',
            'entidad.alerta' => 'required|boolean',
            'entidad.codigo_sap' => 'nullable|string|max:50',
            'entidad.idioma' => 'nullable|string|max:5',
            'entidad.nombre_contacto' => 'nullable|string|max:255',
        ];
    }
}
