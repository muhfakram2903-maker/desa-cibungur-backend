<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePendudukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('penduduk.create');
    }

    public function rules(): array
    {
        return [
            'nik'              => 'required|digits:16|unique:penduduk,nik',
            'nomor_kk'         => 'required|digits:16',
            'nama'             => 'required|string|min:3|max:200',
            'jenis_kelamin'    => 'required|in:L,P',
            'tempat_lahir'     => 'nullable|string|max:100',
            'tanggal_lahir'    => 'nullable|date|before:today',
            'alamat'           => 'nullable|string|max:500',
            'dusun_id'         => 'nullable|exists:dusun,id',
            'rw_id'            => 'nullable|exists:rw,id',
            'rt_id'            => 'nullable|exists:rt,id',
            'agama_id'         => 'nullable|exists:agama,id',
            'pendidikan_id'    => 'nullable|exists:pendidikan,id',
            'pekerjaan_id'     => 'nullable|exists:pekerjaan,id',
            'status_kawin'     => 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'status_keluarga'  => 'nullable|in:kepala_keluarga,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya',
            'no_hp'            => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:100',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_aktif'     => 'boolean',
            'tanggal_masuk'    => 'nullable|date',
            'tanggal_keluar'   => 'nullable|date|after:tanggal_masuk',
            'alasan_keluar'    => 'nullable|string|max:300|required_with:tanggal_keluar',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.digits'            => 'NIK harus 16 digit angka.',
            'nik.unique'            => 'NIK sudah terdaftar dalam sistem.',
            'nomor_kk.required'     => 'Nomor KK wajib diisi.',
            'nomor_kk.digits'       => 'Nomor KK harus 16 digit angka.',
            'nama.required'         => 'Nama lengkap wajib diisi.',
            'nama.min'              => 'Nama minimal 3 karakter.',
            'jenis_kelamin.required'=> 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.before'  => 'Tanggal lahir tidak boleh di masa depan.',
            'foto.image'            => 'File harus berupa gambar.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
            'email.email'           => 'Format email tidak valid.',
            'alasan_keluar.required_with' => 'Alasan keluar wajib diisi jika tanggal keluar diisi.',
        ];
    }
}
