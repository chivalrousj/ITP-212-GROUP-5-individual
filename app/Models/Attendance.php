<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // The table name is set on purpose, so Laravel does not guess it
    protected $table = 'attendances';

    protected $fillable = [
        'event_session_id',
        'student_id',
        'check_in_at',
        'check_out_at',
    ];

    // Both times are read as date and time values
    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];
}
