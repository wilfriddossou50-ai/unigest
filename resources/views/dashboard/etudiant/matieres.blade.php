@extends('layouts.etudiant')

@section('title', 'Matières')
@section('subtitle', 'Liste complète de vos matières')

@section('content')
<div x-data="{ ready: false, searchQuery: '' }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <!-- HEADER -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md transition-all duration-700 ease-out">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Matières</h1>
                <p class="mt-1 text-sm text-slate-600">Toutes les matières de votre filière</p>
            </div>
            <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>
    </div>

    @if($matieres->isNotEmpty())
    <!-- SEARCH -->
    <div class="relative">
        <i data-lucide="search" class="absolute left-3 top-3 h-5 w-5 text-slate-400"></i>
        <input type="text" x-model="searchQuery" placeholder="Chercher une matière..."
            class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2 text-sm placeholder-slate-500 focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
    </div>

    <!-- MATIERES GRID -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($matieres as $matiere)
        <div x-show="!searchQuery || '{{ strtolower($matiere->libelle) }}'.includes(searchQuery.toLowerCase())"
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-md hover:shadow-lg hover:border-sky-300 transition-all">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1">
                    <h3 class="font-semibold text-slate-900">{{ $matiere->libelle }}</h3>
                    <p class="mt-1 text-xs text-slate-600">{{ $matiere->module?->libelle ?? $matiere->module?->nom ?? '—' }}</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600 text-xs font-bold">
                    {{ $matiere->code }}
                </span>
            </div>
            @if($matiere->module?->semestre)
            <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 rounded-lg px-3 py-2">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                {{ $matiere->module->semestre->libelle }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="rounded-2xl bg-white p-12 text-center border border-slate-200 shadow-md">
        <i data-lucide="layers" class="mx-auto h-12 w-12 text-slate-300"></i>
        <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucune matière</h3>
        <p class="mt-1 text-sm text-slate-600">Aucune matière n'est disponible pour votre filière.</p>
    </div>
    @endif
</div>
@endsection
