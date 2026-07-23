<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePendudukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('penduduk.edit');
    }

    public function rules(): array
    {
        $pendudukId = $this->route('penduduk');
        return [
            'nik'              => ['required', 'digits:16', Rule::unique('penduduk', 'nik')->ignore($pendudukId)],
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
            'alasan_keluar'    => 'nullable|string|max:300',
        ];
    }
}
