<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class EBRRiskElementStoreRequest extends FormRequest
{
//    public function prepareForValidation(): void
//    {
//        if (!is_array($this->report_config)) {
//            $this->merge([
//                'report_config' => json_decode($this->report_config, true),
//            ]);
//        }
//
//    }
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
            'risk_element' => 'required|string',
            'lateral_header' => 'required|string',
            'sub_header' => 'required|string',
            'description' => 'required|string',
            'report_config' => 'array',
            'grouper_field' => 'required|string',
            'active' => 'required|boolean',
        ];
    }
}
