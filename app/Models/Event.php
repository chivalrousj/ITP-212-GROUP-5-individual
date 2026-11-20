<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'venue',
        'event_date',
        'day_type',
        'availability',
        'fine_per_miss',
        'created_by',
    ];

    // event_date is read as a date, and fine_per_miss always has 2 decimals
    protected $casts = [
        'event_date' => 'date',
        'fine_per_miss' => 'decimal:2',
    ];
}
