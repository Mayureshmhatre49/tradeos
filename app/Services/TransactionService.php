<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TransactionService
{
    protected $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTransactions(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function createTransaction(array $data): Transaction
    {
        $data['transaction_code'] = 'TRX-' . strtoupper(Str::random(8));
        $data['calculated_profit'] = $this->calculateProfit($data);
        
        return $this->repository->create($data);
    }

    public function updateTransaction(Transaction $transaction, array $data): Transaction
    {
        if (isset($data['quantity']) || isset($data['unit_price']) || isset($data['margin_percentage'])) {
             // Merge existing data with new data for calculation
             $calculationData = array_merge($transaction->toArray(), $data);
             $data['calculated_profit'] = $this->calculateProfit($calculationData);
        }

        return $this->repository->update($transaction, $data);
    }

    protected function calculateProfit(array $data): float
    {
        $quantity = $data['quantity'] ?? 0;
        $unitPrice = $data['unit_price'] ?? 0;
        $margin = $data['margin_percentage'] ?? 0;

        $totalValue = $quantity * $unitPrice;
        return $totalValue * ($margin / 100);
    }

    public function findByUuid(string $uuid): ?Transaction
    {
        return $this->repository->findByUuid($uuid);
    }

    public function deleteTransaction(Transaction $transaction): bool
    {
        return $this->repository->delete($transaction);
    }
}
