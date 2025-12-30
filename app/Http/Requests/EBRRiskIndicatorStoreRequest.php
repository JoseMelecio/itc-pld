<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class EBRRiskIndicatorStoreRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if (!is_array($this->report_config)) {
            $this->merge([
                'report_config' => json_decode($this->report_config, true),
            ]);
        }

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
            'characteristic' => 'required|string',
            'key' => 'nullable|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'type' => 'nullable|string',
            'risk_element_id' => 'required|exists:ebr_risk_elements,id',
            'sql' => 'nullable|string',
            'order' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'characteristic.required' => 'El campo es necesario',
            'name.required' => 'El campo es necesario',
            'description.required' => 'El campo es necesario',
            'report_config.required' => 'El campo es necesario',
            'risk_element_id.required' => 'El campo es necesario',
            'risk_element_id.exists' => 'El elemento de riesgo no exite',
            'order.required' => 'El campo es necesario',
        ];
    }
}
