@extends('layouts.admin')

@section('title', 'Modifier le Cours')

@section('content')
<div class="p-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Modifier le cours</h1>
            <p class="text-slate-600">{{ $programme->matiere->libelle }}</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.programme-cours.update', $programme) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Jour et Heure</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="jour_semaine" class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                        <select name="jour_semaine" id="jour_semaine" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                            <option value="{{ $jour }}" {{ old('jour_semaine', $programme->jour_semaine) === $jour ? 'selected' : '' }}>{{ $jour }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_programme" class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                        <input type="date" name="date_programme" id="date_programme" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('date_programme', $programme->date_programme->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="creneau_id" class="block text-sm font-medium text-slate-700 mb-1">Créneau</label>
                        <select name="creneau_id" id="creneau_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach($creneaux as $creneau)
                            <option value="{{ $creneau->id }}" {{ old('creneau_id', $programme->creneau_id) == $creneau->id ? 'selected' : '' }}>{{ $creneau->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Informations Académiques</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="filiere_id" class="block text-sm font-medium text-slate-700 mb-1">Filière</label>
                        <select name="filiere_id" id="filiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Choisir --</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id', $programme->filiere_id) == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="matiere_id" class="block text-sm font-medium text-slate-700 mb-1">Matière</label>
                        <select name="matiere_id" id="matiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ old('matiere_id', $programme->matiere_id) == $matiere->id ? 'selected' : '' }}>{{ $matiere->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Localisation et Enseignant</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="professeur_id" class="block text-sm font-medium text-slate-700 mb-1">Enseignant</label>
                        <select name="professeur_id" id="professeur_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach($professeurs as $professeur)
                            <option value="{{ $professeur->id }}" {{ old('professeur_id', $programme->professeur_id) == $professeur->id ? 'selected' : '' }}>{{ $professeur->nom_complet }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="salle_id" class="block text-sm font-medium text-slate-700 mb-1">Salle</label>
                        <select name="salle_id" id="salle_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Non attribuée --</option>
                            @foreach($salles as $salle)
                            <option value="{{ $salle->id }}" {{ old('salle_id', $programme->salle_id) == $salle->id ? 'selected' : '' }}>{{ $salle->nom_salle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Remarques</label>
                <textarea name="notes" id="notes" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $programme->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.programme-cours.grille', ['date_debut' => $programme->date_programme->format('Y-m-d')]) }}"
                    class="text-sm text-slate-600 hover:text-slate-900 underline">
                    Retour à la grille
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection