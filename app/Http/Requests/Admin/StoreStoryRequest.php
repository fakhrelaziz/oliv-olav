<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'tanggal_momen' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:2000'],
            'foto_url' => ['nullable', 'string', 'max:500'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }
}
