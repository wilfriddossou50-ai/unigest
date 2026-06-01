@extends('layouts.admin')

@section('title', 'Modifier le Module')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.modules.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux modules
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Modifier le Module</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.modules.update', $module) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Filière *</label>
                <select name="filiere_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('filiere_id') border-red-500 @enderror">
                    <option value="">Sélectionnez une filière</option>
                    @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ old('filiere_id', $module->filiere_id) == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                    @endforeach
                </select>
                @error('filiere_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Semestre *</label>
                <select name="semestre_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semestre_id') border-red-500 @enderror">
                    <option value="">Sélectionnez un semestre</option>
                    @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}" {{ old('semestre_id', $module->semestre_id) == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                    @endforeach
                </select>
                @error('semestre_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Code *</label>
                <input type="text" name="code" value="{{ old('code', $module->code) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" placeholder="Ex: MATH101">
                @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Libellé du module *</label>
                <input type="text" name="libelle" value="{{ old('libelle', $module->libelle) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('libelle') border-red-500 @enderror" placeholder="Ex: Analyse de données">
                @error('libelle')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Sauvegarder
                </button>
                <a href="{{ route('admin.modules.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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
