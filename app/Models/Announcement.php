<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'attachment',
    ];

    // An announcement belongs to the user who wrote it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
