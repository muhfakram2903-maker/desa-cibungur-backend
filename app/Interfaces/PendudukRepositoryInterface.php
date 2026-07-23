<?php

namespace App\Interfaces;

interface PendudukRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15);
    public function findById(int $id);
    public function findByNik(string $nik);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getStatistik(): array;
    public function getByWilayah(string $type, int $id, int $perPage = 15);
    public function search(string $keyword, int $perPage = 15);
    public function import(array $data): array;
}
