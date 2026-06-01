@extends('layouts.admin') {{-- Ajuste selon ton layout admin --}}

@section('title', 'Résultats du Module')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 border border-slate-200 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 mb-2">
                    Code: {{ $module->code }}
                </span>
                <h1 class="text-2xl font-bold text-slate-900">Résultats : {{ $module->libelle }}</h1>
                <p class="mt-1 text-sm text-slate-600">Suivi des moyennes et des notes par matière pour ce module</p>
            </div>
            <a href="{{ route('admin.modules.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour aux modules
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Étudiant</th>
                        @foreach($module->matieres as $matiere)
                        <th class="px-6 py-4 text-center">{{ $matiere->libelle }}</th>
                        @endforeach
                        <th class="px-6 py-4 text-center bg-sky-50/50 text-sky-900 font-bold">Moyenne Module</th>
                        <th class="px-6 py-4 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse($etudiants as $etudiant)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
    <div class="font-semibold text-slate-900">
        @if($etudiant->user)
            {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
        @else
            <span class="text-red-500">Erreur : Pas d'utilisateur lié !</span>
        @endif
    </div>
    <div class="text-xs text-slate-500">
        Matricule : {{ $etudiant->numero_etudiant ?? 'N/A' }}
    </div>
</td>

                        @foreach($module->matieres as $matiere)
                        @php
                        // On cherche la note de cet étudiant pour cette matière spécifique
                        $noteMatiere = $etudiant->notes->where('matiere_id', $matiere->id)->first();
                        @endphp {{-- 👈 CORRIGÉ ICI : Remplacement de @php par @endphp --}}

                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($noteMatiere && $noteMatiere->note_finale !== null)
                            <span class="font-medium {{ $noteMatiere->note_finale >= 10 ? 'text-emerald-600' : 'text-rose-600 font-bold' }}">
                                {{ number_format($noteMatiere->note_finale, 2, ',', '.') }}/20
                            </span>
                            @else
                            <span class="text-slate-400 italic text-xs">N/A</span>
                            @endif
                        </td>
                        @endforeach

                        <td class="px-6 py-4 text-center whitespace-nowrap bg-sky-50/30 font-bold">
                            @if($etudiant->moyenne_module !== null)
                            <span class="{{ $etudiant->module_valide ? 'text-sky-700' : 'text-slate-900' }}">
                                {{ number_format($etudiant->moyenne_module, 2, ',', '.') }}/20
                            </span>
                            @else
                            <span class="text-slate-400 italic text-xs">Pas de note</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($etudiant->module_valide)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                Validé
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-700 border border-rose-200" title="Toutes les matières doivent être >= 10">
                                Dette
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $module->matieres->count() }}" class="px-6 py-12 text-center text-slate-500">
                            Aucun étudiant inscrit dans la filière de ce module.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script pour s'assurer que l'icône de retour s'affiche correctement --}}
<script>
    lucide.createIcons();
</script>
@endsection
