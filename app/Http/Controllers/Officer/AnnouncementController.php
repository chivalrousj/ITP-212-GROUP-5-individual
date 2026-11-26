<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    // Same rules for create and update. The file is optional: PDF or image, up to 5 MB
    private function rules(): array
    {
        return [
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ];
    }

    // File name = slug of the title + a short unique value, saved on the public disk
    private function storeFile(Request $request, string $title): string
    {
        $file = $request->file('attachment');
        $name = (Str::slug($title) ?: 'announcement') . '-' . Str::lower(Str::random(6)) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('announcements', $name, 'public');
    }

    public function create()
    {
        return view('officer.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $this->storeFile($request, $data['title']);
        }

        // The author is always the logged-in user
        $data['user_id'] = $request->user()->id;

        // forceFill, so this works even if $fillable in the model is incomplete
        (new Announcement())->forceFill($data)->save();

        // The Officer dashboard shows an announcement count
        Cache::forget('officer.stats');

        return redirect()->route('announcements.index')->with('success', 'Announcement posted.');
    }

    public function edit(Announcement $announcement)
    {
        return view('officer.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('attachment')) {
            // Remove the replaced file, then save the new one
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }

            $data['attachment'] = $this->storeFile($request, $data['title']);
        } else {
            // No new file: keep the old one
            unset($data['attachment']);
        }

        $announcement->forceFill($data)->save();

        return redirect()->route('announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        Cache::forget('officer.stats');

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
    }
}
