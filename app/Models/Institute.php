<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;

    // Columns that can be filled with create() or update()
    protected $fillable = [
        'code',
        'name',
    ];

    // An institute has many programs
    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    // An institute has many students, through its programs
    public function students()
    {
        return $this->hasManyThrough(Student::class, Program::class);
    }
}
