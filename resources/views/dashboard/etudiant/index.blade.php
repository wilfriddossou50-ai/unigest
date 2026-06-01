@extends('layouts.etudiant')

@section('title', 'Tableau de bord')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6 pb-8">

    <!-- HERO SECTION -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-3xl bg-gradient-to-br from-sky-600 via-sky-500 to-sky-700 p-8 text-white shadow-xl transition-all duration-700 ease-out overflow-hidden relative">

        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Bienvenue</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight">Bonjour, {{ $user->prenom }} !</h2>
                <p class="mt-3 max-w-2xl text-base text-sky-100">Gérez votre progression académique et consultez vos informations en un coup d'œil.</p>
            </div>

            <div class="flex flex-col items-end gap-2">
                @if($pendingDettes > 0)
                <a href="{{ route('etudiant.dettes.index') }}" class="inline-flex items-center gap-3 rounded-full bg-red-500/90 backdrop-blur-md px-4 py-2 text-sm font-bold text-white shadow-lg ring-1 ring-white/20 hover:bg-red-500 transition cursor-pointer">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-white animate-pulse"></span>
                    {{ $pendingDettes }} dette(s) en cours
                </a>
                @else
                <div class="inline-flex items-center gap-3 rounded-full bg-emerald-500/90 backdrop-blur-md px-4 py-2 text-sm font-bold text-white shadow-lg ring-1 ring-white/20">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-white"></span>
                    Dossier à jour
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QUICK ACCESS CARDS -->
    <div class="grid gap-5 sm:grid-cols-2">
        <a href="{{ route('etudiant.notes') }}"
            class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm border border-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-sky-300">
            <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-sky-50 opacity-50"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Évaluation</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">Consulter mes notes</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </span>
            </div>
        </a>

        <a href="{{ route('etudiant.emploi.index') }}"
            class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm border border-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-emerald-300">
            <div class="absolute right-0 top-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-emerald-50 opacity-50"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Planning</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">Emploi du temps</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </span>
            </div>
        </a>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- DERNIÈRES NOTES -->
        <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-sky-500"></i>
                    Dernières évaluations
                </h3>
                <a href="{{ route('etudiant.notes') }}" class="text-sm font-bold text-sky-600 hover:text-sky-800">Tout voir</a>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse($latestNotes as $note)
                <div class="p-5 hover:bg-slate-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900">{{ $note->matiere->libelle ?? 'Matière' }}</p>
                            <p class="text-xs text-slate-500">{{ $note->matiere->module->libelle ?? $note->matiere->module->nom ?? 'Module général' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase font-bold text-sky-400">Moyenne</p>
                            <p class="text-xl font-black text-sky-600">{{ number_format($note->note_finale ?? 0, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-slate-500">Aucune note enregistrée pour le moment.</div>
                @endforelse
            </div>
        </div>

        <!-- SIDE WIDGETS -->
        <div class="space-y-6">
            <!-- Profil -->
            <div class="rounded-3xl bg-slate-900 p-6 shadow-xl text-white">
                <h3 class="text-lg font-bold mb-4">Mon profil</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Email</p>
                        <p class="text-sm text-slate-200 truncate">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Matricule</p>
                        <p class="text-sm text-slate-200">{{ $etudiant->numero_etudiant ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Accès Modules -->
            <a href="{{ route('etudiant.modules.index') }}" class="block rounded-3xl bg-white border border-slate-200 p-6 shadow-sm hover:border-cyan-300 transition">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">
                        <i data-lucide="grid" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Mes Modules</h3>
                        <p class="text-sm text-slate-500">Parcours académique</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
