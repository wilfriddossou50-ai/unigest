@extends('layouts.admin')

@section('title', 'Éditer une Filière')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.filieres.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux filières
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Éditer Filière</h1>
        <p class="text-slate-600 mt-2">{{ $filiere->libelle }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Libellé -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Libellé de la filière *</label>
                <input type="text" name="libelle" value="{{ old('libelle', $filiere->libelle) }}" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('libelle') border-red-500 @enderror"
                    placeholder="Ex: Informatique, Génie Civil...">
                @error('libelle')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Code</label>
                <input type="text" name="code" value="{{ old('code', $filiere->code) }}"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex: INFO, GC...">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Entrez une description de la filière">{{ old('description', $filiere->description) }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.filieres.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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