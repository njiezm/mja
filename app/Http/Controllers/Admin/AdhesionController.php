<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdhesionStatusUpdate;
use App\Models\Adhesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdhesionController extends Controller
{
    public function index(Request $request)
    {
        $query = Adhesion::with('period')->orderByDesc('created_at');
        if ($request->filled('period')) {
            $query->where('period_id', $request->integer('period'));
        }
        $adhesions = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Adhesion::count(),
            'nouvelles' => Adhesion::where('statut', 'nouvelle')->count(),
            'adherents' => Adhesion::where('statut', 'payee')->count(),
        ];
        $periods = \App\Models\AdhesionPeriod::orderByDesc('date_debut')->get();

        return view('admin.adhesions.index', compact('adhesions', 'stats', 'periods'));
    }

    public function show(Adhesion $adhesion)
    {
        $adhesion->update(['lu' => true]);
        return view('admin.adhesions.show', compact('adhesion'));
    }

    public function updateStatut(Request $request, Adhesion $adhesion)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Adhesion::STATUTS))],
        ]);

        $ancien = $adhesion->statut;
        $adhesion->update(['statut' => $validated['statut']]);

        // Devient adhérent : préparer le lien de création de compte.
        if ($validated['statut'] === 'payee') {
            $adhesion->ensureAccountToken();
        }

        // Email personnalisé uniquement si le statut change vers un état « notifiable ».
        $notifiables = ['payee', 'en_attente_paiement', 'refusee'];
        if ($validated['statut'] !== $ancien && in_array($validated['statut'], $notifiables, true)) {
            try {
                Mail::to($adhesion->email)->send(new AdhesionStatusUpdate($adhesion));
            } catch (\Throwable $e) {
                Log::error('Mail statut adhésion échoué : ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Adhesion $adhesion)
    {
        if ($adhesion->photo) {
            Storage::disk('public')->delete($adhesion->photo);
        }
        $adhesion->delete();
        return redirect()->route('admin.adhesions.index')->with('success', 'Demande supprimée.');
    }
}
