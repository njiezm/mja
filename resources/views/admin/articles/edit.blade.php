@extends('layouts.admin')
@section('title', 'Modifier l\'article')
@section('page-title', 'Modifier l\'article')

@section('content')
<div class="max-w-3xl mt-4">
    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        @include('admin.articles._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-mja-blue hover:bg-mja-bluedark text-white font-bold px-8 py-3 rounded-xl transition-colors">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.articles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-colors">Annuler</a>
            @if($article->publie)
            <a href="{{ route('articles.show', $article) }}" target="_blank" class="ml-auto text-sm text-mja-blue font-semibold flex items-center gap-1 hover:text-mja-dark">
                <i class="fas fa-external-link-alt"></i> Voir en ligne
            </a>
            @endif
        </div>
    </form>
</div>
@endsection
