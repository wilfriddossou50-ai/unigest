@extends('layouts.admin')

@section('title', 'Rsultats Annuels')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Rsultats</p>
            <h1 class="text-2xl font-bold text-slate-900">Bilan Annuel</h1>
            <p class="mt-1 text-sm text-slate-500">Affichage compact avec recherche et recalcul global.</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">
            <form method="GET" action="{{ route('admin.resultats.annuel.index') }}" class="admin-toolbar">
                <div class="admin-toolbar-grid">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Rechercher un tudiant"
                        class="admin-filter-input" />
                    <select name="decision" class="admin-filter-select">
                        <option value="">Toutes les dcisions</option>
                        <option value="admis" {{ request('decision') === 'admis' ? 'selected' : '' }}>Admis</option>
                        <option value="ajourne" {{ request('decision') === 'ajourne' ? 'selected' : '' }}>Ajourn</option>
                        <option value="redoublant" {{ request('decision') === 'redoublant' ? 'selected' : '' }}>Redoublant</option>
                        <option value="diplome" {{ request('decision') === 'diplome' ? 'selected' : '' }}>Diplme</option>
                    </select>
                    <button type="submit" class="admin-filter-button">Filtrer</button>
                </div>
            </form>

            <form action="{{ route('admin.resultats.annuel.tout-calculer') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Lancer les calculs annuels
                </button>
            </form>
        </div>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap max-h-[calc(100vh-320px)] overflow-y-auto">
            <table class="admin-table min-w-[760px] text-sm">
                <thead class="sticky top-0 z-20">
                    <tr>
                        <th class="sticky left-0 z-10 bg-slate-50">Étudiant</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">Moy. S1</th>
                        <th class="hidden md:table-cell px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">Moy. S2</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">Moy. Annuelle</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">Dcision</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($resultats as $res)
                    <tr class="admin-row">
                        <td class="sticky left-0 z-10 bg-white border-r border-slate-200 px-4 py-4 text-sm font-medium text-slate-900">
                            {{ $res->etudiant?->user?->nom ?? 'N/A' }} {{ $res->etudiant?->user?->prenom ?? '' }}
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ number_format((float) ($res->moyenne_s1 ?? 0), 2) }}</td>
                        <td class="hidden md:table-cell px-4 py-4 text-center text-sm text-slate-600">{{ number_format((float) ($res->moyenne_s2 ?? 0), 2) }}</td>
                        <td class="px-4 py-4 text-center text-sm font-semibold text-slate-900">{{ number_format((float) ($res->moyenne_annuelle ?? 0), 2) }}</td>
                        <td class="px-4 py-4 text-center text-sm">
                            @php
                            if (in_array($res->decision, ['admis', 'diplome'])) {
                            $decisionClass = 'bg-emerald-100 text-emerald-700';
                            } elseif ($res->decision === 'ajourne') {
                            $decisionClass = 'bg-amber-100 text-amber-700';
                            } elseif ($res->decision === 'redoublant') {
                            $decisionClass = 'bg-rose-100 text-rose-700';
                            } else {
                            $decisionClass = 'bg-slate-100 text-slate-700';
                            }
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[10px] font-semibold uppercase {{ $decisionClass }}">
                                {{ str_replace('_', ' ', ucfirst($res->decision ?? 'en attente')) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm">
                            <form action="{{ route('admin.resultats.annuel.calculer', [$res->etudiant_id, $res->niveau_id]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sky-600 hover:text-sky-900 font-medium">
                                    Recalculer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                            Aucun bilan annuel gnr pour le moment. Cliquez sur le bouton <strong class="text-sky-600">"Lancer les calculs annuels"</strong> ci-dessus pour commencer.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($resultats->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $resultats->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
