<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Penalty;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PenaltyController extends Controller
{
    // Count the missed scans of every student and save the fines of one event
    public function generate(Event $event)
    {
        Gate::authorize('manage-ssg');

        // load the sessions together with their attendance rows (2 queries, not one for each session)
        $event->load('sessions.attendances');

        // every session needs a check-in and a check-out, so 2 scans for each session
        $required = $event->sessions->count() * 2;

        // all attendance rows of this event, grouped by student
        $byStudent = $event->sessions->flatMap(fn ($session) => $session->attendances)->groupBy('student_id');

        foreach (Student::pluck('id') as $studentId) {
            $rows = $byStudent->get($studentId, collect());

            // count only the scans that really have a time
            $counted = $rows->whereNotNull('check_in_at')->count()
                     + $rows->whereNotNull('check_out_at')->count();

            $missed = max($required - $counted, 0);

            if ($missed === 0) {
                // the student is complete, so remove an old penalty if there is one
                Penalty::where('event_id', $event->id)->where('student_id', $studentId)->delete();
                continue;
            }

            // updateOrCreate: a second run changes the numbers and keeps sanction and status
            Penalty::updateOrCreate(
                ['event_id' => $event->id, 'student_id' => $studentId],
                ['missed_scans' => $missed, 'amount' => $missed * $event->fine_per_miss]
            );
        }

        Cache::forget('officer.stats');
        Cache::forget('adviser.stats');

        return back()->with('success', 'Fines generated for ' . $event->title . '.');
    }

    // Edit the amount, sanction and status of one penalty
    public function update(Request $request, Penalty $penalty)
    {
        Gate::authorize('manage-ssg');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:99999.99',
            'sanction' => 'nullable|string|max:255',
            'status' => 'required|in:unpaid,paid,served',
        ]);

        $penalty->update($validated);

        Cache::forget('officer.stats');
        Cache::forget('adviser.stats');

        return back()->with('success', 'Penalty updated.');
    }

    public function destroy(Penalty $penalty)
    {
        Gate::authorize('manage-ssg');

        $penalty->delete();

        Cache::forget('officer.stats');
        Cache::forget('adviser.stats');
        
        return back()->with('success', 'Penalty deleted.');
    }
}
