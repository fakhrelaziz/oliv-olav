<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', Rule::in(['prewedding', 'acara', 'keseharian', 'lainnya'])],
            'foto_url' => ['required', 'string', 'max:500'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }

    public function messages(): array
    {
        return [
            'foto_url.required' => 'Silakan unggah foto terlebih dahulu sebelum menyimpan.',
        ];
    }
}
