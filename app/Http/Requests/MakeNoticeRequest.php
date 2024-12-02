<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeNoticeRequest extends FormRequest
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
            'pld_notice_id' => 'required|integer',
            'month' => [
                'required',
                'string',
                'regex:/^(0[1-9]|1[0-2])\d{4}$/'
            ],
            'collegiate_entity_tax_id' => 'nullable|string',
            'notice_reference' => 'nullable|string',
            'exempt' => 'required|in:yes,no',
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'month.required' => 'El campo mes reportado es obligatorio.',
            'month.string' => 'El campo mes reportado debe ser una cadena de texto.',
            'month.regex' => 'El campo mes reportado debe cumplir con el formato MMAAAA.',
            'exempt.required' => 'El campo exento es obligatorio.',
            'exempt.in' => 'El campo exento debe ser "si" o "no".',
            'file.required' => 'El campo archivo de excel es obligatorio.',
            'file.file' => 'Debes subir un archivo válido.',
            'file.mimes' => 'El archivo debe ser de tipo Excel (.xlsx o .xls).',
            'file.max' => 'El archivo no debe exceder los 2MB.',
            'file.uploaded' => 'El archivo no pudo ser subido, revise que no este dañado y que sea tipo Excel.',
        ];
    }
}
