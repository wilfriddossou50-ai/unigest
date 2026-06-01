@extends('layouts.admin')

@section('title', 'Centre de Pilotage')
@section('subtitle', 'Vue d\'ensemble de l\'université')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-8 pb-8">

    <!-- HEADER -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 ease-out">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Bonjour, Admin ! </h1>
        <p class="mt-2 text-slate-600">Voici un aperçu de l'état actuel de votre système universitaire.</p>
    </div>

    <!-- STATS (KPIs) -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Étudiants -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-100 ease-out">
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all">
                <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-50 opacity-50 transition-transform group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Étudiants</p>
                        <p class="mt-2 text-4xl font-bold text-slate-900">{{ $totalEtudiants }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professeurs -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-150 ease-out">
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:border-sky-300 transition-all">
                <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-sky-50 opacity-50 transition-transform group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Enseignants</p>
                        <p class="mt-2 text-4xl font-bold text-slate-900">{{ $totalProfesseurs }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <i data-lucide="user-check" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filières -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-200 ease-out">
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:border-emerald-300 transition-all">
                <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-emerald-50 opacity-50 transition-transform group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Filières actives</p>
                        <p class="mt-2 text-4xl font-bold text-slate-900">{{ $totalFilieres }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <i data-lucide="network" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inscriptions en attente -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-300 ease-out">
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:border-amber-300 transition-all {{ $pending > 0 ? 'ring-2 ring-amber-400 ring-offset-2' : '' }}">
                <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-amber-50 opacity-50 transition-transform group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Inscriptions</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <p class="text-4xl font-bold text-slate-900">{{ $pending }}</p>
                            <p class="text-sm font-medium text-amber-600">en attente</p>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i data-lucide="bell" class="h-6 w-6 {{ $pending > 0 ? 'animate-bounce' : '' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACCÈS RAPIDES -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-400 ease-out">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Actions rapides</h2>
        <div class="grid gap-4 grid-cols-2 md:grid-cols-4">
            <a href="{{ route('admin.inscriptions.index') }}" class="flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-slate-700 hover:text-blue-700 px-4 py-3 rounded-xl font-semibold shadow-sm transition">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Admissions
            </a>
            <a href="{{ route('admin.notes.create') }}" class="flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-sky-400 hover:bg-sky-50 text-slate-700 hover:text-sky-700 px-4 py-3 rounded-xl font-semibold shadow-sm transition">
                <i data-lucide="pen-tool" class="w-5 h-5"></i>
                Saisir Note
            </a>
            <a href="{{ route('admin.programme-cours.index') }}" class="flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 px-4 py-3 rounded-xl font-semibold shadow-sm transition">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                Emploi du temps
            </a>
            <a href="{{ route('admin.resultats.semestre.index') }}" class="flex items-center justify-center gap-2 bg-slate-900 border border-slate-900 hover:bg-slate-800 text-white px-4 py-3 rounded-xl font-semibold shadow-sm transition shadow-blue-900/20">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                Résultats
            </a>
        </div>
    </div>

    <!-- LISTES (2 Colonnes) -->
    <div class="grid gap-6 lg:grid-cols-2">

        <!-- Dernières Inscriptions -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden transition-all duration-700 delay-500 ease-out">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-slate-400"></i>
                    Dernières demandes
                </h3>
                <a href="{{ route('admin.inscriptions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Voir tout</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($latestInscriptions as $inscription)
                <div class="p-6 hover:bg-slate-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0">
                                {{ strtoupper(substr($inscription->nom ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $inscription->nom }} {{ $inscription->prenom }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $inscription->filiere->libelle ?? 'Filière non spécifiée' }}</p>
                            </div>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $inscription->statut === 'en_attente' ? 'bg-amber-100 text-amber-700 border border-amber-200' : ($inscription->statut === 'approuvee' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-200') }}">
                            {{ ucwords(str_replace('_', ' ', $inscription->statut)) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500">
                    <i data-lucide="check-square" class="mx-auto w-8 h-8 text-slate-300 mb-2"></i>
                    <p>Aucune inscription récente.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Dernières Notes Saisies -->
        <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden transition-all duration-700 delay-500 ease-out">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-slate-400"></i>
                    Dernières notes ajoutées
                </h3>
                <a href="{{ route('admin.notes.index') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Historique</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($latestNotes as $note)
                <div class="p-6 hover:bg-slate-50 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $note->etudiant->user->nom ?? 'Inconnu' }} {{ $note->etudiant->user->prenom ?? '' }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                    {{ $note->matiere->code ?? 'N/A' }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $note->matiere->libelle ?? 'Matière inconnue' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            @if(!is_null($note->cc))
                            <div class="text-center">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">CC</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $note->cc }}</p>
                            </div>
                            @endif
                            @if(!is_null($note->examen))
                            <div class="text-center">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Exam</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $note->examen }}</p>
                            </div>
                            @endif
                            @if(!is_null($note->note_finale))
                            <div class="ml-2 pl-3 border-l border-slate-200 text-center">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Finale</p>
                                <p class="text-sm font-bold text-sky-600">{{ $note->note_finale }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500">
                    <i data-lucide="file-minus" class="mx-auto w-8 h-8 text-slate-300 mb-2"></i>
                    <p>Aucune note n'a été saisie récemment.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection