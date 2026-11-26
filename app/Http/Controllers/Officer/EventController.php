<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function create()
    {
        return view('officer.events.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);

        // The event and its sessions are saved together, or not at all
        DB::transaction(function () use ($validated) {
            $event = Event::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'venue' => $validated['venue'],
                'event_date' => $validated['event_date'],
                'day_type' => $validated['day_type'],
                'availability' => $validated['availability'],
                'fine_per_miss' => $validated['fine_per_miss'],
                'created_by' => auth()->id(),
            ]);

            // two sessions for a whole day, one session for a half day
            foreach ($this->periodsFor($validated['day_type']) as $period) {
                $event->sessions()->create(
                    ['period' => $period] + $this->sessionTimes($validated, $period)
                );
            }
        });

        $this->clearDashboardCache(); return redirect()->route('events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event)
    {
        Gate::authorize('update', $event);

        $event->load('sessions');

        return view('officer.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $validated = $this->validateEvent($request);

        $dayTypeChanged = $validated['day_type'] !== $event->day_type;

        // Changing the day type rebuilds the sessions, which would erase attendance
        if ($dayTypeChanged && $event->attendances()->exists()) {
            return back()->withInput()->withErrors([
                'day_type' => 'The day type cannot be changed because this event already has attendance.',
            ]);
        }

        DB::transaction(function () use ($event, $validated, $dayTypeChanged) {
            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'venue' => $validated['venue'],
                'event_date' => $validated['event_date'],
                'day_type' => $validated['day_type'],
                'availability' => $validated['availability'],
                'fine_per_miss' => $validated['fine_per_miss'],
            ]);

            if ($dayTypeChanged) {
                // safe here: the check above proved there is no attendance
                $event->sessions()->delete();

                foreach ($this->periodsFor($validated['day_type']) as $period) {
                    $event->sessions()->create(
                        ['period' => $period] + $this->sessionTimes($validated, $period)
                    );
                }
            } else {
                // same day type: keep the session rows (and their attendance), change only the times
                foreach ($this->periodsFor($validated['day_type']) as $period) {
                    $event->sessions()->updateOrCreate(
                        ['period' => $period],
                        $this->sessionTimes($validated, $period)
                    );
                }
            }
        });

        $this->clearDashboardCache(); return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        // EventPolicy: only an Officer, and only when the event has no attendance
        Gate::authorize('delete', $event);

        $event->delete();

        $this->clearDashboardCache(); return redirect()->route('events.index')->with('success', 'Event deleted.');
    }

    // Which session periods an event needs
    private function periodsFor(string $dayType): array
    {
        return $dayType === 'whole_day' ? ['morning', 'afternoon'] : ['half_day'];
    }

    // The four time columns of one session. Manual events do not use times.
    private function sessionTimes(array $validated, string $period): array
    {
        $times = $validated['availability'] === 'scheduled'
            ? ($validated['sessions'][$period] ?? [])
            : [];

        return [
            'check_in_start' => $times['check_in_start'] ?? null,
            'check_in_end' => $times['check_in_end'] ?? null,
            'check_out_start' => $times['check_out_start'] ?? null,
            'check_out_end' => $times['check_out_end'] ?? null,
        ];
    }

    // Shared validation for store() and update()
    private function validateEvent(Request $request): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'event_date' => 'required|date',
            'day_type' => 'required|in:whole_day,half_day',
            'availability' => 'required|in:scheduled,manual',
            'fine_per_miss' => 'required|numeric|min:0|max:99999.99',
        ];

        $attributes = [];

        // Times are required only for scheduled events
        if ($request->input('availability') === 'scheduled') {
            $periods = $request->input('day_type') === 'whole_day'
                ? ['morning', 'afternoon']
                : ['half_day'];

            foreach ($periods as $period) {
                $label = str_replace('_', ' ', $period);

                $rules["sessions.$period.check_in_start"] = 'required|date_format:H:i';
                // each end time must be after its start time
                $rules["sessions.$period.check_in_end"] = "required|date_format:H:i|after:sessions.$period.check_in_start";
                $rules["sessions.$period.check_out_start"] = 'required|date_format:H:i';
                $rules["sessions.$period.check_out_end"] = "required|date_format:H:i|after:sessions.$period.check_out_start";

                $attributes["sessions.$period.check_in_start"] = "$label check-in start time";
                $attributes["sessions.$period.check_in_end"] = "$label check-in end time";
                $attributes["sessions.$period.check_out_start"] = "$label check-out start time";
                $attributes["sessions.$period.check_out_end"] = "$label check-out end time";
            }
        }

        return $request->validate($rules, [], $attributes);
    }

    // Event changes affect the dashboard counts, so the cached numbers must go
    private function clearDashboardCache(): void
    {
        Cache::forget('officer.stats');
        Cache::forget('adviser.stats');

        // each student has their own cached numbers
        User::whereHas('student')->pluck('id')->each(fn ($id) => Cache::forget('student.stats.' . $id));
    }
}
