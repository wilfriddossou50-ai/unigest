@extends('layouts.admin')

@section('title', 'Ajouter un Professeur')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.professeurs.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux professeurs
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Ajouter un Professeur</h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl">
        <form action="{{ route('admin.professeurs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Code professeur *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                        placeholder="Ex: P001">
                    @error('code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Sexe *</label>
                    <select name="sexe" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sexe') border-red-500 @enderror">
                        <option value="">Sélectionnez</option>
                        <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror"
                        placeholder="Entrez le nom">
                    @error('nom')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prenom') border-red-500 @enderror"
                        placeholder="Entrez le prénom">
                    @error('prenom')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="exemple@universite.com">
                    @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Date de naissance</label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_naissance') border-red-500 @enderror">
                    @error('date_naissance')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telephone') border-red-500 @enderror"
                        placeholder="+223 XX XX XX XX">
                    @error('telephone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Statut *</label>
                    <select name="statut" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('statut') border-red-500 @enderror">
                        <option value="">Sélectionnez un statut</option>
                        <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('statut')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Grade académique *</label>
                    <select name="grade" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('grade') border-red-500 @enderror">
                        <option value="">Sélectionnez un grade</option>
                        <option value="assistant" {{ old('grade') === 'assistant' ? 'selected' : '' }}>Assistant</option>
                        <option value="ingenieur" {{ old('grade') === 'ingenieur' ? 'selected' : '' }}>Ingénieur</option>
                        <option value="docteur" {{ old('grade') === 'docteur' ? 'selected' : '' }}>Docteur</option>
                    </select>
                    @error('grade')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Spécialité</label>
                    <input type="text" name="specialite" value="{{ old('specialite') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('specialite') border-red-500 @enderror"
                        placeholder="Ex: Mathématiques, Informatique...">
                    @error('specialite')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t border-slate-200">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                    Ajouter le Professeur
                </button>
                <a href="{{ route('admin.professeurs.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-medium py-2.5 rounded-lg transition text-center">
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