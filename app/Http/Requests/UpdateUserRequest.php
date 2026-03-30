<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $userIdFromRoute = $this->route('id');

        // return auth()->check() && auth()->id() === (int) $userIdFromRoute;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $userId . ',id',
            'username' => 'required|string|max:255|unique:users,username,'. $userId . ',id',
            'contraseña' => 'nullable|string|min:8',
            'NIF' => 'nullable|string|max:20',
            'tel1' => 'nullable|string|max:50',
            'rol_id' => 'required|exists:roles,rol_id',
            'idioma'    => 'required|in:es,en,fr,cat',
        ];
    }
}
