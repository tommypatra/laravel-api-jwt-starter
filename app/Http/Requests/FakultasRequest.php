<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FakultasRequest extends FormRequest
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
        // $id = $this->route('id');

        return [
            'fakultas_siakad_id' => [
                'nullable',
                'integer',
            ],
            'nama_fakultas' => [
                'required',
                'string',
                'max:180',
            ],
            'fakultas_singkatan' => [
                'nullable',
                'string',
                'max:50',
            ],
            'is_aktif' => [
                'nullable',
                'boolean',
            ],
            'updated_at_siakad' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ]];
    }
}
