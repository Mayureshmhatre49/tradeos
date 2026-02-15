<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Buyer extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'company_name',
        'country_id',
        'tax_id',
        'risk_level',
        'status',
        'ubo_details',
        'approved_by',
    ];

    protected $casts = [
        'ubo_details' => 'array',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
