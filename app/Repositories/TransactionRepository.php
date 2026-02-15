<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class TransactionRepository extends BaseRepository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }
}
