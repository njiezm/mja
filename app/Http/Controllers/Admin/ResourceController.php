<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('ordre')->paginate(15);
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateResource($request);

        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('resources', 'public');
        }

        Resource::create($validated);
        return redirect()->route('admin.resources.index')->with('success', 'Ressource créée avec succès.');
    }

    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $this->validateResource($request);

        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('resources', 'public');
        }

        $resource->update($validated);
        return redirect()->route('admin.resources.index')->with('success', 'Ressource mise à jour.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('success', 'Ressource supprimée.');
    }

    private function validateResource(Request $request): array
    {
        return $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|max:51200',
            'lien_externe' => 'nullable|url',
            'type' => 'required|in:document,pdf,guide,lien,video,audio,podcast,infographie',
            'categorie' => 'nullable|string|max:100',
            'actif' => 'boolean',
            'ordre' => 'integer|min:0',
        ]);
    }
}
