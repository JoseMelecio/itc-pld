<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class EBRStoreRequest extends FormRequest
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
            'file_clients' => 'required|file|max:10240',
            'file_operations' => 'required|file|max:10240',
            'ebr_type_id' => 'required|exists:ebr_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'file_clients.required' => 'El archivo es requerido',
            'file_clients.max' => 'El archivo es demasiado grande',
            'file_operations.required' => 'El archivo es requerido',
            'file_operations.max' => 'El archivo es demasiado grande',
            'ebr_type_id.required' => 'El tipo de EBR es requerido',
            'ebr_type_id.exists' => 'El tipo de EBR debe existir en la base de datos.',
        ];
    }
}
