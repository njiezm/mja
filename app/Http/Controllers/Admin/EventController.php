<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('date_debut')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);
        return redirect()->route('admin.events.index')->with('success', 'Événement créé avec succès.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $this->validateEvent($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);
        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'titre'             => 'required|string|max:255',
            'description_courte'=> 'nullable|string|max:300',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:10240',
            'date_debut'        => 'required|date',
            'date_fin'          => 'nullable|date|after_or_equal:date_debut',
            'lieu'              => 'nullable|string|max:150',
            'adresse'           => 'nullable|string|max:255',
            'gratuit'           => 'boolean',
            'lien_inscription'  => 'nullable|url',
            'publie'            => 'boolean',
        ]);
    }
}
