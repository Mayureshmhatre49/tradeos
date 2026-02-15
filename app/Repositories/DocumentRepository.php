<?php

namespace App\Repositories;

use App\Models\Document;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class DocumentRepository extends BaseRepository
{
    public function __construct(Document $model)
    {
        parent::__construct($model);
    }
}
