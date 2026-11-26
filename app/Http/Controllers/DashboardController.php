<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // The status word of a fine that is not paid yet. Change it if yours is different
    private const UNPAID = 'unpaid';

    // Each role has its own dashboard view. Only plain numbers are cached, never models
    public function admin()
    {
        $stats = Cache::remember('admin.stats', now()->addMinutes(10), fn () => [
            'roles' => User::join('roles', 'roles.id', '=', 'users.role_id')
                ->selectRaw('roles.name as role, count(*) as total')
                ->groupBy('roles.name')
                ->pluck('total', 'role')
                ->toArray(),
            'total_users' => User::count(),
            'pending'     => User::where('is_active', false)->count(),
            'institutes'  => Institute::count(),
            'programs'    => Program::count(),
        ]);

        return view('dashboards.admin', compact('stats'));
    }

    public function officer()
    {
        $stats = Cache::remember('officer.stats', now()->addMinutes(10), fn () => [
            'students'      => Student::count(),
            'upcoming'      => Event::whereDate('event_date', '>=', today())->count(),
            'unpaid_fines'  => Penalty::where('status', self::UNPAID)->count(),
            'announcements' => Announcement::count(),
        ]);

        return view('dashboards.officer', compact('stats'));
    }

    public function adviser()
    {
        $stats = Cache::remember('adviser.stats', now()->addMinutes(10), fn () => [
            'upcoming'     => Event::whereDate('event_date', '>=', today())->count(),
            'attendance'   => Attendance::count(),
            'unpaid_fines' => Penalty::where('status', self::UNPAID)->count(),
        ]);

        return view('dashboards.adviser', compact('stats'));
    }

    public function student()
    {
        $userId = auth()->id();
        $studentId = auth()->user()->student?->id;

        $stats = Cache::remember('student.stats.' . $userId, now()->addMinutes(10), fn () => [
            'upcoming'   => Event::whereDate('event_date', '>=', today())->count(),
            'attendance' => $studentId ? Attendance::where('student_id', $studentId)->count() : 0,
        ]);

        return view('dashboards.student', compact('stats'));
    }
}