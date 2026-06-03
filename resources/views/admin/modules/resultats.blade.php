@extends('layouts.admin')

@section('title', 'Rsultats du Module')

@section('content')
<div class="admin-page space-y-6">
    <div class="rounded-2xl bg-white p-6 border border-slate-200 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 mb-2">
                    Code: {{ $module->code }}
                </span>
                <h1 class="text-2xl font-bold text-slate-900">Rsultats : {{ $module->libelle }}</h1>
                <p class="mt-1 text-sm text-slate-600">Suivi des moyennes et des notes par matire pour ce module.</p>
            </div>
            <a href="{{ route('admin.modules.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour aux modules
            </a>
        </div>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap overflow-x-auto">
            <table class="admin-table min-w-[900px] text-sm">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        @foreach($module->matieres as $matiere)
                        <th class="text-center whitespace-nowrap">{{ $matiere->libelle }}</th>
                        @endforeach
                        <th class="text-center bg-sky-50/50 text-sky-900 font-bold whitespace-nowrap">Moyenne Module</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse($etudiants as $etudiant)
                    <tr class="admin-row">
                        <td class="whitespace-nowrap">
                            <div class="font-semibold text-slate-900">
                                @if($etudiant->user)
                                    {{ $etudiant->user?->nom ?? '' }} {{ $etudiant->user?->prenom ?? '' }}
                                @else
                                    <span class="text-red-500">Erreur : Pas d'utilisateur li !</span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-500">
                                Matricule : {{ $etudiant->numero_etudiant ?? 'N/A' }}
                            </div>
                        </td>

                        @foreach($module->matieres as $matiere)
                        @php
                        $noteMatiere = $etudiant->notes->where('matiere_id', $matiere->id)->first();
                        @endphp
                        <td class="text-center whitespace-nowrap">
                            @if($noteMatiere && $noteMatiere->note_finale !== null)
                            <span class="font-medium {{ $noteMatiere->note_finale >= 10 ? 'text-emerald-600' : 'text-rose-600 font-bold' }}">
                                {{ number_format($noteMatiere->note_finale, 2, ',', '.') }}/20
                            </span>
                            @else
                            <span class="text-slate-400 italic text-xs">N/A</span>
                            @endif
                        </td>
                        @endforeach

                        <td class="text-center whitespace-nowrap bg-sky-50/30 font-bold">
                            @if($etudiant->moyenne_module !== null)
                            <span class="{{ $etudiant->module_valide ? 'text-sky-700' : 'text-slate-900' }}">
                                {{ number_format($etudiant->moyenne_module, 2, ',', '.') }}/20
                            </span>
                            @else
                            <span class="text-slate-400 italic text-xs">Pas de note</span>
                            @endif
                        </td>

                        <td class="text-center whitespace-nowrap">
                            @if($etudiant->module_valide)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                Valid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-700 border border-rose-200" title="Toutes les matires doivent tre >= 10">
                                Dette
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $module->matieres->count() }}" class="px-6 py-12 text-center text-slate-500">
                            Aucun tudiant inscrit dans la filire de ce module.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
