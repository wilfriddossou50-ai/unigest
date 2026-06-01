@extends('layouts.admin')

@section('title', 'Programmer un Cours')

@section('content')
<div class="p-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Programmer un nouveau cours</h1>
        </div>

        @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.programme-cours.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Bloc Jour et Heure --}}
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Jour et Heure</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="jour_semaine" class="block text-sm font-medium text-slate-700 mb-1">Jour *</label>
                        <select name="jour_semaine" id="jour_semaine" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <option value="">-- Choisir --</option>
                            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                            <option value="{{ $jour }}" {{ old('jour_semaine') === $jour ? 'selected' : '' }}>{{ $jour }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_programme" class="block text-sm font-medium text-slate-700 mb-1">Date *</label>
                        <input type="date" name="date_programme" id="date_programme" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" value="{{ old('date_programme', request('date')) }}" required>
                    </div>
                    <div>
                        <label for="creneau_id" class="block text-sm font-medium text-slate-700 mb-1">Créneau *</label>
                        <select name="creneau_id" id="creneau_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <option value="">-- Choisir --</option>
                            @foreach($creneaux as $creneau)
                            <option value="{{ $creneau->id }}" {{ old('creneau_id', request('creneau')) == $creneau->id ? 'selected' : '' }}>{{ $creneau->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bloc Informations Académiques --}}
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Informations Académiques</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="filiere_id" class="block text-sm font-medium text-slate-700 mb-1">Filière</label>
                        <select name="filiere_id" id="filiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                            <option value="">-- Choisir --</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="niveau_id" class="block text-sm font-medium text-slate-700 mb-1">Niveau</label>
                        <select name="niveau_id" id="niveau_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                            <option value="">-- Choisir --</option>
                            @foreach($niveaux as $niveau)
                            <option value="{{ $niveau->id }}">{{ $niveau->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="semestre_id" class="block text-sm font-medium text-slate-700 mb-1">Semestre</label>
                        <select name="semestre_id" id="semestre_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                            <option value="">-- Choisir --</option>
                            @foreach($semestres as $semestre)
                            <option value="{{ $semestre->id }}">{{ $semestre->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="module_id" class="block text-sm font-medium text-slate-700 mb-1">Module</label>
                        <select name="module_id" id="module_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                            <option value="">-- Choisir --</option>
                            @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->libelle ?? $module->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="matiere_id" class="block text-sm font-medium text-slate-700 mb-1">Matière *</label>
                        <select name="matiere_id" id="matiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <option value="">-- Choisir --</option>
                            @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="professeur_id" class="block text-sm font-medium text-slate-700 mb-1">Enseignant *</label>
                        <select name="professeur_id" id="professeur_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <option value="">-- Choisir --</option>
                            @foreach($professeurs as $professeur)
                            <option value="{{ $professeur->id }}">{{ $professeur->nom_complet }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bloc Salle et Remarques --}}
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-4">
                    <label for="salle_id" class="block text-sm font-medium text-slate-700 mb-1">Salle</label>
                    <select name="salle_id" id="salle_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                        <option value="">-- Choisir --</option>
                        @foreach($salles as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom_salle }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Remarques</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.programme-cours.grille') }}" class="text-sm text-slate-600 hover:text-slate-900 underline">Retour</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    Programmer le cours
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
