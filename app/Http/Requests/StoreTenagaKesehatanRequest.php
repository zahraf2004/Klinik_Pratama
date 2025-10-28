<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenagaKesehatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan route dilindungi middleware; di sini true
        return true;
    }

    public function rules(): array
    {
        return [
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nama'           => ['required', 'string', 'max:100'],
            'tanggal_lahir'  => ['nullable', 'date'],
            'email'          => ['required', 'email', 'unique:tenaga_kesehatan,email'],
            'hp'             => ['nullable', 'string', 'max:25'],
            'alumnus'        => ['nullable', 'string', 'max:150'],
            'profesi'        => ['required', 'in:dokter,bidan,perawat'],
            // opsional: 'buat_akun' => ['sometimes','boolean'],
        ];
    }
}
