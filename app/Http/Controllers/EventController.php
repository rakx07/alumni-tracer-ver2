<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /* ================= PUBLIC ================= */

    public function public()
    {
        $events = Event::where('is_published', true)
            ->orderBy('start_date')
            ->get();

        return view('events.calendar', compact('events'));
    }

    /* ================= ADMIN ================= */

    public function index()
    {
        $events = Event::orderBy('start_date', 'desc')->paginate(10);
        return view('portal.events.index', compact('events'));
    }

    public function create()
    {
        return view('portal.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'type' => ['nullable','string','max:80'],
            'organizer' => ['nullable','string','max:255'],
            'target_group' => ['nullable','string','max:255'],
            'audience' => ['nullable','string','max:100'],

            'description' => ['nullable','string'],

            // ✅ REQUIRED by DB
            'start_date' => ['required','date'],
            'end_date'   => ['nullable','date','after_or_equal:start_date'],

            // ✅ your column name is location (not venue)
            'location' => ['nullable','string','max:255'],

            // ✅ optional link (only validate if filled)
            'registration_link' => ['nullable','sometimes','url','max:255'],
            'contact_email' => ['nullable','email','max:255'],

            'poster' => ['nullable','image','max:4096'], // 4MB
            'is_published' => ['nullable','boolean'],
        ]);

        $data['is_published'] = (bool)($data['is_published'] ?? false);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

        Event::create($data);

        return redirect()->route('portal.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('portal.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'type' => ['nullable','string','max:80'],
            'organizer' => ['nullable','string','max:255'],
            'target_group' => ['nullable','string','max:255'],
            'audience' => ['nullable','string','max:100'],

            'description' => ['nullable','string'],

            'start_date' => ['required','date'],
            'end_date'   => ['nullable','date','after_or_equal:start_date'],

            'location' => ['nullable','string','max:255'],

            'registration_link' => ['nullable','sometimes','url','max:255'],
            'contact_email' => ['nullable','email','max:255'],

            'poster' => ['nullable','image','max:4096'],
            'is_published' => ['nullable','boolean'],
        ]);

        $data['is_published'] = (bool)($data['is_published'] ?? false);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

        $event->update($data);

        return redirect()->route('portal.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return back()->with('success', 'Event deleted.');
    }
}
