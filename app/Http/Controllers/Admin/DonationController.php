<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::orderByDesc('created_at')->paginate(20);
        $stats = [
            'total'   => Donation::where('statut', 'paye')->count(),
            'montant' => (float) Donation::where('statut', 'paye')->sum('montant'),
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return back()->with('success', 'Don supprimé.');
    }
}
