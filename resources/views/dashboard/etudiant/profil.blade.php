@extends('layouts.etudiant')

@section('title', 'Mon profil')
@section('subtitle', 'Gérez vos informations personnelles')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6 max-w-4xl">
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

    <!-- PROFILE HEADER -->
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

    <!-- PROFILE INFORMATION -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md p-6">
        <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mb-4">
            <i data-lucide="user" class="w-5 h-5 text-sky-600"></i>
            Informations du profil
        </h3>
        <p class="text-sm text-slate-600 mb-6">Mettez à jour votre nom et votre adresse email.</p>

        @if ($status === 'profile-updated')
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
            <p class="text-sm text-emerald-700 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Profil mis à jour avec succès
            </p>
        </div>
        @endif

        <form method="post" action="{{ route('etudiant.profil.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="name" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                    Nom complet
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', auth()->user()->name ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm shadow-sm placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    placeholder="Votre nom complet" />
                @error('name')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                    Adresse email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', auth()->user()->email ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm shadow-sm placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    placeholder="email@example.com" />
                @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 px-6 rounded-lg transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- UPDATE PASSWORD FORM -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md p-6">
        <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mb-4">
            <i data-lucide="lock" class="w-5 h-5 text-sky-600"></i>
            Sécurité du compte
        </h3>
        <p class="text-sm text-slate-600 mb-6">Changez votre mot de passe régulièrement pour sécuriser votre compte</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                    Mot de passe actuel
                </label>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm shadow-sm placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    autocomplete="current-password"
                    placeholder="••••••••" />
                @if ($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-xs text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="update_password_password" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                    Nouveau mot de passe
                </label>
                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm shadow-sm placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    autocomplete="new-password"
                    placeholder="••••••••" />
                @if ($errors->updatePassword->has('password'))
                <p class="mt-2 text-xs text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                    Confirmer le mot de passe
                </label>
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm shadow-sm placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    autocomplete="new-password"
                    placeholder="••••••••" />
                @if ($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-xs text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 px-6 rounded-lg transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- SECURITY INFO -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md p-6">
        <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mb-4">
            <i data-lucide="shield" class="w-5 h-5 text-sky-600"></i>
            État de sécurité
        </h3>

        <div class="space-y-4">
            <div class="flex items-start justify-between rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-200 text-emerald-700">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <p class="font-medium text-slate-900">Identité vérifiée</p>
                        <p class="text-xs text-slate-600 mt-1">Votre compte a été approuvé par l'administration</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start justify-between rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-200 text-blue-700">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <p class="font-medium text-slate-900">Connexion sécurisée</p>
                        <p class="text-xs text-slate-600 mt-1">Vous êtes actuellement connecté de manière sécurisée</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE ACCOUNT -->
    <div class="rounded-2xl bg-white border border-red-200 shadow-md p-6">
        <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2 mb-4">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
            Zone de danger
        </h3>
        <p class="text-sm text-slate-600 mb-4">Actions irreversibles</p>

        <div class="rounded-lg bg-red-50 border border-red-200 p-4">
            <h4 class="font-semibold text-red-900 mb-2">Supprimer mon compte</h4>
            <p class="text-xs text-red-700 mb-4">
                La suppression de votre compte supprimera toutes les données associées (notes, inscriptions, etc.).
                Cette action ne peut pas être annulée.
            </p>

            <form method="post" action="{{ route('etudiant.profil.destroy') }}" class="space-y-4"
                onsubmit="return confirm('Êtes-vous absolument sûr ? Cette action est irréversible.')">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-xs uppercase tracking-wider font-semibold text-slate-700 mb-2">
                        Confirmer avec votre mot de passe
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-red-300 px-4 py-2.5 text-sm shadow-sm focus:border-red-500 focus:ring-1 focus:ring-red-500"
                        placeholder="••••••••" />
                    @if ($errors->userDeletion->has('password'))
                    <p class="mt-2 text-xs text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition">
                    Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>
@endsection