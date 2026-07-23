<?php

namespace App\Services;

use App\Interfaces\PendudukRepositoryInterface;
use App\Models\PendudukRiwayat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PendudukService
{
    public function __construct(
        private readonly PendudukRepositoryInterface $repository
    ) {}

    /**
     * Get all penduduk dengan filter
     */
    public function getAll(array $filters = [], int $perPage = 15)
    {
        return $this->repository->getAll($filters, $perPage);
    }

    /**
     * Get statistik penduduk (dengan cache)
     */
    public function getStatistik(): array
    {
        return Cache::remember('statistik_penduduk', 3600, function () {
            return $this->repository->getStatistik();
        });
    }

    /**
     * Create penduduk baru
     */
    public function create(array $data): mixed
    {
        // Set created_by
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        // Handle foto upload
        if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
            $data['foto'] = $data['foto']->store('penduduk/foto', 'public');
        }

        $penduduk = $this->repository->create($data);

        // Clear cache statistik
        Cache::forget('statistik_penduduk');

        return $penduduk;
    }

    /**
     * Update penduduk dengan tracking riwayat
     */
    public function update(int $id, array $data): mixed
    {
        $existing = $this->repository->findById($id);
        $data['updated_by'] = Auth::id();

        // Handle foto upload
        if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
            // Hapus foto lama
            if ($existing->foto) {
                Storage::disk('public')->delete($existing->foto);
            }
            $data['foto'] = $data['foto']->store('penduduk/foto', 'public');
        }

        // Track perubahan field penting
        $fieldsToTrack = ['nama', 'nik', 'nomor_kk', 'alamat', 'status_aktif', 'status_kawin'];
        foreach ($fieldsToTrack as $field) {
            if (isset($data[$field]) && $existing->$field != $data[$field]) {
                PendudukRiwayat::create([
                    'penduduk_id' => $id,
                    'user_id'     => Auth::id(),
                    'field_changed' => $field,
                    'nilai_lama'  => (string) $existing->$field,
                    'nilai_baru'  => (string) $data[$field],
                ]);
            }
        }

        $penduduk = $this->repository->update($id, $data);

        Cache::forget('statistik_penduduk');

        return $penduduk;
    }

    /**
     * Delete penduduk (soft delete)
     */
    public function delete(int $id): bool
    {
        $result = $this->repository->delete($id);
        Cache::forget('statistik_penduduk');
        return $result;
    }

    /**
     * Get data for public statistik page
     */
    public function getStatistikPublik(): array
    {
        $stats = $this->getStatistik();

        // Format data untuk Chart.js
        return [
            'ringkasan' => [
                'total'    => $stats['total'],
                'laki_laki' => $stats['laki_laki'],
                'perempuan' => $stats['perempuan'],
                'kk'       => $stats['jumlah_kk'],
            ],
            'chart_agama' => [
                'labels' => $stats['per_agama']->pluck('nama')->toArray(),
                'data'   => $stats['per_agama']->pluck('total')->toArray(),
            ],
            'chart_pendidikan' => [
                'labels' => $stats['per_pendidikan']->pluck('nama')->toArray(),
                'data'   => $stats['per_pendidikan']->pluck('total')->toArray(),
            ],
            'chart_pekerjaan' => [
                'labels' => $stats['per_pekerjaan']->pluck('nama')->toArray(),
                'data'   => $stats['per_pekerjaan']->pluck('total')->toArray(),
            ],
        ];
    }
}
