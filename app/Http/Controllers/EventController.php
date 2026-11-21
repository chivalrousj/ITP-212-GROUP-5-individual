<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;

class EventController extends Controller
{
    // Event list for Officer, Adviser and Student
    public function index()
    {
        // withCount adds attendances_count without loading every attendance row
        $events = Event::withCount('attendances')
            ->orderByDesc('event_date')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load([
            'creator',
            'sessions' => fn ($query) => $query->orderBy('id'),
        ]);

        // Give each session its event, so isCheckInOpen() does not run a new query for every row
        $event->sessions->each->setRelation('event', $event);

        // For a Student: the student profile and the attendance rows of this event
        $student = null;
        $myAttendances = collect();

        if (auth()->user()->hasRole('Student')) {
            $student = auth()->user()->student;

            if ($student) {
                $myAttendances = Attendance::where('student_id', $student->id)
                    ->whereIn('event_session_id', $event->sessions->pluck('id'))
                    ->get()
                    ->keyBy('event_session_id');
            }
        }

        return view('events.show', compact('event', 'student', 'myAttendances'));
    }
}
