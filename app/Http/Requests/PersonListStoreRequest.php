<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonListStoreRequest extends FormRequest
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
            'origin' => 'required|string',
            'un_list_type' => 'required|string',
            'record_type' => 'required|string',
            'first_name' => 'required|string',
            'second_name' => 'nullable|string',
            'third_name' => 'nullable|string',
            'alias' => 'nullable|string',
            'birth_date' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'file' => 'required|file|mimes:pdf'
        ];
    }
}
