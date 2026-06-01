@extends('layouts.admin')

@section('title', 'Éditer un Niveau')

@section('content')
<div class="p-8 max-w-2xl">
    <a href="{{ route('admin.niveaux.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-4 inline-block">← Retour</a>
    <h1 class="text-3xl font-bold text-slate-900 mb-8">Éditer Niveau: {{ $niveau->libelle }}</h1>

    <div class="bg-white rounded-xl shadow-sm p-8">
        <form action="{{ route('admin.niveaux.update', $niveau) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Code niveau *</label>
                <input type="text" name="code" value="{{ old('code', $niveau->code) }}" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                    placeholder="Ex: L1">
                @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Libellé *</label>
                <input type="text" name="libelle" value="{{ old('libelle', $niveau->libelle) }}" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('libelle') border-red-500 @enderror"
                    placeholder="Ex: Licence 1 Informatique">
                @error('libelle')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700">Mettre à jour</button>
                <a href="{{ route('admin.niveaux.index') }}" class="flex-1 bg-slate-200 text-slate-900 font-medium py-2 rounded-lg text-center hover:bg-slate-300">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection