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
        // Clear Filters: forget the saved filters, then reload the page
        if ($request->boolean('clear')) {
            session()->forget(['filters.institute_id', 'filters.program_id']);

            return redirect()->route('attendance.index', $event);
        }

        // Use the value from the request. If the request has none, use the one saved in the session
        $instituteId = $request->has('institute_id') ? $request->input('institute_id') : session('filters.institute_id');
        $programId = $request->has('program_id') ? $request->input('program_id') : session('filters.program_id');

        // Save them, so the user returns to the same view
        session(['filters.institute_id' => $instituteId, 'filters.program_id' => $programId]);

        $sessions = $event->sessions()->orderBy('id')->get();

        // with() loads the student, user, program and institute in a few queries, not one per row
        $query = Attendance::with(['session', 'student.user', 'student.program.institute'])
            ->whereIn('event_session_id', $sessions->pluck('id'));

        if ($sessionId = $request->input('session')) {
            $query->where('event_session_id', $sessionId);
        }

        if (filled($instituteId)) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $instituteId));
        }

        if (filled($programId)) {
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

        return view('attendance.index', compact('event', 'sessions', 'attendances', 'institutes', 'programs', 'students', 'instituteId', 'programId'));
    }
}