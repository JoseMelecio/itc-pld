<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PersonListFindMassiveRequest extends FormRequest
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
            'file' => 'nullable|file|mimes:xlsx|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'El archivo debe ser un archivo excel 2007 en adelante',
        ];
    }
}
