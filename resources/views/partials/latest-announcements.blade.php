{{-- The three newest announcements. $latestAnnouncements comes from the View composer in AppServiceProvider --}}
<section class="panel" style="margin-bottom: 18px;">
    <div class="panel-head">
        <div>
            <h3>Latest Announcements</h3>
            <small>The three newest posts</small>
        </div>
        <a href="{{ route('announcements.index') }}" class="btn btn-small btn-light">View all</a>
    </div>

    @forelse ($latestAnnouncements as $item)
        <div style="padding: 10px 0; border-top: 1px solid #e5eaf2;">
            <a href="{{ route('announcements.show', $item) }}"><strong>{{ $item->title }}</strong></a>
            <small style="display:block;">{{ $item->created_at->format('M d, Y') }}</small>
            <p style="font-size: 14px; margin-top: 4px;">{{ Str::limit($item->body, 120) }}</p>
        </div>
    @empty
        <p style="font-size: 14px;">No announcements yet.</p>
    @endforelse
</section>
