@extends('layouts.admin')

@section('title', 'Ajouter une Note')

@section('content')
<div class="p-8" x-data="{ noteType: '{{ request('type', 'cc') }}' }">
    <div class="mb-8">
        <a href="{{ route('admin.notes.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux notes
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Saisie des Notes</h1>
        <p class="text-slate-600 mt-1">Sélectionnez le type d'évaluation à saisir.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        @if($etudiants->isEmpty() || $matieres->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Ajoutez d'abord au moins un étudiant actif et une matière avant d'enregistrer une note.
        </div>
        @else
        <form action="{{ route('admin.notes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-3">Type d'évaluation *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg transition-colors" :class="noteType === 'cc' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                        <input type="radio" name="type_note" value="cc" x-model="noteType" class="hidden">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="font-medium text-sm">CC</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg transition-colors" :class="noteType === 'examen' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                        <input type="radio" name="type_note" value="examen" x-model="noteType" class="hidden">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        <span class="font-medium text-sm">Examen Final</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg transition-colors" :class="noteType === 'rattrapage' ? 'border-yellow-500 bg-yellow-50 text-yellow-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                        <input type="radio" name="type_note" value="rattrapage" x-model="noteType" class="hidden">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        <span class="font-medium text-sm">Rattrapage</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg transition-colors" :class="noteType === 'reprise' ? 'border-red-500 bg-red-50 text-red-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                        <input type="radio" name="type_note" value="reprise" x-model="noteType" class="hidden">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        <span class="font-medium text-sm">Reprise</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Étudiant *</label>
                <select name="etudiant_id" required @class([ 'w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500' , 'border-slate-200'=> !$errors->has('etudiant_id'),
                    'border-red-500' => $errors->has('etudiant_id'),
                    ])>
                    <option value="">Sélectionnez un étudiant</option>
                    @foreach($etudiants as $etudiant)
                    <option value="{{ $etudiant->id }}" {{ (old('etudiant_id', request('etudiant_id')) == $etudiant->id) ? 'selected' : '' }}>
                        {{ $etudiant->user?->nom ?? '' }} {{ $etudiant->user?->prenom ?? '' }} — {{ $etudiant->filiere?->libelle ?? 'Filière inconnue' }}
                    </option>
                    @endforeach
                </select>
                @error('etudiant_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Matière *</label>
                <select name="matiere_id" required @class([ 'w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500' , 'border-slate-200'=> !$errors->has('matiere_id'),
                    'border-red-500' => $errors->has('matiere_id'),
                    ])>
                    <option value="">Sélectionnez une matière</option>
                    @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id }}" {{ (old('matiere_id', request('matiere_id')) == $matiere->id) ? 'selected' : '' }}>
                        {{ $matiere->libelle ?? $matiere->code }} — {{ $matiere->module?->filiere?->libelle ?? 'Filière inconnue' }}
                    </option>
                    @endforeach
                </select>
                @error('matiere_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="noteType === 'cc'" x-transition>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Note de Contrôle continu</label>
                <input type="number" name="cc" value="{{ old('cc') }}" min="0" max="20" step="0.1" :required="noteType === 'cc'" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: 12.5">
            </div>

            <div x-show="noteType === 'examen'" x-transition style="display: none;">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Note d'Examen</label>
                <input type="number" name="examen" value="{{ old('examen') }}" min="0" max="20" step="0.1" :required="noteType === 'examen'" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: 15.0">
            </div>

            <div x-show="noteType === 'rattrapage'" x-transition style="display: none;">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Note de Rattrapage</label>
                <input type="number" name="note_rattrapage" value="{{ old('note_rattrapage') }}" min="0" max="20" step="0.1" :required="noteType === 'rattrapage'" class="w-full px-4 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="Ex: 11.0">
            </div>

            <div x-show="noteType === 'reprise'" x-transition style="display: none;">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Note de Reprise</label>
                <input type="number" name="note_reprise" value="{{ old('note_reprise') }}" min="0" max="20" step="0.1" :required="noteType === 'reprise'" class="w-full px-4 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ex: 14.0">
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition shadow-sm">
                    Enregistrer la note
                </button>
                <a href="{{ route('admin.notes.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
                    Annuler
                </a>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection