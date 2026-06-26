@extends('layouts.admin')
@section('title', 'Ajouter un membre')
@section('page-title', 'Ajouter un membre')
@section('content')
<div class="max-w-2xl mt-4">
    <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('admin.team._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-mja-blue hover:bg-mja-bluedark text-white font-bold px-8 py-3 rounded-xl transition-colors"><i class="fas fa-save mr-2"></i>Ajouter</button>
            <a href="{{ route('admin.team.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-colors">Annuler</a>
        </div>
    </form>
</div>
@endsection
