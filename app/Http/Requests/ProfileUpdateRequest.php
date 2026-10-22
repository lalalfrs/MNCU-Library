<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();
        $role = $this->input('role') ?: ($user?->role ?? 'mahasiswa');

        $this->merge([
            'role' => strtolower((string) $role),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $table = $user?->getTable() ?? 'pengguna';
        $primaryKey = $user?->getKeyName() ?? 'id_pengguna';
        $userId = $user?->getKey();

        return [
            'role' => ['nullable', 'string', 'in:admin,dosen,mahasiswa'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique($table, 'email')->ignore($userId, $primaryKey),
            ],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'no_wa' => ['required', 'string', 'max:25'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'nik' => ['required', 'string', 'max:30'],
            'nim' => ['nullable', 'required_if:role,mahasiswa', 'string', 'max:30'],
            'nidn' => ['nullable', 'required_if:role,dosen', 'string', 'max:30'],
            'foto_profil' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:200'],
            'kata_sandi' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nim.required_if' => 'NIM wajib diisi untuk mahasiswa.',
            'nidn.required_if' => 'NIDN wajib diisi untuk dosen.',
            'foto_profil.mimes' => 'Foto profil hanya boleh berformat .jpg, .jpeg, atau .png.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 200 KB.',
            'kata_sandi.min' => 'Kata sandi baru minimal 6 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ];
    }
}
