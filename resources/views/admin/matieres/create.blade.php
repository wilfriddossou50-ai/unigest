@extends('layouts.admin')

@section('title', 'Ajouter une Matière')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.matieres.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux matières
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Ajouter une Matière</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        @if($modules->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Créez d'abord au moins un module avec sa filière et son semestre avant d'ajouter une matière.
        </div>
        @else
        <form action="{{ route('admin.matieres.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Module *</label>
                <select name="module_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('module_id') border-red-500 @enderror">
                    <option value="">Sélectionnez un module</option>
                    @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                        {{ $module->libelle ?? $module->nom ?? 'Module inconnu' }} — {{ $module->filiere?->libelle ?? 'Filière inconnue' }} — {{ $module->semestre?->libelle ?? 'Semestre inconnu' }}
                    </option>
                    @endforeach
                </select>
                @error('module_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Code *</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" placeholder="Ex: ALGO101">
                @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Libellé de la matière *</label>
                <input type="text" name="libelle" value="{{ old('libelle') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('libelle') border-red-500 @enderror" placeholder="Ex: Programmation Web">
                @error('libelle')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Enregistrer la matière
                </button>
                <a href="{{ route('admin.matieres.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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
