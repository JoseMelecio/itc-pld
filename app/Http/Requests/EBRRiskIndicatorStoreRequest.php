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
            'key' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'report_config' => 'required|array',
            'risk_indicator' => 'required|string',
            'order' => 'required|numeric',
        ];
    }
}
