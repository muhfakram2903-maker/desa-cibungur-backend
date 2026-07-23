<?php

namespace App\Repositories;

use App\Interfaces\PendudukRepositoryInterface;
use App\Models\Penduduk;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PendudukRepository implements PendudukRepositoryInterface
{
    public function __construct(
        private readonly Penduduk $model
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['dusun', 'rw', 'rt', 'agama', 'pendidikan', 'pekerjaan'])
            ->latest();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['dusun_id'])) {
            $query->byDusun($filters['dusun_id']);
        }

        if (!empty($filters['rw_id'])) {
            $query->byRw($filters['rw_id']);
        }

        if (!empty($filters['rt_id'])) {
            $query->byRt($filters['rt_id']);
        }

        if (isset($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (isset($filters['status_aktif'])) {
            $query->where('status_aktif', $filters['status_aktif']);
        }

        if (!empty($filters['agama_id'])) {
            $query->where('agama_id', $filters['agama_id']);
        }

        if (!empty($filters['pendidikan_id'])) {
            $query->where('pendidikan_id', $filters['pendidikan_id']);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Penduduk
    {
        return $this->model->with([
            'dusun', 'rw', 'rt', 'agama', 'pendidikan', 'pekerjaan', 'riwayat.user'
        ])->findOrFail($id);
    }

    public function findByNik(string $nik): ?Penduduk
    {
        return $this->model->where('nik', $nik)->first();
    }

    public function create(array $data): Penduduk
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Penduduk
    {
        $penduduk = $this->findById($id);
        $penduduk->update($data);
        return $penduduk->fresh();
    }

    public function delete(int $id): bool
    {
        $penduduk = $this->findById($id);
        return $penduduk->delete();
    }

    public function getStatistik(): array
    {
        $total = $this->model->aktif()->count();
        $lakiLaki = $this->model->aktif()->lakiLaki()->count();
        $perempuan = $this->model->aktif()->perempuan()->count();
        $jumlahKk = $this->model->aktif()
            ->where('status_keluarga', 'kepala_keluarga')
            ->count();

        // Statistik per agama
        $perAgama = $this->model->aktif()
            ->select('agama_id', DB::raw('count(*) as total'))
            ->with('agama:id,nama')
            ->groupBy('agama_id')
            ->get()
            ->map(fn($item) => [
                'nama'  => $item->agama?->nama ?? 'Tidak Diketahui',
                'total' => $item->total,
            ]);

        // Statistik per pendidikan
        $perPendidikan = $this->model->aktif()
            ->select('pendidikan_id', DB::raw('count(*) as total'))
            ->with('pendidikan:id,nama')
            ->groupBy('pendidikan_id')
            ->get()
            ->map(fn($item) => [
                'nama'  => $item->pendidikan?->nama ?? 'Tidak Diketahui',
                'total' => $item->total,
            ]);

        // Statistik per pekerjaan
        $perPekerjaan = $this->model->aktif()
            ->select('pekerjaan_id', DB::raw('count(*) as total'))
            ->with('pekerjaan:id,nama')
            ->groupBy('pekerjaan_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'nama'  => $item->pekerjaan?->nama ?? 'Tidak Bekerja',
                'total' => $item->total,
            ]);

        return [
            'total'          => $total,
            'laki_laki'      => $lakiLaki,
            'perempuan'      => $perempuan,
            'jumlah_kk'      => $jumlahKk,
            'per_agama'      => $perAgama,
            'per_pendidikan' => $perPendidikan,
            'per_pekerjaan'  => $perPekerjaan,
        ];
    }

    public function getByWilayah(string $type, int $id, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->aktif()->with(['dusun', 'rw', 'rt']);

        match ($type) {
            'dusun' => $query->byDusun($id),
            'rw'    => $query->byRw($id),
            'rt'    => $query->byRt($id),
            default => null,
        };

        return $query->paginate($perPage);
    }

    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->search($keyword)
            ->with(['dusun', 'rw', 'rt'])
            ->paginate($perPage);
    }

    public function import(array $data): array
    {
        $success = 0;
        $failed  = 0;
        $errors  = [];

        foreach ($data as $index => $row) {
            try {
                $this->model->updateOrCreate(
                    ['nik' => $row['nik']],
                    $row
                );
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return compact('success', 'failed', 'errors');
    }
}
