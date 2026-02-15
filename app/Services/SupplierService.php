<?php

namespace App\Services;

use App\Repositories\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierService
{
    protected $repository;

    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSuppliers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function createSupplier(array $data): Supplier
    {
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }
        
        return $this->repository->create($data);
    }

    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        return $this->repository->update($supplier, $data);
    }

    public function deleteSupplier(Supplier $supplier): bool
    {
        return $this->repository->delete($supplier);
    }

    public function findByUuid(string $uuid): ?Supplier
    {
        return $this->repository->findByUuid($uuid);
    }
}
