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

    @php
    $matieresParSemestre = $matieres->groupBy(fn($matiere) => $matiere->module?->semestre?->libelle ?? 'Semestre inconnu');
    @endphp

    <div class="space-y-6">
        @foreach($matieresParSemestre as $semestreLibelle => $matieresSemestre)
        @php
        $modulesGroupes = $matieresSemestre->groupBy(fn($matiere) => $matiere->module?->id ?? 0);
        $moduleCount = $modulesGroupes->count();
        $matiereCount = $matieresSemestre->count();
        @endphp
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Semestre</p>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $semestreLibelle }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $matiereCount }} matière(s) répartie(s) sur {{ $moduleCount }} module(s).</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700">
                    <span class="font-semibold">Filtrer :</span>
                    <span>{{ $matiereCount }} matières</span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($modulesGroupes as $moduleId => $moduleMatieres)
                @php $module = $moduleMatieres->first()->module; @endphp
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Module</p>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $module->libelle ?? $module->nom ?? 'Module inconnu' }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $module->code ?? 'Code indisponible' }}</p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 border border-slate-200">
                            {{ $moduleMatieres->count() }} matière(s)
                        </span>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($moduleMatieres as $matiere)
                        <div x-show="!searchQuery || '{{ strtolower($matiere->libelle) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($module->libelle ?? $module->nom) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($semestreLibelle) }}'.includes(searchQuery.toLowerCase())"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-slate-900 truncate">{{ $matiere->libelle }}</h4>
                                    <p class="mt-1 text-xs text-slate-500">{{ $matiere->code ?? '—' }}</p>
                                </div>
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sky-600 text-xs font-bold">
                                    {{ strtoupper(substr($matiere->code ?? 'M', 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </section>
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