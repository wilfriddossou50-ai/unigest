@extends('layouts.etudiant')

@section('title', 'Tableau de bord')
@section('subtitle', 'Votre espace étudiant')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-[32px] bg-gradient-to-br from-slate-900 via-sky-600 to-sky-500 p-8 text-white shadow-2xl transition-all duration-700 ease-out">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-slate-200/75">Espace étudiant</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight">Bienvenue, {{ $user->prenom }}</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-100/90">Votre parcours est prêt. Retrouvez ici l’essentiel de votre filière, de votre niveau et de vos prochains rendez-vous académiques.</p>
            </div>
            <div class="inline-flex items-center gap-3 rounded-full bg-white/10 px-4 py-3 text-sm font-semibold text-slate-100 ring-1 ring-white/20">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                Statut : approuvé
            </div>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-3xl bg-white/10 p-5 ring-1 ring-white/10 backdrop-blur-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-200/60">Filière</p>
                <p class="mt-4 text-2xl font-semibold text-white">{{ $inscription?->filiere?->libelle ?? 'Non renseignée' }}</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-5 ring-1 ring-white/10 backdrop-blur-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-200/60">Niveau</p>
                <p class="mt-4 text-2xl font-semibold text-white">{{ $etudiant?->niveau?->libelle ?? 'Non renseigné' }}</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-5 ring-1 ring-white/10 backdrop-blur-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-200/60">Dernière mise à jour</p>
                <p class="mt-4 text-2xl font-semibold text-white">{{ optional($inscription?->updated_at)->translatedFormat('d F Y') ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <a href="{{ route('etudiant.notes') }}"
            class="group overflow-hidden rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">Notes</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Consulter</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-sm">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </span>
            </div>
            <p class="mt-5 text-sm text-slate-500">Accédez à vos notes semestrielles et suivez votre progression.</p>
        </a>

        <a href="{{ route('etudiant.bulletin.index') }}"
            class="group overflow-hidden rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">Bulletin</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Télécharger</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-sm">
                    <i data-lucide="file-bar-chart" class="w-5 h-5"></i>
                </span>
            </div>
            <p class="mt-5 text-sm text-slate-500">Consultez ou téléchargez vos bulletins académiques.</p>
        </a>

        <a href="{{ route('etudiant.emploi.index') }}"
            class="group overflow-hidden rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400">Emploi du temps</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Voir</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-sm">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </span>
            </div>
            <p class="mt-5 text-sm text-slate-500">Retrouvez vos séances et créneaux de la semaine.</p>
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition-all duration-700 ease-out">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Détails du dossier</h3>
                    <p class="mt-2 text-sm text-slate-500">Toutes les informations importantes de votre compte.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Actif</span>
            </div>
            <dl class="mt-6 grid gap-5 text-sm text-slate-600">
                <div class="rounded-3xl bg-slate-50 p-4">
                    <dt class="font-semibold text-slate-900">Nom complet</dt>
                    <dd class="mt-1">{{ $user->nom }} {{ $user->prenom }}</dd>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <dt class="font-semibold text-slate-900">Email</dt>
                    <dd class="mt-1">{{ $user->email }}</dd>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <dt class="font-semibold text-slate-900">Sexe</dt>
                    <dd class="mt-1">{{ $inscription->sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <dt class="font-semibold text-slate-900">Date de naissance</dt>
                    <dd class="mt-1">{{ optional($inscription->date_naissance)->format('d/m/Y') ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition-all duration-700 ease-out">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Suivi académique</h3>
                    <p class="mt-2 text-sm text-slate-500">Suivez les actions clés de votre parcours.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">Recommandé</span>
            </div>
            <ul class="mt-6 space-y-4 text-sm text-slate-600">
                <li class="flex gap-3 rounded-3xl bg-slate-50 p-4">
                    <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Résultats et notes disponibles
                </li>
                <li class="flex gap-3 rounded-3xl bg-slate-50 p-4">
                    <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Informations de filière à jour
                </li>
                <li class="flex gap-3 rounded-3xl bg-slate-50 p-4">
                    <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Accès direct aux documents et plannings
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection