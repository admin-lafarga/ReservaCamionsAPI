<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProveedorRequest extends FormRequest
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
        $proveedorId = $this->route('proveedore')?->proveedor_id

        return [
            'tipo_proveedor_id' => 'required|exists:tipo_proveedores,tipo_proveedor_id',
            'codigo_sap' => 'required|string|max:255',
            'nombre' => 'required|string|max:255|unique:proveedores,nombre,' . $proveedorId . ',proveedor_id',
            'abreviatura' => 'required|string|max:255',
            'NIF' => 'required|string|max:50|unique:proveedores,NIF,' . $proveedorId . ',proveedor_id',
            'PIN' => 'required|string|max:50|unique:proveedores,PIN,' . $proveedorId . ',proveedor_id',
            'nombre_contacto' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:proveedores,email,' . $proveedorId . ',proveedor_id',
            'notificaciones_email' => 'required|email|max:255|unique:proveedores,notificaciones_email,' . $proveedorId . ',proveedor_id',
            'tel1' => ['required', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'tel2' => ['nullable', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'alerta' => 'boolean',
            'estado' => 'boolean',
        ];
    }
}
