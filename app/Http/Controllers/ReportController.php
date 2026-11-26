<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    // Pick an event to open its report
    public function index()
    {
        Gate::authorize('view-reports');

        $events = Event::orderByDesc('event_date')->paginate(10);

        return view('reports.index', compact('events'));
    }

    public function show(Event $event)
    {
        Gate::authorize('view-reports');

        // Query Builder: join attendances, sessions, students, programs and institutes.
        // Only this event, and only rows that have a check-in.
        $rows = DB::table('attendances as a')
            ->join('event_sessions as es', 'es.id', '=', 'a.event_session_id')
            ->join('students as s', 's.id', '=', 'a.student_id')
            ->join('programs as p', 'p.id', '=', 's.program_id')
            ->join('institutes as i', 'i.id', '=', 'p.institute_id')
            ->where('es.event_id', $event->id)
            ->whereNotNull('a.check_in_at')
            ->groupBy('i.code', 'p.code')
            ->orderBy('i.code')
            ->orderBy('p.code')
            // DISTINCT: a whole-day student has two attendance rows but is counted once
            ->selectRaw("
                i.code as institute_code,
                p.code as program_code,
                COUNT(DISTINCT a.student_id) as total,
                COUNT(DISTINCT CASE WHEN s.sex = 'Male' THEN a.student_id END) as male,
                COUNT(DISTINCT CASE WHEN s.sex = 'Female' THEN a.student_id END) as female
            ")
            ->get();

        // Support Collection: rows grouped by institute, plus the grand totals
        $byInstitute = $rows->groupBy('institute_code');

        $grand = [
            'total' => $rows->sum('total'),
            'male' => $rows->sum('male'),
            'female' => $rows->sum('female'),
        ];

        // Penalties of this event with their students, for the per-student table and the fine summary
        $penalties = $event->penalties()
            ->with('student.user', 'student.program')
            ->get()
            ->sortBy(fn ($penalty) => $penalty->student->student_no);

                // Step 1: load this event's fines together with each student's program
        $finesWithProgram = $event->penalties()->with('student.program')->get();

        // Step 2: put the fines in a collection and group them by the student's program code
        $fineSummary = collect($finesWithProgram)
            ->groupBy(fn ($penalty) => $penalty->student->program->code)
            // Step 3: turn each group into one row: the program, how many students, and the total fines
            ->map(fn ($group, $programCode) => [
                'program'  => $programCode,
                'students' => $group->pluck('student_id')->unique()->count(),
                'total'    => $group->sum('amount'),
            ])
            // Step 4: biggest total first, then number the rows again from 0
            ->sortByDesc('total')
            ->values();

        // Step 5: add all the program totals together
        $grandTotal = $fineSummary->sum('total');

        return view('reports.show', compact('event', 'byInstitute', 'grand', 'penalties', 'fineSummary', 'grandTotal'));
    }
}
