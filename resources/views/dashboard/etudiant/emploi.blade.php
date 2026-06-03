@extends('layouts.etudiant')

@section('title', 'Emploi du temps')
@section('subtitle', 'Votre planning académique')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md transition-all duration-700 ease-out">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Emploi du temps</h1>
                <p class="mt-1 text-sm text-slate-600">Consultez vos séances selon votre filière et votre niveau.</p>
                <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-600">
                    <span class="rounded-full bg-slate-100 px-3 py-1">Filière : {{ $etudiant->filiere?->libelle ?? 'Non défini' }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1">Niveau : {{ $etudiant->niveau?->libelle ?? 'Non défini' }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('etudiant.emploi.index') }}" class="flex items-center gap-3">
                    @if($semestresDisponibles->isNotEmpty())
                    <label class="text-sm font-medium text-slate-700" for="semestre_id">Semestre</label>
                    <select id="semestre_id" name="semestre_id" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                        @foreach($semestresDisponibles as $semestre)
                        <option value="{{ $semestre->id }}" {{ $selectedSemestreId == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">Filtrer</button>
                    @endif
                </form>
                <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>Retour
                </a>
            </div>
        </div>
    </div>

    @if($emplois->isNotEmpty())
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Séances programmées</h2>
            @if($selectedSemestreId)
            <p class="mt-1 text-sm text-slate-600">Affichage du semestre sélectionné.</p>
            @endif
        </div>

        @php
        $emploisGroupedByDay = $emplois->groupBy('jour');
        $daysOrder = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        @endphp

        <div class="space-y-4 p-6">
            @foreach($daysOrder as $day)
            @if($emploisGroupedByDay->has($day))
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <div class="bg-blue-600 px-4 py-3 text-sm font-semibold text-white">{{ $day }}</div>
                <div class="space-y-3 p-4">
                    @foreach($emploisGroupedByDay[$day]->sortBy('heure_debut') as $emploi)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm transition hover:border-blue-300 hover:bg-white">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] text-blue-700">{{ $emploi->semestre?->libelle ?? 'Semestre inconnu' }}</p>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $emploi->matiere?->libelle ?? 'Matière non définie' }}</h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $emploi->matiere?->module?->libelle ?? $emploi->matiere?->module?->nom ?? 'Module inconnu' }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 border border-slate-200">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                    {{ $emploi->heure_debut ?? '—' }} - {{ $emploi->heure_fin ?? '—' }}
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 border border-slate-200">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    {{ $emploi->salle ?? 'Salle non définie' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                            @if($emploi->professeur)
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                {{ $emploi->professeur?->nom ?? '' }} {{ $emploi->professeur?->prenom ?? '' }}
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                                {{ $emploi->niveau?->libelle ?? 'Niveau inconnu' }}
                            </span>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-2xl bg-white p-12 text-center border border-slate-200 shadow-md">
        <i data-lucide="calendar" class="mx-auto h-12 w-12 text-slate-300"></i>
        <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun emploi du temps</h3>
        <p class="mt-1 text-sm text-slate-600">Aucune séance n'est programmée pour ce semestre ou ce niveau.</p>
    </div>
    @endif
</div>
@endsection
