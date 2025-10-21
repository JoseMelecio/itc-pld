<?php

namespace App\Http\Requests;

use App\Models\PLDNotice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MakeNoticeRequest extends FormRequest
{
    protected array $dynamicRules = [];

    protected array $dynamicMessages = [];

    public function prepareForValidation(): void
    {
        if ($this->has('validate_xsd_xml')) {
            $this->merge([
                'validate_xsd_xml' => filter_var($this->input('validate_xsd_xml'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $pldNotice = PLDNotice::find($this->input('pld_notice_id'));

        foreach ($pldNotice->customFields as $customField) {
            $this->dynamicRules[$customField->name] = $customField->validation;

            $messages = $customField->validation_message;
            foreach ($messages as $index => $message) {
                $this->dynamicMessages[$customField->name.'.'.$index] = $message;
            }
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
    private function dbRules(): array
    {
        return $this->dynamicRules;
    }

    public function rules(): array
    {
        $rules = [
            'pld_notice_id' => 'required|integer',
            'month' => [
                'required',
                'string',
                'regex:/^\d{4}(0[1-9]|1[0-2])$/',
            ],
            'validate_xsd_xml' => 'nullable',
            'collegiate_entity_tax_id' => 'nullable|string',
            'custom_obligated_subject' => 'nullable|string',
            'notice_reference' => 'nullable|string',
            'exempt' => 'required|in:yes,no',
            'file' => 'required|file|mimes:xlsx,xls|max:51200',
        ];

        if (Auth::user()->multi_subject) {
            $rules['custom_obligated_subject'] = 'required|string';
        }

        return array_merge($rules, $this->dynamicRules);
    }

    public function messages(): array
    {
        return array_merge([
            'month.required' => 'El campo mes reportado es obligatorio.',
            'month.string' => 'El campo mes reportado debe ser una cadena de texto.',
            'month.regex' => 'El campo mes reportado debe cumplir con el formato AAAAMM.',
            'custom_obligated_subject' => 'El campo sujeto obligado es obligatorio.',
            'exempt.required' => 'El campo exento es obligatorio.',
            'exempt.in' => 'El campo exento debe ser "si" o "no".',
            'file.required' => 'El campo archivo de excel es obligatorio.',
            'file.file' => 'Debes subir un archivo válido.',
            'file.mimes' => 'El archivo debe ser de tipo Excel (.xlsx o .xls).',
            'file.max' => 'El archivo no debe exceder los 2MB.',
            'file.uploaded' => 'El archivo no pudo ser subido, revise que no este dañado y que sea tipo Excel.',
        ], $this->dynamicMessages);
    }
}
