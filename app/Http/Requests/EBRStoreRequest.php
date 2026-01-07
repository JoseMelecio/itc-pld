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
            'file_clients' => [
                'required',
                'file',
                'max:102400',
                'mimetypes:application/json,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],

            'file_operations' => [
                'required',
                'file',
                'max:102400',
                'mimetypes:application/json,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file_clients.required' => 'El archivo es requerido',
            'file_clients.mimetypes' => 'El archivo debe ser Excel (.xlsx) o JSON',
            'file_clients.max' => 'El archivo es demasiado grande',

            'file_operations.required' => 'El archivo es requerido',
            'file_operations.mimetypes' => 'El archivo debe ser Excel (.xlsx) o JSON',
            'file_operations.max' => 'El archivo es demasiado grande',
        ];
    }
}
