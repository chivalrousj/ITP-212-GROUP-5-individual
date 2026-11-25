<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    // Fines and sanctions of one event, for Officer and Adviser
    public function index(Request $request, Event $event)
    {
        $query = Penalty::with(['student.user', 'student.program.institute'])
            ->where('event_id', $event->id);

        if ($instituteId = $request->input('institute')) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $instituteId));
        }

        if ($programId = $request->input('program')) {
            $query->whereHas('student', fn ($q) => $q->where('program_id', $programId));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // the total of ALL filtered rows, not only the rows of this page
        $total = (clone $query)->sum('amount');

        $penalties = $query->orderBy('id')->paginate(20)->withQueryString();

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        return view('penalties.index', compact('event', 'penalties', 'total', 'institutes', 'programs'));
    }
}
