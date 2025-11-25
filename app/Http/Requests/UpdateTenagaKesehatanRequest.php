<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenagaKesehatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('tenaga_kesehatan')?->id ?? $this->route('tenaga-kesehatan');

        return [
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nama'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', Rule::unique('tenaga_kesehatan', 'email')->ignore($id)],
            'hp'             => ['required', 'string', 'max:25'],
            'str'            => ['nullable', 'string', 'max:50'],
            'sip'            => ['nullable', 'string', 'max:50'],
            'tahun_mulai'    => ['nullable', 'integer', 'min:1980', 'max:2099'],
            'role'           => ['required', 'in:dokter_umum,admin,superadmin'],
            'jadwal_shift'   => ['nullable', 'json'],
        ];
    }
}
