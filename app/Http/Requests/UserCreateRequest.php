<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserCreateRequest extends FormRequest
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
            'user_name' => 'required|unique:users,user_name',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:13|unique:users,tax_id',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:13|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'permissions' => 'required|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'El nombre de usuario es obligatorio.',
            'user_name.unique' => 'El nombre de usuario ya esta en uso.',
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'tax_id.required' => 'El RFC es obligatorio.',
            'tax_id.unique' => 'El RFC ya está en uso.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.email' => 'El correo electrónico debe tener formato correcto.',
            'phone.required' => 'El telefono es obligatorio.',
            'phone.unique' => 'El telefono ya está en uso.',
            'phone.min' => 'El telefono debe tener 13 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password' => 'La confirmacion de contraseña es obligatoria.',
            'permissions.required' => 'Los permisos son requeridos.',
        ];
    }
}
