<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    // List for Officer, Adviser and Student: newest first, 10 per page
    public function index()
    {
        $announcements = Announcement::with('user')->latest()->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('user');

        return view('announcements.show', compact('announcement'));
    }
}
