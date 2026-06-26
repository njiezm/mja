<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderByDesc('created_at')->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateArticle($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($validated['publie'] && !isset($validated['publie_le'])) {
            $validated['publie_le'] = now();
        }

        Article::create($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $this->validateArticle($request, $article->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($validated['publie'] && !$article->publie_le) {
            $validated['publie_le'] = now();
        }

        $article->update($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé.');
    }

    private function validateArticle(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'titre'     => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:articles,slug' . ($ignoreId ? ",{$ignoreId}" : ''),
            'extrait'   => 'nullable|string|max:500',
            'contenu'   => 'required|string',
            'image'     => 'nullable|image|max:10240',
            'categorie' => 'required|string',
            'auteur'    => 'nullable|string|max:100',
            'publie'    => 'boolean',
        ]);
    }
}
