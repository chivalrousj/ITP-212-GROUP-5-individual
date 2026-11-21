{{-- Shared form fields for create and edit. $event is null when creating. --}}
@php
    $sessions = $event ? $event->sessions->keyBy('period') : collect();

    $periods = ['morning' => 'Morning', 'afternoon' => 'Afternoon', 'half_day' => 'Half day'];
    $fields = [
        'check_in_start' => 'Check-in start',
        'check_in_end' => 'Check-in end',
        'check_out_start' => 'Check-out start',
        'check_out_end' => 'Check-out end',
    ];

    // A saved time like 09:00:00 is shown as 09:00. old() wins after a failed save.
    $value = function ($period, $field) use ($sessions) {
        $saved = $sessions->get($period);
        $time = ($saved && $saved->$field) ? substr($saved->$field, 0, 5) : '';
        return old("sessions.$period.$field", $time);
    };
@endphp

<style>
    .user-form input[type="date"], .user-form input[type="time"], .user-form input[type="number"], .user-form textarea {
        width: 100%; padding: 10px 12px; border: 1px solid #d5e3ed; border-radius: 8px;
        font-size: 14px; font-family: inherit; color: #17324d; background: white;
    }
    .session-block { border: 1px solid #d5e3ed; border-radius: 10px; padding: 14px; margin-bottom: 14px; }
    .session-block h4 { margin-bottom: 10px; color: #103b60; font-size: 14px; }
    .time-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
</style>

@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $event?->title) }}">
</div>

<div class="form-group">
    <label for="description">Description (optional)</label>
    <textarea id="description" name="description" rows="3">{{ old('description', $event?->description) }}</textarea>
</div>

<div class="form-group">
    <label for="venue">Venue</label>
    <input type="text" id="venue" name="venue" value="{{ old('venue', $event?->venue) }}">
</div>

<div class="form-group">
    <label for="event_date">Event Date</label>
    <input type="date" id="event_date" name="event_date" value="{{ old('event_date', $event?->event_date?->format('Y-m-d')) }}">
</div>

<div class="form-group">
    <label for="day_type">Day Type</label>
    <select id="day_type" name="day_type">
        <option value="whole_day" {{ old('day_type', $event?->day_type ?? 'whole_day') === 'whole_day' ? 'selected' : '' }}>Whole day (morning and afternoon)</option>
        <option value="half_day" {{ old('day_type', $event?->day_type) === 'half_day' ? 'selected' : '' }}>Half day (one session)</option>
    </select>
</div>

<div class="form-group">
    <label for="availability">Availability</label>
    <select id="availability" name="availability">
        <option value="scheduled" {{ old('availability', $event?->availability ?? 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled (opens by the time windows)</option>
        <option value="manual" {{ old('availability', $event?->availability) === 'manual' ? 'selected' : '' }}>Manual (the Officer opens and closes it)</option>
    </select>
</div>

<div class="form-group">
    <label for="fine_per_miss">Fine per missed scan (PHP)</label>
    <input type="number" id="fine_per_miss" name="fine_per_miss" step="0.01" min="0" value="{{ old('fine_per_miss', $event?->fine_per_miss ?? '0.00') }}">
</div>

{{-- Session times: the blocks shown depend on the day type and the availability --}}
@foreach ($periods as $key => $label)
    <div class="session-block" id="session-{{ $key }}" data-day="{{ $key === 'half_day' ? 'half_day' : 'whole_day' }}">
        <h4>{{ $label }} session</h4>
        <div class="time-grid">
            @foreach ($fields as $field => $fieldLabel)
                <div class="form-group">
                    <label for="{{ $key }}_{{ $field }}">{{ $fieldLabel }}</label>
                    <input type="time" id="{{ $key }}_{{ $field }}" name="sessions[{{ $key }}][{{ $field }}]" value="{{ $value($key, $field) }}">
                </div>
            @endforeach
        </div>
    </div>
@endforeach

<script>
    (function () {
        const dayType = document.getElementById('day_type');
        const availability = document.getElementById('availability');
        const blocks = document.querySelectorAll('.session-block');

        function refresh() {
            blocks.forEach(function (block) {
                // show a block only for scheduled events of the matching day type
                const show = availability.value === 'scheduled' && block.dataset.day === dayType.value;
                block.style.display = show ? 'block' : 'none';

                // hidden inputs are disabled, so they are not sent with the form
                block.querySelectorAll('input').forEach(function (input) {
                    input.disabled = !show;
                });
            });
        }

        dayType.addEventListener('change', refresh);
        availability.addEventListener('change', refresh);
        refresh();
    })();
</script>
