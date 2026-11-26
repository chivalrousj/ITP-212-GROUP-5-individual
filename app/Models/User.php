<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // A user has one student profile
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // A user has many announcements
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // True if the user's role name is one of the given names
    public function hasRole(string ...$names): bool
    {
        return in_array($this->role?->name, $names, true);
    }

    // The route name of this user's dashboard
    public function dashboardRoute(): string
    {
        return match ($this->role?->name) {
            'Admin' => 'admin.dashboard',
            'Officer' => 'officer.dashboard',
            'Adviser' => 'adviser.dashboard',
            default => 'student.dashboard',
        };
    }

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'is_active',
        'is_admin',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
    ];

    public function getFullNameAttribute(): string
{
    return Str::of("{$this->first_name} {$this->middle_name} {$this->last_name}")->squish()->toString();
}
}