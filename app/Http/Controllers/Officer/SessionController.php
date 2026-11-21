<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    // Open or close check-in or check-out of a MANUAL event session
    public function toggle(Request $request, EventSession $session)
    {
        Gate::authorize('manage-ssg');

        $validated = $request->validate([
            'type' => 'required|in:check_in,check_out',
        ]);

        // Scheduled events open and close by the clock, so they cannot be toggled
        abort_unless($session->event->availability === 'manual', 403);

        $column = $validated['type'] . '_open';

        $session->$column = !$session->$column;
        $session->save();

        $label = $validated['type'] === 'check_in' ? 'Check-in' : 'Check-out';
        $state = $session->$column ? 'opened' : 'closed';
        $period = ucfirst(str_replace('_', ' ', $session->period));

        return back()->with('success', "{$label} {$state} for the {$period} session.");
    }
}
