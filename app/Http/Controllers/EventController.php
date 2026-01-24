<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'location'    => 'nullable|string|max:255',
            'is_published'=> 'boolean',
        ]);

        Event::create($data);

        return redirect()->route('portal.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('portal.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'location'    => 'nullable|string|max:255',
            'is_published'=> 'boolean',
        ]);

        $event->update($data);

        return redirect()->route('portal.events.index')
            ->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return back()->with('success', 'Event deleted.');
    }
}
