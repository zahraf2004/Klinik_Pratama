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
            'tanggal_lahir'  => ['nullable', 'date'],
            'email'          => ['required', 'email', Rule::unique('tenaga_kesehatan', 'email')->ignore($id)],
            'hp'             => ['nullable', 'string', 'max:25'],
            'alumnus'        => ['nullable', 'string', 'max:150'],
            'profesi'        => ['required', 'in:dokter,bidan,perawat'],
        ];
    }
}
