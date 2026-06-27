<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaire::orderBy('ordre')->orderBy('nom')->get();
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function create()
    {
        $partenaire = new Partenaire;
        return view('admin.partenaires.form', compact('partenaire'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'url'         => 'nullable|url|max:255',
            'description' => 'nullable|string|max:200',
            'ordre'       => 'nullable|integer|min:0|max:999',
            'logo'        => 'nullable|image|max:2048',
        ], [
            'nom.required'  => 'Le nom du partenaire est obligatoire.',
            'url.url'       => 'L\'URL doit être une adresse web valide.',
            'logo.image'    => 'Le logo doit être une image.',
            'logo.max'      => 'Le logo ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partenaires', 'public');
        }

        $data['actif'] = $request->boolean('actif', true);
        $data['ordre'] = $request->integer('ordre', 0);

        Partenaire::create($data);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire ajouté avec succès.');
    }

    public function edit(Partenaire $partenaire)
    {
        return view('admin.partenaires.form', compact('partenaire'));
    }

    public function update(Request $request, Partenaire $partenaire)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'url'         => 'nullable|url|max:255',
            'description' => 'nullable|string|max:200',
            'ordre'       => 'nullable|integer|min:0|max:999',
            'logo'        => 'nullable|image|max:2048',
        ], [
            'nom.required' => 'Le nom du partenaire est obligatoire.',
            'url.url'      => 'L\'URL doit être une adresse web valide.',
            'logo.image'   => 'Le logo doit être une image.',
            'logo.max'     => 'Le logo ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('logo')) {
            if ($partenaire->logo) {
                Storage::disk('public')->delete($partenaire->logo);
            }
            $data['logo'] = $request->file('logo')->store('partenaires', 'public');
        } else {
            unset($data['logo']);
        }

        $data['actif'] = $request->boolean('actif', true);
        $data['ordre'] = $request->integer('ordre', 0);

        $partenaire->update($data);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partenaire $partenaire)
    {
        if ($partenaire->logo) {
            Storage::disk('public')->delete($partenaire->logo);
        }
        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire supprimé.');
    }
}
