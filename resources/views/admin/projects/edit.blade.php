@extends('layouts.admin')
@section('title', 'Modifier le projet')
@section('page-title', 'Modifier le projet')
@section('content')
<div class="max-w-3xl mt-4">
    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        @include('admin.projects._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-mja-blue hover:bg-mja-bluedark text-white font-bold px-8 py-3 rounded-xl transition-colors"><i class="fas fa-save mr-2"></i>Enregistrer</button>
            <a href="{{ route('admin.projects.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-colors">Annuler</a>
        </div>
    </form>
</div>
@endsection
