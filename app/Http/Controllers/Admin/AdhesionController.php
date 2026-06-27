<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use Illuminate\Http\Request;

class AdhesionController extends Controller
{
    public function index()
    {
        $adhesions = Adhesion::orderByDesc('created_at')->paginate(20);
        $stats = [
            'total'     => Adhesion::count(),
            'nouvelles' => Adhesion::where('statut', 'nouvelle')->count(),
            'acceptees' => Adhesion::where('statut', 'acceptee')->count(),
        ];
        return view('admin.adhesions.index', compact('adhesions', 'stats'));
    }

    public function show(Adhesion $adhesion)
    {
        $adhesion->update(['lu' => true]);
        return view('admin.adhesions.show', compact('adhesion'));
    }

    public function updateStatut(Request $request, Adhesion $adhesion)
    {
        $request->validate(['statut' => 'required|in:nouvelle,traitee,acceptee,refusee']);
        $adhesion->update(['statut' => $request->statut]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Adhesion $adhesion)
    {
        $adhesion->delete();
        return redirect()->route('admin.adhesions.index')->with('success', 'Demande supprimée.');
    }
}
