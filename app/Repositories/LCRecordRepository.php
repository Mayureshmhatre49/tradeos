<?php

namespace App\Repositories;

use App\Models\LCRecord;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class LCRecordRepository extends BaseRepository
{
    public function __construct(LCRecord $model)
    {
        parent::__construct($model);
    }
}
