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
            'email'          => ['required', 'email', 'unique:tenaga_kesehatan,email'],
            'hp'             => ['required', 'string', 'max:25'],
            'str'            => ['nullable', 'string', 'max:50'],
            'sip'            => ['nullable', 'string', 'max:50'],
            'tahun_mulai'    => ['required', 'integer', 'min:1980', 'max:2099'],
            'role'           => ['required', 'in:dokter_umum,admin,superadmin'],
            'jadwal_shift'   => ['nullable', 'json'],
        ];
    }
}
