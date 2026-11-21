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

    // A session belongs to one event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // A session has many attendance rows
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'event_session_id');
    }

    // Is check-in open right now?
    public function isCheckInOpen(): bool
    {
        return $this->isOpen('check_in');
    }

    // Is check-out open right now?
    public function isCheckOutOpen(): bool
    {
        return $this->isOpen('check_out');
    }

    // One rule for both. $type is 'check_in' or 'check_out'.
    private function isOpen(string $type): bool
    {
        $event = $this->event;

        // Manual event: the Officer's open or closed value decides
        if ($event->availability === 'manual') {
            return (bool) $this->{$type . '_open'};
        }

        // Scheduled event: it must be today ...
        if (!$event->event_date->isToday()) {
            return false;
        }

        $start = $this->{$type . '_start'};
        $end = $this->{$type . '_end'};

        if (!$start || !$end) {
            return false;
        }

        // ... and the time now (H:i:s, same format as MySQL time) must be inside the window.
        // This uses the Asia/Manila timezone from config/app.php.
        $now = now()->format('H:i:s');

        return $now >= $start && $now <= $end;
    }
}
