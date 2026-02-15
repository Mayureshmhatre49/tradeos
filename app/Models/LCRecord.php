<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LCRecord extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'l_c_records';

    protected $fillable = [
        'transaction_id',
        'lc_number',
        'issuing_bank',
        'confirming_bank',
        'expiry_date',
        'shipment_deadline',
        'amount',
        'discrepancy_notes',
        'payment_status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'shipment_deadline' => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
