<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransporteRequest extends FormRequest
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
        $transporteId = $this->route('transportista')->transporte_id;

        return [
            'proveedor_id'        => 'required|exists:proveedores,proveedor_id',
            'nombre'              => 'required|string|max:255|unique:transportes,nombre,' . $transporteId . ',transporte_id',
            'abreviatura'         => 'required|string|max:255',
            'NIF'                 => 'required|string|max:50|unique:transportes,NIF,' . $transporteId . ',transporte_id',
            'PIN'                 => 'required|string|max:50|unique:transportes,PIN,' . $transporteId . ',transporte_id',
            'nombre_contacto'     => 'required|string|max:255',
            'email'               => 'required|email|max:255|unique:transportes,email,' . $transporteId . ',transporte_id',
            'tel1'                => ['required', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'tel2'                => ['nullable', 'regex:/^\+?[0-9\s\-]{6,20}$/'],
            'alert'               => 'boolean',
            'estado'              => 'boolean',
            'puede_gestionar'     => 'boolean',
        ];
    }
}

