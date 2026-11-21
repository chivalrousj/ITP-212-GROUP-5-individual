<?php

namespace App\Http\Controllers;

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

        return view('events.show', compact('event'));
    }
}
