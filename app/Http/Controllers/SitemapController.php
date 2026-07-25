<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Génère le sitemap XML à partir des pages statiques et des contenus publiés.
     */
    public function index(): Response
    {
        $urls = [];

        // Pages statiques principales
        foreach ([
            ['home', 'weekly', '1.0'],
            ['about', 'monthly', '0.7'],
            ['projects.index', 'weekly', '0.8'],
            ['sns', 'monthly', '0.7'],
            ['events.index', 'weekly', '0.8'],
            ['articles.index', 'weekly', '0.8'],
            ['resources.index', 'monthly', '0.6'],
            ['adhesion', 'yearly', '0.9'],
            ['contact', 'yearly', '0.6'],
        ] as [$route, $freq, $priority]) {
            $urls[] = [
                'loc'        => route($route),
                'changefreq' => $freq,
                'priority'   => $priority,
            ];
        }

        // Contenus dynamiques publiés
        foreach (Article::publie()->get() as $article) {
            $urls[] = [
                'loc'        => route('articles.show', $article->slug),
                'lastmod'    => ($article->updated_at ?? $article->publie_le)?->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.6',
            ];
        }

        foreach (Event::publie()->get() as $event) {
            $urls[] = [
                'loc'        => route('events.show', $event->slug),
                'lastmod'    => $event->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.6',
            ];
        }

        foreach (Project::actif()->get() as $project) {
            $urls[] = [
                'loc'        => route('projects.show', $project->slug),
                'lastmod'    => $project->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.6',
            ];
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
