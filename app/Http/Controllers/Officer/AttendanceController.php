<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    // Add a missing attendance record
    public function store(Request $request, Event $event)
    {
        Gate::authorize('manage-ssg');

        $validated = $request->validate([
            'event_session_id' => [
                'required',
                Rule::exists('event_sessions', 'id')
                    ->where('event_id', $event->id),
            ],
            'student_id' => 'required|exists:students,id',
            'check_in_at' => 'nullable|date|required_without:check_out_at',
            'check_out_at' => 'nullable|date|after_or_equal:check_in_at',
        ]);

        // Prevent duplicate records for the same student and session
        $alreadyThere = Attendance::where(
            'event_session_id',
            $validated['event_session_id']
        )
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($alreadyThere) {
            return back()->withInput()->withErrors([
                'student_id' => 'This student already has a record for that session. Edit it instead.',
            ]);
        }

        Attendance::create($validated);

        return back()->with('success', 'Attendance record added.');
    }

    // Correct the times of an attendance record
    public function update(Request $request, Attendance $attendance)
    {
        Gate::authorize('manage-ssg');

        $validated = $request->validate([
            'check_in_at' => 'nullable|date|required_without:check_out_at',
            'check_out_at' => 'nullable|date|after_or_equal:check_in_at',
        ]);

        $attendance->update($validated);

        return back()->with('success', 'Attendance record updated.');
    }

    // Delete an attendance record
    public function destroy(Attendance $attendance)
    {
        Gate::authorize('manage-ssg');

        $attendance->delete();

        return back()->with('success', 'Attendance record deleted.');
    }
}
