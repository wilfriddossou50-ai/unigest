@extends('layouts.admin')

@section('title', 'Modifier la Matière')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.matieres.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux matières
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Modifier la Matière</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ url('admin/matieres/' . $matiere->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Module *</label>
                <select name="module_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('module_id') border-red-500 @enderror">
                    <option value="">Sélectionnez un module</option>
                    @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ old('module_id', $matiere->module_id) == $module->id ? 'selected' : '' }}>
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
                <input type="text" name="code" value="{{ old('code', $matiere->code) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" placeholder="Ex: ALGO101">
                @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Libellé de la matière *</label>
                <input type="text" name="libelle" value="{{ old('libelle', $matiere->libelle) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('libelle') border-red-500 @enderror" placeholder="Ex: Programmation Web">
                @error('libelle')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('admin.matieres.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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
