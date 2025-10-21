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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('validate_xsd_xml')) {
            $this->merge([
                'validate_xsd_xml' => filter_var($this->input('validate_xsd_xml'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
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
            'month' => 'required',
            'collegiate_entity_tax_id' => 'nullable',
            'notice_reference' => 'required',
            'exempt' => 'required',
            'occupation_type' => 'required',
            'occupation_description' => 'nullable',
            'validate_xsd_xml' => 'nullable',
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
            'month.required' => 'El mes es requerido',
            'notice_reference.required' => 'La referencia es requerida',
            'exempt.required' => 'El campo excento es requerido',
            'occupation_type.required' => 'La ocupacion es requerida',
        ];
    }
}
