<?php

namespace App\Repositories;

use App\Models\Buyer;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class BuyerRepository extends BaseRepository
{
    public function __construct(Buyer $model)
    {
        parent::__construct($model);
    }
}
