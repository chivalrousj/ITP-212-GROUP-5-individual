<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\EventSession;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request, EventSession $session)
    {
        // The student profile of the logged-in user (a Student account may have none)
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account has no student record.');

        // The server checks again, because a student can send an old form
        abort_unless($session->isCheckInOpen(), 403, 'Check-in is closed.');

        // firstOrCreate: a second click finds the same row and does not make another one
        $attendance = Attendance::firstOrCreate(
            ['event_session_id' => $session->id, 'student_id' => $student->id],
            ['check_in_at' => now()]
        );

        if (!$attendance->wasRecentlyCreated) {
            // a row an Officer added without a check-in time can still be completed
            if ($attendance->check_in_at === null) {
                $attendance->update(['check_in_at' => now()]);

                return back()->with('success', 'Check-in recorded.');
            }

            return back()->with('error', 'You already checked in for this session.');
        }

        return back()->with('success', 'Check-in recorded.');
    }

    public function checkOut(Request $request, EventSession $session)
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account has no student record.');
        abort_unless($session->isCheckOutOpen(), 403, 'Check-out is closed.');

        // Only a row with a check-in and no check-out yet. No row means 404.
        $attendance = Attendance::where('event_session_id', $session->id)
            ->where('student_id', $student->id)
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->firstOrFail();

        $attendance->update(['check_out_at' => now()]);

        return back()->with('success', 'Check-out recorded.');
    }
}
