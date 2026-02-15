<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'user_identifier',
        'subject_id',
        'subject_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function subject()
    {
        return $this->morphTo();
    }
}
