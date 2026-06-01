@extends('layouts.admin')

@section('title', 'Ajouter une séance')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.emplois.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-block">
            ← Retour à l'emploi du temps
        </a>
        <h1 class="mt-4 text-3xl font-bold text-slate-900">Ajouter une nouvelle séance</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.emplois.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Matière *</label>
                <select name="matiere_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Sélectionnez une matière</option>
                    @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>{{ $matiere->libelle ?? $matiere->code }}</option>
                    @endforeach
                </select>
                @error('matiere_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Professeur *</label>
                <select name="professeur_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Sélectionnez un professeur</option>
                    @foreach($professeurs as $professeur)
                    <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>{{ $professeur->nom ?? '' }} {{ $professeur->prenom ?? '' }}</option>
                    @endforeach
                </select>
                @error('professeur_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Semestre *</label>
                    <select name="semestre_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionnez un semestre</option>
                        @foreach($semestres as $semestre)
                        <option value="{{ $semestre->id }}" {{ old('semestre_id') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                        @endforeach
                    </select>
                    @error('semestre_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Niveau *</label>
                    <select name="niveau_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionnez un niveau</option>
                        @foreach($niveaux as $niveau)
                        <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                        @endforeach
                    </select>
                    @error('niveau_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Jour *</label>
                    <select name="jour" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionnez un jour</option>
                        @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'] as $jour)
                        <option value="{{ $jour }}" {{ old('jour') == $jour ? 'selected' : '' }}>{{ $jour }}</option>
                        @endforeach
                    </select>
                    @error('jour')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Heure début *</label>
                    <input type="time" name="heure_debut" value="{{ old('heure_debut') }}" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    @error('heure_debut')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Heure fin *</label>
                    <input type="time" name="heure_fin" value="{{ old('heure_fin') }}" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    @error('heure_fin')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Salle *</label>
                <input type="text" name="salle" value="{{ old('salle') }}" placeholder="Ex: B101" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                @error('salle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col gap-4 pt-4 border-t border-slate-200 md:flex-row">
                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-3 text-white font-semibold transition hover:bg-blue-700">Enregistrer</button>
                <a href="{{ route('admin.emplois.index') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-center text-slate-900 transition hover:bg-slate-100">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection