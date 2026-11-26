{{-- Shared by create and edit. $announcement is null on create --}}
@php $a = $announcement ?? null; @endphp

@if ($errors->any())
    <div class="flash flash-error">
        <ul style="margin-left: 18px;">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($a)
        @method('PUT')
    @endif

    <p style="margin-bottom: 12px;">
        <label for="title"><strong>Title</strong></label><br>
        <input type="text" id="title" name="title" value="{{ old('title', $a?->title) }}"
               style="width: 100%; padding: 8px;" required>
    </p>

    <p style="margin-bottom: 12px;">
        <label for="body"><strong>Message</strong></label><br>
        <textarea id="body" name="body" rows="8" style="width: 100%; padding: 8px;" required>{{ old('body', $a?->body) }}</textarea>
    </p>

    <p style="margin-bottom: 12px;">
        <label for="attachment"><strong>Attachment</strong> (optional: PDF, JPG, PNG or WEBP, max 5 MB)</label><br>
        <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp">
        @if ($a?->attachment)
            <br><small>Current file:
                <a href="{{ asset('storage/' . $a->attachment) }}" target="_blank">view</a>.
                Choose a new file only if you want to replace it.</small>
        @endif
    </p>

    <button type="submit" class="btn">{{ $button }}</button>
    <a href="{{ route('announcements.index') }}" class="btn btn-light">Cancel</a>
</form>
