<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'jumlah_undangan' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'string', Rule::in(['Hadir', 'Tidak Hadir', 'Masih Ragu', 'Belum Konfirmasi'])],
        ];
    }
}
