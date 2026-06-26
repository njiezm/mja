<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categorie = $request->get('categorie');
        $query = Article::publie();

        if ($categorie) {
            $query->where('categorie', $categorie);
        }

        $articles = $query->paginate(9);
        return view('articles.index', compact('articles', 'categorie'));
    }

    public function show(Article $article)
    {
        abort_if(!$article->publie, 404);
        $recents = Article::publie()->where('id', '!=', $article->id)->limit(3)->get();
        return view('articles.show', compact('article', 'recents'));
    }
}
