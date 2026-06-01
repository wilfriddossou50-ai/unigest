@extends('layouts.admin')

@section('title', 'Ajouter un étudiant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.etudiants.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour aux étudiants
        </a>
        <h1 class="mt-4 text-3xl font-bold text-slate-900">Ajouter un étudiant</h1>
        <p class="mt-2 text-sm text-slate-500">Créez un profil étudiant avec filière et niveau.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('admin.etudiants.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('nom') border-red-500 @enderror"
                        placeholder="Ex: Kouadio">
                    @error('nom')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('prenom') border-red-500 @enderror"
                        placeholder="Ex: Marie">
                    @error('prenom')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('email') border-red-500 @enderror"
                    placeholder="exemple@domaine.com">
                @error('email')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Mot de passe *</label>
                    <input type="password" name="password" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 caractères">
                    @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Répétez le mot de passe">
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Filière *</label>
                    <select name="filiere_id" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('filiere_id') border-red-500 @enderror">
                        <option value="">Sélectionnez une filière</option>
                        @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
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
                        <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                        @endforeach
                    </select>
                    @error('niveau_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-slate-200">
                <a href="{{ route('admin.etudiants.index') }}" class="inline-flex justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Annuler
                </a>
                <button type="submit" class="inline-flex justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Créer l'étudiant
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
