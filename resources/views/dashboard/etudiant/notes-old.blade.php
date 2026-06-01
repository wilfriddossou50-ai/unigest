@extends('layouts.etudiant')

@section('title', 'Mes notes')
@section('subtitle', 'Consultez votre performance académique')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-3xl bg-white p-8 border border-slate-200 shadow-sm transition-all duration-700 ease-out hover:-translate-y-1 hover:shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Mes notes</h1>
                <p class="text-sm text-slate-500">Retrouvez un aperçu des résultats enregistrés pour votre filière.</p>
            </div>
            <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Retour au tableau
            </a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Semestre</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $currentSemestre ?? 'Non défini' }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Moyenne</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $average ?? '—' }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Crédits validés</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $validatedCount ?? 0 }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Aperçu des notes</h2>
                    <p class="text-sm text-slate-500">Retrouvez la liste des matières et vos notes finales.</p>
                </div>
                @if($notes->isNotEmpty())
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $notes->count() }} notes</span>
                @else
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Aucune note</span>
                @endif
            </div>

            @if($notes->isNotEmpty())
            <div class="mt-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left uppercase tracking-[0.2em]">Matière</th>
                            <th class="px-4 py-3 text-left uppercase tracking-[0.2em]">Module</th>
                            <th class="px-4 py-3 text-left uppercase tracking-[0.2em]">Semestre</th>
                            <th class="px-4 py-3 text-left uppercase tracking-[0.2em]">Note finale</th>
                            <th class="px-4 py-3 text-left uppercase tracking-[0.2em]">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($notes as $note)
                        <tr>
                            <td class="px-4 py-3 text-slate-700">{{ $note->matiere?->libelle ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $note->matiere?->module?->nom ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $note->matiere?->module?->semestre?->libelle ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $note->note_finale ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $note->statut === 'valide' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($note->statut ?? 'en attente') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="mt-6 grid gap-4">
                <div class="rounded-3xl bg-white p-4 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-slate-900">Aucun résultat à afficher pour le moment</p>
                        <span class="text-xs text-slate-500">Section vide</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Les notes sont synchronisées dès qu’elles sont saisies par l’administration.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection