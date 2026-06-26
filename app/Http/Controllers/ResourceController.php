<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $categorie = $request->get('categorie');
        $query = Resource::actif();

        if ($categorie) {
            $query->where('categorie', $categorie);
        }

        $resources = $query->get();
        $categories = Resource::where('actif', true)
            ->select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie')
            ->filter();

        return view('resources.index', compact('resources', 'categories', 'categorie'));
    }
}
