<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicRsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $status = $this->input('status_kehadiran') ?: $this->input('attendance', 'Hadir');
        $this->merge([
            'nama_pengirim' => trim((string) $this->input('nama_pengirim', '')),
            'pesan_ucapan' => trim((string) $this->input('pesan_ucapan', '')),
            'status_kehadiran' => trim((string) $status),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_pengirim' => ['required', 'string', 'min:2', 'max:100'],
            'pesan_ucapan' => ['required', 'string', 'min:2', 'max:1000'],
            'status_kehadiran' => ['required', 'string', Rule::in(['Hadir', 'Tidak Hadir', 'Masih Ragu'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pengirim.required' => 'Mohon tuliskan nama Anda.',
            'pesan_ucapan.required' => 'Mohon tuliskan doa dan ucapan untuk kedua mempelai.',
            'status_kehadiran.in' => 'Pilihan status kehadiran tidak valid.',
        ];
    }
}
