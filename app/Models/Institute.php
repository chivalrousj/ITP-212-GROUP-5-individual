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
}
