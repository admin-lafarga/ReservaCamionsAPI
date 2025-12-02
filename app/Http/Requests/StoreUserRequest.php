<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'       => 'required|string|max:255',
            'apellidos'  => 'required|string|max:255',
            'username'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'username'   => 'required|string|max:255|unique:users,username',
            'password'   => 'required|string|min:8',
            'PIN'        => 'required|string|max:10|unique:users,PIN',
            'NIF'        => 'nullable|string|max:20',
            'tel1'       => 'nullable|string|max:50',
            'rol_id'     => 'required|exists:roles,rol_id',
            'estado'     => 'required|boolean',
        ];
    }
}
