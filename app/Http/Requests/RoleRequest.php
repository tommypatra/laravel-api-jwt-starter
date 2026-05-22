<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:100',
            ],
            'is_admin' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama role wajib diisi.',
            'nama.string' => 'Nama role harus berupa teks.',
            'nama.max' => 'Nama role maksimal 100 karakter.',

            'is_admin.boolean' => 'Status admin harus true/false atau 1/0.',
        ];
    }
}
