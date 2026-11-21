<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'student_no',
        'sex',
        'year_level',
    ];

    // A student belongs to one user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A student belongs to one program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // A student has many attendance rows
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // A student has many penalties
    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}
