<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Document extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'transaction_id',
        'shipment_id',
        'lc_record_id',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by',
        'version',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function lcRecord()
    {
        return $this->belongsTo(LCRecord::class);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
