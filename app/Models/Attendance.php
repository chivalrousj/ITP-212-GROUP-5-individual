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

    // An attendance row belongs to one session (the foreign key is event_session_id)
    public function session()
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    // An attendance row belongs to one student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
