<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PegawaiRequest extends FormRequest
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
            'user_id' => [
                'required',
                'integer',
            ],
            'nip' => [
                'required',
                'string',
                'max:50',
            ],
            'status' => [
                'required',
                'string',
                'in:dosen,pegawai',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User id wajib diisi.',
            'user_id.integer' => 'User id role harus berupa angka.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.string' => 'NIP role harus berupa teks.',
            'nip.max' => 'NIP maksimal 50 karakter.',
            'status.required' => 'Status wajib diisi.',
            'status.string' => 'Status harus berupa teks.',
            'status.in' => 'Status hanya bisa dosen atau pegawai.',
        ];
    }
}
