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

    // An event has many sessions (morning and afternoon, or one half day)
    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }

    // An event has many attendance rows, through its sessions
    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, EventSession::class, 'event_id', 'event_session_id');
    }

    // An event has many penalties
    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    // The user who created the event (the foreign key is created_by, not creator_id)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
