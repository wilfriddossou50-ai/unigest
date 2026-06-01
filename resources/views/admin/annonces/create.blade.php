@extends('layouts.admin')

@section('title', 'Ajouter une Annonce')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.annonces.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux annonces
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Ajouter une Annonce</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-4xl">
        <form action="{{ route('admin.annonces.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titre') border-red-500 @enderror">
                @error('titre')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Contenu *</label>
                <textarea name="contenu" rows="6" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contenu') border-red-500 @enderror">{{ old('contenu') }}</textarea>
                @error('contenu')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Type *</label>
                    <select name="type" class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                        <option value="info" {{ old('type', 'info') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="important" {{ old('type') === 'important' ? 'selected' : '' }}>Important</option>
                        <option value="urgent" {{ old('type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Filière ciblée</label>
                    <select name="filiere_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                        <option value="">Toutes les filières</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Niveau ciblé</label>
                    <select name="niveau_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Date de publication</label>
                    <input type="date" name="date_publication" value="{{ old('date_publication') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Date d’expiration</label>
                    <input type="date" name="date_expiration" value="{{ old('date_expiration') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-2.5 w-full">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-slate-700">Annonce active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Publier
                </button>
                <a href="{{ route('admin.annonces.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
