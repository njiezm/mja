<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $avenir = Event::avenir()->get();
        $passes = Event::publie()->where('date_debut', '<', now())->orderByDesc('date_debut')->paginate(6);
        return view('events.index', compact('avenir', 'passes'));
    }

    public function show(Event $event)
    {
        abort_if(!$event->publie, 404);
        return view('events.show', compact('event'));
    }
}
