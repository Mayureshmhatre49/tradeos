<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Shipment extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'transaction_id',
        'port_of_loading',
        'port_of_discharge',
        'vessel_name',
        'bl_number',
        'insurance_details',
        'eta',
        'risk_flag',
    ];

    protected $casts = [
        'eta' => 'date',
        'risk_flag' => 'boolean',
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
