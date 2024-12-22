<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PersonListFindRequest extends FormRequest
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
            'name' => 'nullable|string',
            'alias' => 'nullable|string',
            'date' => 'nullable|string|regex:/^\d{4}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])$/',
            'file' => 'nullable|file|mimes:xlsx,xls|max:2048',
        ];
    }
}
