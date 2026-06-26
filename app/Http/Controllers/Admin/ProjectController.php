<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderByDesc('created_at')->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Projet créé avec succès.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request, $project->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Projet supprimé.');
    }

    private function validateProject(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
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
        ]);
    }
}
