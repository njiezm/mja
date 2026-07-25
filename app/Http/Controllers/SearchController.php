<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\Project;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $results = collect();

        if (Str::length($q) >= 2) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

            $articles = Article::publie()
                ->where(fn ($w) => $w->where('titre', 'like', $like)
                    ->orWhere('extrait', 'like', $like)
                    ->orWhere('contenu', 'like', $like))
                ->limit(20)->get()
                ->map(fn ($a) => [
                    'type'  => 'Actualité',
                    'icon'  => 'fa-newspaper',
                    'titre' => $a->titre,
                    'extrait' => $a->extrait,
                    'url'   => route('articles.show', $a->slug),
                ]);

            $projects = Project::actif()
                ->where(fn ($w) => $w->where('titre', 'like', $like)
                    ->orWhere('description_courte', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->limit(20)->get()
                ->map(fn ($p) => [
                    'type'  => 'Projet',
                    'icon'  => 'fa-diagram-project',
                    'titre' => $p->titre,
                    'extrait' => $p->description_courte,
                    'url'   => route('projects.show', $p->slug),
                ]);

            $events = Event::publie()
                ->where(fn ($w) => $w->where('titre', 'like', $like)
                    ->orWhere('description_courte', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->limit(20)->get()
                ->map(fn ($e) => [
                    'type'  => 'Événement',
                    'icon'  => 'fa-calendar-day',
                    'titre' => $e->titre,
                    'extrait' => $e->description_courte,
                    'url'   => route('events.show', $e->slug),
                ]);

            $resources = Resource::actif()
                ->where(fn ($w) => $w->where('titre', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->limit(20)->get()
                ->map(fn ($r) => [
                    'type'  => 'Ressource',
                    'icon'  => 'fa-folder-open',
                    'titre' => $r->titre,
                    'extrait' => $r->description,
                    'url'   => $r->lien_externe
                        ?: ($r->fichier ? \Illuminate\Support\Facades\Storage::url($r->fichier) : route('resources.index')),
                ]);

            $results = $articles->concat($projects)->concat($events)->concat($resources);
        }

        return view('search', compact('q', 'results'));
    }
}
