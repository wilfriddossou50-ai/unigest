@extends('layouts.admin')

@section('title', 'Résultats Annuels')

@section('content')
<div class="p-8">
    {{-- En-tête avec le bouton global visible même si le tableau est vide --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Bilan Annuel</h1>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch gap-3">
            <form method="GET" action="{{ route('admin.resultats.annuel.index') }}" class="flex items-stretch gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Rechercher un étudiant"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                <select name="decision" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Toutes les décisions</option>
                    <option value="admis" {{ request('decision') === 'admis' ? 'selected' : '' }}>Admis</option>
                    <option value="ajourne" {{ request('decision') === 'ajourne' ? 'selected' : '' }}>Ajourné</option>
                    <option value="redoublant" {{ request('decision') === 'redoublant' ? 'selected' : '' }}>Redoublant</option>
                    <option value="diplome" {{ request('decision') === 'diplome' ? 'selected' : '' }}>Diplôme</option>
                </select>
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition">Filtrer</button>
            </form>

            <form action="{{ route('admin.resultats.annuel.tout-calculer') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Lancer les calculs annuels</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-320px)] overflow-y-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="bg-slate-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">Étudiant</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-500">Moy. S1</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-500">Moy. S2</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-500">Moy. Annuelle</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-500">Décision</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($resultats as $res)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $res->etudiant->user->nom ?? 'N/A' }} {{ $res->etudiant->user->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600">{{ number_format($res->moyenne_s1, 2) }}</td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600">{{ number_format($res->moyenne_s2, 2) }}</td>
                        <td class="px-6 py-4 text-center text-sm font-semibold text-slate-900">{{ number_format($res->moyenne_annuelle, 2) }}</td>
                        <td class="px-6 py-4 text-center text-sm">
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
                        <td class="px-6 py-4 text-center text-sm">
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
                            Aucun bilan annuel généré pour le moment. Cliquez sur le bouton <strong class="text-sky-600">"Lancer les calculs annuels"</strong> ci-dessus pour commencer.
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
    @endsection