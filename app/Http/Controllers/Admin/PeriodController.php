<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\AdhesionPeriod;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = AdhesionPeriod::withCount('adhesions')->orderByDesc('date_debut')->get();
        $current = AdhesionPeriod::current();

        return view('admin.periods.index', compact('periods', 'current'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePeriod($request);
        $period = AdhesionPeriod::create($data);
        $n = $this->backfill($period);

        return back()->with('success', "Période « {$period->label} » créée." . ($n ? " {$n} adhésion(s) rattachée(s)." : ''));
    }

    public function edit(AdhesionPeriod $period)
    {
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, AdhesionPeriod $period)
    {
        $period->update($this->validatePeriod($request));
        $this->backfill($period);

        return redirect()->route('admin.periods.index')->with('success', 'Période mise à jour.');
    }

    public function destroy(AdhesionPeriod $period)
    {
        $period->delete(); // period_id des adhésions repasse à null (nullOnDelete)

        return back()->with('success', 'Période supprimée.');
    }

    /** Rattache les adhésions sans période dont la date de création tombe dans l'intervalle. */
    private function backfill(AdhesionPeriod $period): int
    {
        return Adhesion::whereNull('period_id')
            ->whereBetween('created_at', [$period->date_debut->startOfDay(), $period->date_fin->endOfDay()])
            ->update(['period_id' => $period->id]);
    }

    private function validatePeriod(Request $request): array
    {
        return $request->validate([
            'label'      => 'required|string|max:120',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
            'actif'      => 'boolean',
        ], [
            'label.required'     => 'Le nom de la période est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure à la date de début.',
        ]) + ['actif' => $request->boolean('actif', true)];
    }
}
