<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreNewPasswordRequest extends FormRequest
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
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'El campo actual es requerido.',
            'password.required' => 'El campo nueva es requerido.',
            'password.min' => 'El campo nueva debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'El campo confirmación es requerido.',
            'password_confirmation.min' => 'El campo confirmación debe tener al menos 8 caracteres.',
        ];
    }

    /**
     * Applies additional validation logic after the default validation process.
     *
     * This method checks if the authenticated user is valid and if the provided current password matches
     * the password stored in the database. If the validation fails, an error is added to the validator
     * for the 'current_password' field.
     *
     * @param \Illuminate\Validation\Validator $validator Validator instance to perform validation.
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user(); // Usuario autenticado
            if (! $user || ! Hash::check($this->input('current_password'), $user->password)) {
                $validator->errors()->add('current_password', 'La contraseña actual no es correcta.');
            }
        });
    }
}
