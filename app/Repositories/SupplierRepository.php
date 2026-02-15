<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class SupplierRepository extends BaseRepository
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }
}
