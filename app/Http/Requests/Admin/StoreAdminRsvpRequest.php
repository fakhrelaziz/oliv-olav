<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminRsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nama_pengirim' => ['required', 'string', 'min:2', 'max:100'],
            'status_kehadiran' => ['required', 'string', Rule::in(['Hadir', 'Tidak Hadir', 'Masih Ragu'])],
            'pesan_ucapan' => ['required', 'string', 'min:2', 'max:1000'],
            'is_tampil' => ['nullable', 'boolean'],
        ];
    }
}
