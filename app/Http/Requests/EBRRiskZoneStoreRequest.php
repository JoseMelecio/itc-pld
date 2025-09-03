<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EBRRiskZoneStoreRequest extends FormRequest
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
            'risk_zone' => 'required|string',
            'incidence_of_crime' => 'required|numeric',
            'percentage_1' => 'required|numeric',
            'percentage_2' => 'required|numeric',
            'zone' => 'required|numeric',
            'color' => 'required|string',
        ];
    }
}
