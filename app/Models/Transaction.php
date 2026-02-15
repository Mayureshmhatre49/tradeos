<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'transaction_code',
        'buyer_id',
        'supplier_id',
        'origin_country_id',
        'destination_country_id',
        'structuring_country_id',
        'product_category',
        'hs_code',
        'quantity',
        'unit_price',
        'currency_id',
        'margin_percentage',
        'calculated_profit',
        'payment_type',
        'status',
        'risk_level',
        'risk_score',
        'risk_reason',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'calculated_profit' => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
