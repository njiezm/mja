<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderByDesc('created_at')->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create', ['evenements' => $this->evenementsSelectionnables()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($validated);
        $this->synchroniserEvenements($project, $request->input('evenements', []));

        return redirect()->route('admin.projects.index')->with('success', 'Projet créé avec succès.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', [
            'project'    => $project,
            'evenements' => $this->evenementsSelectionnables(),
        ]);
    }

    /**
     * Événements proposables au rattachement : ceux du projet en cours et
     * ceux qui ne sont rattachés à aucun projet. Les événements déjà pris
     * par un autre projet n'apparaissent pas — un événement n'appartient
     * qu'à un seul projet.
     */
    private function evenementsSelectionnables()
    {
        return Event::orderByDesc('date_debut')->get(['id', 'titre', 'date_debut', 'project_id', 'publie']);
    }

    /**
     * Rattache au projet les événements cochés, et détache ceux qui ne le
     * sont plus. Un projet accepte de zéro à N événements.
     */
    private function synchroniserEvenements(Project $project, array $ids): void
    {
        Event::where('project_id', $project->id)
            ->when($ids, fn ($q) => $q->whereNotIn('id', $ids))
            ->update(['project_id' => null]);

        if ($ids) {
            Event::whereIn('id', $ids)->update(['project_id' => $project->id]);
        }
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request, $project->id);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($validated);
        $this->synchroniserEvenements($project, $request->input('evenements', []));

        return redirect()->route('admin.projects.index')->with('success', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Projet supprimé.');
    }

    private function validateProject(Request $request, ?int $ignoreId = null): array
    {
        $donnees = $request->validate([
            'titre'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:projects,slug' . ($ignoreId ? ",{$ignoreId}" : ''),
            'description_courte'=> 'nullable|string|max:300',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:10240',
            'statut'            => 'required|in:en_cours,termine,a_venir',
            'date_debut'        => 'nullable|date',
            'date_fin'          => 'nullable|date',
            'actif'             => 'boolean',
            'ordre'             => 'integer|min:0',
            'evenements'        => 'nullable|array',
            'evenements.*'      => 'integer|exists:events,id',
        ]);

        // `evenements` ne fait pas partie des colonnes du projet : le
        // rattachement se joue côté événement, via sa clé project_id.
        unset($donnees['evenements']);

        return $donnees;
    }
}
