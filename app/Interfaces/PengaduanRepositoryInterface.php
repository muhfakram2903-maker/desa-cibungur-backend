<?php

namespace App\Interfaces;

interface PengaduanRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15);
    public function findById(int $id);
    public function findByNomorTiket(string $tiket);
    public function create(array $data);
    public function update(int $id, array $data);
    public function updateStatus(int $id, string $status, string $keterangan = null): bool;
    public function addRespon(int $id, array $data);
    public function getStatistik(): array;
    public function getByUser(int $userId, int $perPage = 10);
}
