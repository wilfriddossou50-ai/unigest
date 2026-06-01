@extends('layouts.etudiant')

@section('title', 'Mes dettes')
@section('subtitle', 'Consultez vos matières à régulariser')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <!-- HEADER -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md transition-all duration-700 ease-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mes dettes</h1>
                <p class="mt-1 text-sm text-slate-600">Suivi des matières non encore validées</p>
            </div>
            <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>
    </div>

    @if($dettes->isNotEmpty())
    <!-- STATS -->
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-gradient-to-br from-red-50 to-red-100 p-5 border border-red-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest font-semibold text-red-600">En cours</p>
                    <p class="mt-2 text-2xl font-bold text-red-900">{{ $pending }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-red-200 text-red-700">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest font-semibold text-emerald-600">Levées</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-900">{{ $dettes->where('statut', 'levee')->count() }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-200 text-emerald-700">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 p-5 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest font-semibold text-blue-600">Total</p>
                    <p class="mt-2 text-2xl font-bold text-blue-900">{{ $dettes->count() }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-200 text-blue-700">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- DETTES TABLE -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-md overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Détail des dettes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Semestre</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($dettes as $dette)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $dette->module?->libelle ?? $dette->module?->nom ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $dette->matiere?->libelle ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $dette->semestre?->libelle ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($dette->statut === 'levee')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Levée
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                En cours
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="rounded-2xl bg-white p-12 text-center border border-slate-200 shadow-md">
        <i data-lucide="check-circle" class="mx-auto h-12 w-12 text-emerald-300"></i>
        <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucune dette</h3>
        <p class="mt-1 text-sm text-slate-600">Aucune matière à régulariser pour le moment.</p>
    </div>
    @endif
</div>
@endsection
