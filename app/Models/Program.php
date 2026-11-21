<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'code',
        'name',
    ];

    // A program belongs to one institute
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    // A program has many students
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
