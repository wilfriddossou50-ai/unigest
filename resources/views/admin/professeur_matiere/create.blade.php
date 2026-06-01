@extends('layouts.admin')

@section('title', 'Nouvelle Affectation')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.professeur-matiere.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux affectations
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Créer une affectation</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.professeur-matiere.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Professeur *</label>
                <select name="professeur_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                    <option value="">Sélectionnez un professeur</option>
                    @foreach($professeurs as $professeur)
                        <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>
                            {{ $professeur->nom_complet }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Matière *</label>
                <select name="matiere_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg">
                    <option value="">Sélectionnez une matière</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Enregistrer
                </button>
                <a href="{{ route('admin.professeur-matiere.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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
