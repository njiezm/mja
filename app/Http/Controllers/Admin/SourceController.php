<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\Contact;
use App\Models\Source;
use App\Models\SourceVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SourceController extends Controller
{
    /** Segments d'URL déjà utilisés par le site : interdits comme slug de source. */
    private const RESERVED = [
        'admin', 'contact', 'adhesion', 'connexion', 'deconnexion', 'a-propos',
        'projets', 'evenements', 'actualites', 'ressources', 'sante-nutrition-sport',
        'mentions-legales', 'politique-de-confidentialite', 'sitemap.xml', 'robots.txt',
        'espace', 'mot-de-passe-oublie', 'reinitialiser-mot-de-passe', 'up', 'storage', 'images',
        'vendor', 'fonts', 'build', 'css', 'js', 'favicon.ico',
    ];

    public function index()
    {
        $sources = Source::orderBy('label')->get();
        $stats = $this->computeStats($sources);

        // Classement : meilleures sources par nombre de visites.
        $ranking = $sources->sortByDesc(fn ($s) => $stats[$s->id]['total'])->take(8)->values();

        // Série 14 jours (toutes sources) pour le mini-graphique.
        $raw = SourceVisit::selectRaw('DATE(created_at) as d, count(*) as c')
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->groupBy('d')->pluck('c', 'd');
        $series = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $series[] = ['label' => $day->isoFormat('DD/MM'), 'count' => (int) ($raw[$day->toDateString()] ?? 0)];
        }

        // Provenance (referer) et appareils — toutes sources confondues.
        $provenance = SourceVisit::selectRaw('referer, count(*) as c')->groupBy('referer')->get()
            ->groupBy(fn ($r) => SourceVisit::refererLabel($r->referer))
            ->map(fn ($g) => (int) $g->sum('c'))->sortDesc();

        $devices = SourceVisit::selectRaw('device, count(*) as c')->groupBy('device')->pluck('c', 'device');

        // Tunnel de conversion global.
        $funnel = [
            'visites'   => (int) array_sum(array_column($stats, 'total')),
            'adhesions' => (int) Adhesion::whereNotNull('source_id')->count(),
            'payees'    => (int) Adhesion::whereNotNull('source_id')->where('statut', 'payee')->count(),
        ];

        $totaux = [
            'sources'     => $sources->count(),
            'visites'     => $funnel['visites'],
            'conversions' => (int) array_sum(array_column($stats, 'conversions')),
        ];

        return view('admin.sources.index', compact('sources', 'stats', 'series', 'totaux', 'provenance', 'devices', 'funnel', 'ranking'));
    }

    /** Statistiques par source (visites, uniques, conversions, taux, courbe 30 j). */
    private function computeStats($sources): array
    {
        $visits = SourceVisit::selectRaw('source_id, count(*) as total, count(distinct visitor_hash) as uniques')
            ->groupBy('source_id')->get()->keyBy('source_id');

        $adhesions = Adhesion::selectRaw('source_id, count(*) as c')->whereNotNull('source_id')
            ->groupBy('source_id')->pluck('c', 'source_id');
        $payees = Adhesion::selectRaw('source_id, count(*) as c')->whereNotNull('source_id')->where('statut', 'payee')
            ->groupBy('source_id')->pluck('c', 'source_id');
        $contacts = Contact::selectRaw('source_id, count(*) as c')->whereNotNull('source_id')
            ->groupBy('source_id')->pluck('c', 'source_id');

        // Courbe 30 jours par source (une seule requête).
        $daily = SourceVisit::selectRaw('source_id, DATE(created_at) as d, count(*) as c')
            ->where('created_at', '>=', Carbon::today()->subDays(29))
            ->groupBy('source_id', 'd')->get()->groupBy('source_id');

        $stats = [];
        foreach ($sources as $s) {
            $total = (int) ($visits[$s->id]->total ?? 0);
            $adh   = (int) ($adhesions[$s->id] ?? 0);
            $ct    = (int) ($contacts[$s->id] ?? 0);
            $conv  = $adh + $ct;

            $byDay = ($daily[$s->id] ?? collect())->keyBy('d');
            $spark = [];
            for ($i = 29; $i >= 0; $i--) {
                $spark[] = (int) ($byDay[Carbon::today()->subDays($i)->toDateString()]->c ?? 0);
            }

            $stats[$s->id] = [
                'total'       => $total,
                'uniques'     => (int) ($visits[$s->id]->uniques ?? 0),
                'adhesions'   => $adh,
                'payees'      => (int) ($payees[$s->id] ?? 0),
                'contacts'    => $ct,
                'conversions' => $conv,
                'taux'        => $total > 0 ? round($conv / $total * 100, 1) : 0.0,
                'spark'       => $spark,
            ];
        }

        return $stats;
    }

    public function export(): StreamedResponse
    {
        $sources = Source::orderBy('label')->get();
        $stats = $this->computeStats($sources);

        return response()->streamDownload(function () use ($sources, $stats) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF"); // BOM UTF-8 (Excel)
            fputcsv($out, ['Source', 'Lien', 'Visites', 'Visiteurs uniques', 'Adhésions', 'Payées', 'Messages', 'Taux de conversion (%)'], ';');
            foreach ($sources as $s) {
                $st = $stats[$s->id];
                fputcsv($out, [$s->label, $s->slug, $st['total'], $st['uniques'], $st['adhesions'], $st['payees'], $st['contacts'], $st['taux']], ';');
            }
            fclose($out);
        }, 'sources-mja-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        $data = $this->validateSource($request);
        Source::create($data);

        return back()->with('success', "Source « {$data['label']} » créée.");
    }

    public function edit(Source $source)
    {
        return view('admin.sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        $data = $this->validateSource($request, $source);
        $source->update($data);

        return redirect()->route('admin.sources.index')->with('success', 'Source mise à jour.');
    }

    public function destroy(Source $source)
    {
        $source->visits()->delete();
        $source->delete();

        return back()->with('success', 'Source supprimée.');
    }

    private function validateSource(Request $request, ?Source $source = null): array
    {
        $data = $request->validate([
            'label'       => 'required|string|max:120',
            'slug'        => [
                'required', 'string', 'max:60', 'regex:/^[A-Za-z0-9._-]+$/',
                Rule::notIn(self::RESERVED),
                Rule::unique('sources', 'slug')->ignore($source?->id),
            ],
            'description' => 'nullable|string|max:200',
            'target'      => 'nullable|string|max:200',
            'is_active'   => 'boolean',
        ], [
            'slug.regex'  => 'Le lien ne peut contenir que lettres, chiffres, points, tirets et underscores.',
            'slug.not_in' => 'Ce lien est réservé par le site, choisissez-en un autre.',
            'slug.unique' => 'Ce lien est déjà utilisé par une autre source.',
        ]);

        $data['slug'] = Str::lower($data['slug']);
        $data['target'] = $data['target'] ?: '/';
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
