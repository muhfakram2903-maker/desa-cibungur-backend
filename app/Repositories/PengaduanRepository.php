<?php

namespace App\Repositories;

use App\Interfaces\PengaduanRepositoryInterface;
use App\Models\Pengaduan;
use App\Models\PengaduanRespon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PengaduanRepository implements PengaduanRepositoryInterface
{
    public function __construct(
        private readonly Pengaduan $model
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['user', 'kategori', 'penanggungjawab'])
            ->latest();

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (!empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (!empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('nomor_tiket', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): Pengaduan
    {
        return $this->model->with([
            'user', 'kategori', 'penanggungjawab',
            'respon.user', 'timeline.user'
        ])->findOrFail($id);
    }

    public function findByNomorTiket(string $tiket): ?Pengaduan
    {
        return $this->model->with(['kategori', 'timeline', 'responPublik'])
            ->where('nomor_tiket', $tiket)
            ->first();
    }

    public function create(array $data): Pengaduan
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Pengaduan
    {
        $pengaduan = $this->findById($id);
        $pengaduan->update($data);
        return $pengaduan->fresh();
    }

    public function updateStatus(int $id, string $status, ?string $keterangan = null): bool
    {
        $pengaduan = $this->model->findOrFail($id);
        $statusLama = $pengaduan->status;

        $pengaduan->status = $status;
        $saved = $pengaduan->save();

        if ($saved && $keterangan) {
            $pengaduan->timeline()->create([
                'user_id'     => Auth::id(),
                'status_lama' => $statusLama,
                'status_baru' => $status,
                'keterangan'  => $keterangan,
            ]);
        }

        return $saved;
    }

    public function addRespon(int $id, array $data): PengaduanRespon
    {
        $pengaduan = $this->findById($id);
        return $pengaduan->respon()->create(array_merge($data, [
            'user_id' => Auth::id(),
        ]));
    }

    public function getStatistik(): array
    {
        return [
            'total'    => $this->model->count(),
            'menunggu' => $this->model->byStatus('menunggu')->count(),
            'diproses' => $this->model->byStatus('diproses')->count(),
            'selesai'  => $this->model->byStatus('selesai')->count(),
            'ditolak'  => $this->model->byStatus('ditolak')->count(),
        ];
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with(['kategori'])
            ->latest()
            ->paginate($perPage);
    }
}
