<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Attendance records of one event, for Officer and Adviser
    public function index(Request $request, Event $event)
    {
        $sessions = $event->sessions()->orderBy('id')->get();

        // with() loads the student, user, program and institute in a few queries, not one per row
        $query = Attendance::with(['session', 'student.user', 'student.program.institute'])
            ->whereIn('event_session_id', $sessions->pluck('id'));

        if ($sessionId = $request->input('session')) {
            $query->where('event_session_id', $sessionId);
        }

        if ($instituteId = $request->input('institute')) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $instituteId));
        }

        if ($programId = $request->input('program')) {
            $query->whereHas('student', fn ($q) => $q->where('program_id', $programId));
        }

        $attendances = $query
            ->orderBy('event_session_id')
            ->orderBy('check_in_at')
            ->paginate(20)
            ->withQueryString();

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        // The student list is only needed for the Officer's "add a record" form
        $students = auth()->user()->hasRole('Officer')
            ? Student::with('user')->orderBy('student_no')->get()
            : collect();

        return view('attendance.index', compact('event', 'sessions', 'attendances', 'institutes', 'programs', 'students'));
    }
}
