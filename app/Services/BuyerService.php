<?php

namespace App\Services;

use App\Repositories\BuyerRepository;
use App\Models\Buyer;
use Illuminate\Pagination\LengthAwarePaginator;

class BuyerService
{
    protected $repository;

    public function __construct(BuyerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBuyers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function createBuyer(array $data): Buyer
    {
        // Add specific business logic here (e.g., initial risk assessment)
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }
        
        return $this->repository->create($data);
    }

    public function updateBuyer(Buyer $buyer, array $data): Buyer
    {
        return $this->repository->update($buyer, $data);
    }

    public function deleteBuyer(Buyer $buyer): bool
    {
        return $this->repository->delete($buyer);
    }

    public function findByUuid(string $uuid): ?Buyer
    {
        return $this->repository->findByUuid($uuid);
    }
}
