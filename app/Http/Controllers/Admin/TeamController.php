<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('ordre')->paginate(20);
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateMember($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($validated);
        return redirect()->route('admin.team.index')->with('success', 'Membre ajouté avec succès.');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.edit', compact('team'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $this->validateMember($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $team->update($validated);
        return redirect()->route('admin.team.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(TeamMember $team)
    {
        $team->delete();
        return redirect()->route('admin.team.index')->with('success', 'Membre supprimé.');
    }

    private function validateMember(Request $request): array
    {
        return $request->validate([
            'prenom'  => 'required|string|max:100',
            'nom'     => 'nullable|string|max:100',
            'role'    => 'required|string|max:150',
            'bio'     => 'nullable|string',
            'photo'   => 'nullable|image|max:10240',
            'actif'   => 'boolean',
            'ordre'   => 'integer|min:0',
        ]);
    }
}
