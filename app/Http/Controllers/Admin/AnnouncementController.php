<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class AnnouncementController extends Controller
{
    /**
     * Show the announcement editor.
     */
    public function index()
    {
        $announcement = SystemSetting::get('library_announcement', '');
        return view('admin.announcements.index', compact('announcement'));
    }

    /**
     * Store the announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'announcement' => 'nullable|string|max:2000',
        ]);

        SystemSetting::set('library_announcement', $validated['announcement'] ?? '', 'string', 'Library announcement');

        return redirect()->route('admin.announcements.index')->with('status', 'Announcement saved.');
    }
}

