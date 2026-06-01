@extends('layouts.admin')

@section('title', 'Suivi de la Progression')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Progression des Étudiants</h1>
            <p class="text-slate-600 mt-1">Consultez l'évolution historique et le statut d'inscription de chaque étudiant</p>
        </div>
        <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
            <i data-lucide="info" class="w-3 h-3 inline-block mr-1"></i> Synchronisé automatiquement avec le bilan annuel.
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Étudiant</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Niveau concerné</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Année Académique</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Statut de Progression</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($progressions ?? [] as $progression)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($progression->etudiant->user->nom ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $progression->etudiant->user->nom ?? 'Inconnu' }} {{ $progression->etudiant->user->prenom ?? '' }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $progression->etudiant->numero_etudiant ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                {{ $progression->niveau->libelle ?? $progression->niveau->code ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center text-sm font-medium text-slate-600">
                            {{ $progression->annee_academique }}
                        </td>

                        <td class="px-6 py-4 text-center text-sm">
                            @if($progression->statut === 'diplome')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Diplômé(e)
                            </span>
                            @elseif($progression->statut === 'passage')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Passage au niveau supérieur
                            </span>
                            @elseif($progression->statut === 'redoublement')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700 border border-red-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Redoublant
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span> En cours
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="trending-up" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucune donnée de progression</p>
                            <p class="text-sm mt-1">Générez d'abord le bilan annuel pour voir apparaître les statuts ici.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($progressions && method_exists($progressions, 'links'))
    <div class="mt-4">
        {{ $progressions->links() }}
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection