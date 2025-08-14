<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class EBRConfigurationStoreRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->user()->id,
            'template_clients_config' => array_filter(
                array_map('trim', explode(',', $this->template_clients_config)),
                fn($v) => $v !== ''
            ),
            'template_operations_config' => array_filter(
                array_map('trim', explode(',', $this->template_operations_config)),
                fn($v) => $v !== ''
            ),
        ]);
    }
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
            'tenant_id' => 'required|integer',
            'user_id' => 'required|integer',
            'template_clients_config' => 'required|array|min:1',
            'template_operations_config' => 'required|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'template_clients_config.required' => 'La estructura de la plantilla de clientes es requerida.',
            'template_operations_config.required' => 'La estructura de la plantilla de operaciones es requerida.',
            'template_clients_config.min' => 'La estructura de la plantilla de clientes debe tener al menos 1 columna.',
            'template_operations_config.min' => 'La estructura de la plantilla de operaciones debe tener al menos 1 columna.',
        ];
    }
}
