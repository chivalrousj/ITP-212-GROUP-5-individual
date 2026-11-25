<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Event list with Upcoming and Past tabs and a date search
    public function index(Request $request)
    {
        $tab = $request->input('tab') === 'past' ? 'past' : 'upcoming';
        $today = now()->toDateString();

        // withCount adds attendances_count without loading every attendance row
        $query = Event::withCount('attendances');

        if ($tab === 'past') {
            $query->whereDate('event_date', '<', $today)->orderByDesc('event_date');
        } else {
            $query->whereDate('event_date', '>=', $today)->orderBy('event_date');
        }

        // optional search for one date
        if ($date = $request->input('date')) {
            $query->whereDate('event_date', $date);
        }

        $events = $query->paginate(10)->withQueryString();

        return view('events.index', compact('events', 'tab'));
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
