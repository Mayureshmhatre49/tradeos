<?php

namespace App\Services;

use App\Repositories\LCRecordRepository;
use App\Models\LCRecord;
use Illuminate\Pagination\LengthAwarePaginator;

class LCRecordService
{
    protected $repository;

    public function __construct(LCRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllLCRecords(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function createLCRecord(array $data): LCRecord
    {
        return $this->repository->create($data);
    }

    public function updateLCRecord(LCRecord $lcRecord, array $data): LCRecord
    {
        return $this->repository->update($lcRecord, $data);
    }

    public function findByUuid(string $uuid): ?LCRecord
    {
        return $this->repository->findByUuid($uuid);
    }

    public function deleteLCRecord(LCRecord $lcRecord): bool
    {
        return $this->repository->delete($lcRecord);
    }
}
