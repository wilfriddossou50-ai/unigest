@extends('layouts.etudiant')

@section('title', 'Mes modules')
@section('subtitle', 'Consultez tous vos modules par semestre')

@section('content')
<div x-data="{ expandedSemestre: null, ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <!-- HEADER -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md transition-all duration-700 ease-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mes modules</h1>
                <p class="mt-1 text-sm text-slate-600">Tous les modules de votre filière organisés par semestre</p>
            </div>
            <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>
    </div>

    @if($modules->isNotEmpty())
    <!-- MODULES BY SEMESTER -->
    <div class="space-y-4">
        @foreach($modules->groupBy('semestre_id') as $semestreId => $modulesGroup)
        @php $semestre = $modulesGroup->first()->semestre; @endphp
        <div class="rounded-xl border border-slate-200 overflow-hidden shadow-md hover:shadow-lg transition-all">
            <button @click="expandedSemestre = expandedSemestre === {{ $semestreId }} ? null : {{ $semestreId }}"
                class="w-full px-6 py-5 bg-gradient-to-r from-sky-50 to-blue-50 hover:from-sky-100 hover:to-blue-100 flex items-center justify-between transition">
                <div class="flex items-center gap-4">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-white">
                        <i data-lucide="book" class="w-5 h-5"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-slate-900">{{ $semestre->libelle ?? "Semestre $semestreId" }}</h3>
                        <p class="text-sm text-slate-600">{{ $modulesGroup->count() }} module(s)</p>
                    </div>
                </div>
                <i data-lucide="chevron-down" class="w-5 h-5 text-slate-600 transition-transform"
                   :class="expandedSemestre === {{ $semestreId }} && 'rotate-180'"></i>
            </button>

            <div x-show="expandedSemestre === {{ $semestreId }}" x-transition class="border-t border-slate-200 bg-white">
                <div class="divide-y divide-slate-200">
                    @foreach($modulesGroup as $module)
                    <div class="px-6 py-5 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="font-semibold text-slate-900">{{ $module->libelle ?? $module->nom ?? 'Module sans libellé' }}</h4>
                                <p class="mt-1 text-sm text-slate-600">{{ $module->matieres->count() }} matière(s)</p>
                                
                                @if(!is_null($module->moyenne))
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-sm font-semibold text-slate-800">Moyenne: {{ number_format($module->moyenne, 2, ',', '.') }}</span>
                                    @if($module->est_valide)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Module Validé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700 border border-rose-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Non validé (Dette)
                                            </span>
                                        @endif
                                </div>
                                @else
                                <div class="mt-2 text-xs text-slate-500 italic">Moyenne en attente des notes...</div>
                                @endif
                                
                            </div>
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $module->code }}
                            </span>
                        </div>
                        @if($module->matieres->isNotEmpty())
                        <div class="mt-4 space-y-2">
                            @foreach($module->matieres as $matiere)
                            <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm">
                                <div class="font-medium text-slate-900">{{ $matiere->libelle }}</div>
                                <div class="text-xs text-slate-600 mt-1">Code: <span class="font-semibold">{{ $matiere->code }}</span></div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="rounded-2xl bg-white p-12 text-center border border-slate-200 shadow-md">
        <i data-lucide="package" class="mx-auto h-12 w-12 text-slate-300"></i>
        <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun module</h3>
        <p class="mt-1 text-sm text-slate-600">Aucun module n'a été attribué à votre filière pour le moment.</p>
    </div>
    @endif
</div>
@endsection
