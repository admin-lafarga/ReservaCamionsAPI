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
        return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $this->route('user')->id,
                'password' => 'nullable|string|min:8',
                'pin' => 'nullable|string|max:10',
                'tel1' => 'nullable|string|max:20',
        ];
    }
}
