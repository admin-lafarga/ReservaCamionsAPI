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
        $proveedor = $this->route('proveedor');

        $proveedorId = $proveedor?->proveedor_id; // <- para usar en las validaciones únicas de entidad
        $entidadId = $proveedor?->entidad_id; // <- para usar en las validaciones únicas de entidad

        return [
            // 🔹 Reglas del proveedor
            'tipo_proveedor_id' => 'required|exists:tipo_proveedores,tipo_proveedor_id',
            'email_notificaciones' => 'required|email|max:255',

            // 🔹 Reglas de la entidad asociada
            'entidad' => 'required|array',
            'entidad.nombre' => 'required|string|max:255',
            'entidad.abreviatura' => 'nullable|string|max:255',
            'entidad.nif' => 'required|string|max:50|unique:entidades,nif,' . $entidadId . ',entidad_id',
            'entidad.pin' => 'required|string|max:255',
            'entidad.email' => 'required|email|unique:entidades,email,' . $entidadId . ',entidad_id',
            'entidad.telefono1' => 'nullable|string|max:20|unique:entidades,telefono1,' . $entidadId . ',entidad_id',
            'entidad.telefono2' => 'nullable|string|max:20|unique:entidades,telefono2,' . $entidadId . ',entidad_id',
            'entidad.alerta' => 'required|boolean',
            'entidad.codigo_sap' => 'nullable|string|max:50',
            // 'entidad.idioma' => 'nullable|string|max:5',
            'entidad.nombre_contacto' => 'nullable|string|max:255',
        ];
    }
}
