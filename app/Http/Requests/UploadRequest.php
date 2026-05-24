<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
        $requiredFile = $this->isMethod('post') ? 'required' : 'nullable';

        return [
            'berkas' => [
                $requiredFile,
                'file',
                'mimetypes:application/pdf,image/jpeg,image/png,image/webp',
                'max:5120',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'berkas.required' => 'File wajib diunggah.',
            'berkas.nullable' => 'File boleh dikosongkan saat pembaruan.',
            'berkas.file' => 'Input harus berupa file yang valid.',
            'berkas.mimetypes' => 'File hanya boleh berupa PDF, JPG, JPEG, PNG, atau WEBP.',
            'berkas.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
