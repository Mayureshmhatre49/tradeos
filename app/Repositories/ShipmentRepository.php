<?php

namespace App\Repositories;

use App\Models\Shipment;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class ShipmentRepository extends BaseRepository
{
    public function __construct(Shipment $model)
    {
        parent::__construct($model);
    }
}
