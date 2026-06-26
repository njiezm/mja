<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Project;
use App\Models\Resource;
use App\Models\TeamMember;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'articles' => Article::count(),
            'projets' => Project::count(),
            'evenements' => Event::count(),
            'ressources' => Resource::count(),
            'membres' => TeamMember::count(),
            'messages' => Contact::count(),
            'messages_non_lus' => Contact::where('lu', false)->count(),
        ];

        $derniers_messages = Contact::orderByDesc('created_at')->limit(5)->get();
        $prochains_events = Event::avenir()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'derniers_messages', 'prochains_events'));
    }
}
