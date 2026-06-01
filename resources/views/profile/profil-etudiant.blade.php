@extends('layouts.etudiant')

@section('title', 'Mon profil')
@section('subtitle', 'Gérez vos informations personnelles')

@section('content')
<div x-data="{ ready: false, showPassword: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6 max-w-4xl">
    <!-- HEADER -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md transition-all duration-700 ease-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mon profil</h1>
                <p class="mt-1 text-sm text-slate-600">Gérez vos informations personnelles et paramètres</p>
            </div>
            <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- PROFILE CARD -->
    <div class="rounded-2xl bg-gradient-to-br from-sky-600 to-sky-700 p-8 text-white shadow-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full"></div>
        </div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="h-20 w-20 rounded-2xl bg-white/20 flex items-center justify-center text-3xl font-bold backdrop-blur-sm ring-2 ring-white/30">
                {{ strtoupper(substr(auth()->user()->nom ?? 'E', 0, 1)) }}{{ strtoupper(substr(auth()->user()->prenom ?? 'E', 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</h2>
                <p class="mt-1 text-white/80">{{ auth()->user()->email }}</p>
                <p class="mt-1 text-xs uppercase tracking-widest text-white/60 font-semibold">Compte étudiant</p>
            </div>
        </div>
    </div>

    <!-- INFO CARDS -->
    <div class="grid gap-6 md:grid-cols-2">
        <!-- UPDATE PASSWORD -->
        <div class="rounded-2xl bg-white border border-slate-200 shadow-md p-6">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-sky-600"></i>
                Mot de passe
            </h3>
            <p class="mt-1 text-sm text-slate-600">Modifiez votre mot de passe régulièrement</p>

            <form method="POST" action="{{ route('etudiant.profil.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                        Mot de passe actuel
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                            placeholder="••••••••">
                    </div>
                    @error('current_password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        placeholder="••••••••">
                    @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                        Confirmer mot de passe
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 rounded-lg transition">
                    Mettre à jour
                </button>
            </form>
        </div>

        <!-- DELETE ACCOUNT -->
        <div class="rounded-2xl bg-white border border-red-200 shadow-md p-6">
            <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                Supprimer le compte
            </h3>
            <p class="mt-1 text-sm text-slate-600">Cette action est irréversible</p>

            <div class="mt-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <p class="text-xs text-red-700 mb-4">
                    La suppression de votre compte supprimera toutes les données associées. Cette action ne peut pas être annulée.
                </p>

                <form method="POST" action="{{ route('etudiant.profil.destroy') }}"
                    onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition">
                        Supprimer mon compte
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SESSION SECURITY -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md p-6">
        <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
            <i data-lucide="shield" class="w-5 h-5 text-sky-600"></i>
            Sécurité
        </h3>
        <p class="mt-1 text-sm text-slate-600">Informations de sécurité de votre compte</p>

        <div class="mt-6 space-y-4">
            <div class="flex items-start justify-between rounded-lg border border-slate-200 p-4">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <p class="font-medium text-slate-900">Identité vérifiée</p>
                        <p class="text-xs text-slate-600 mt-1">Votre compte a été approuvé</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start justify-between rounded-lg border border-slate-200 p-4">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <p class="font-medium text-slate-900">Connexion sécurisée</p>
                        <p class="text-xs text-slate-600 mt-1">Dernière connexion: {{ auth()->user()->updated_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection