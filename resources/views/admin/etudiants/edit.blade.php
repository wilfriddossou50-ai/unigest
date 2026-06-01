@extends('layouts.admin')

@section('title', 'Modifier un étudiant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.etudiants.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour aux étudiants
        </a>
        <h1 class="mt-4 text-3xl font-bold text-slate-900">Modifier l'étudiant</h1>
        <p class="mt-2 text-sm text-slate-500">Mettez à jour le niveau, la filière ou le statut.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.etudiants.update', $etudiant) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nom</label>
                    <input type="text" value="{{ $etudiant->user->nom ?? '' }}" disabled
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Prénom</label>
                    <input type="text" value="{{ $etudiant->user->prenom ?? '' }}" disabled
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                <input type="email" value="{{ $etudiant->user->email ?? '' }}" disabled
                    class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700">
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Filière *</label>
                    <select name="filiere_id" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('filiere_id') border-red-500 @enderror">
                        <option value="">Sélectionnez une filière</option>
                        @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ $etudiant->filiere_id == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                        @endforeach
                    </select>
                    @error('filiere_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Niveau *</label>
                    <select name="niveau_id" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('niveau_id') border-red-500 @enderror">
                        <option value="">Sélectionnez un niveau</option>
                        @foreach($niveaux as $niveau)
                        <option value="{{ $niveau->id }}" {{ $etudiant->niveau_id == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                        @endforeach
                    </select>
                    @error('niveau_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Statut</label>
                <select name="statut" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="actif" {{ $etudiant->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="suspendu" {{ $etudiant->statut === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    <option value="diplome" {{ $etudiant->statut === 'diplome' ? 'selected' : '' }}>Diplômé</option>
                </select>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('password') border-red-500 @enderror"
                        placeholder="Laisser vide pour ne pas changer">
                    @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Répétez le nouveau mot de passe">
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-slate-200">
                <a href="{{ route('admin.etudiants.index') }}" class="inline-flex justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Annuler
                </a>
                <button type="submit" class="inline-flex justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
