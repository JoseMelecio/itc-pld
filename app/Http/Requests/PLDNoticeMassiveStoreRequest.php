<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PLDNoticeMassiveStoreRequest extends FormRequest
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
            'template' => 'required|file|mimes:xlsx|max:10240',
            'notice_id' => 'required|exists:pld_notice,id',
        ];
    }

    public function messages(): array
    {
        return [
            'template.required' => 'El archivo es requerido',
            'template.max' => 'El archivo es demasiado grande',
            'template.mimes' => 'El archivo debe ser un archivo excel 2007 en adelante',
            'notice_id.required' => 'La notificacion es requerida',
            'notice_id.exists' => 'La notificacion no existe',

        ];
    }
}
