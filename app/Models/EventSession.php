<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'period',
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'check_in_open',
        'check_out_open',
    ];

    // The open flags are true or false, not 1 or 0
    protected $casts = [
        'check_in_open' => 'boolean',
        'check_out_open' => 'boolean',
    ];
}
