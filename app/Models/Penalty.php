<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'student_id',
        'missed_scans',
        'amount',
        'sanction',
        'status',
    ];

    // amount always has 2 decimals
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // A penalty belongs to one event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // A penalty belongs to one student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
