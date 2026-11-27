<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'section' => ['nullable', 'string', 'max:60', 'regex:/^[a-zA-Z0-9_-]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'File gambar wajib dipilih.',
            'image.image' => 'File harus berupa gambar valid (JPG, PNG, WEBP, GIF).',
            'image.max' => 'Ukuran gambar maksimal 10MB.',
        ];
    }
}
