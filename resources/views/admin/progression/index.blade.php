@extends('layouts.admin')

@section('title', 'Suivi de la Progression')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Progression</p>
            <h1 class="text-2xl font-bold text-slate-900">Progression des Etudiants</h1>
            <p class="mt-1 text-sm text-slate-500">Consultez l'evolution historique et le statut d'inscription de chaque etudiant.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 border border-slate-200">
            <i data-lucide="info" class="w-3 h-3 inline-block mr-1"></i>
            Synchronise automatiquement avec le bilan annuel.
        </span>
    </div>

    <div class="admin-toolbar mb-6">
        <form method="GET" action="{{ route('admin.progression.index') }}" class="admin-toolbar-grid">
            <input
                id="q"
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Nom, prenom, email, numero"
                class="admin-filter-input xl:col-span-2" />

            <select id="niveau" name="niveau" class="admin-filter-select">
                <option value="">Tous les niveaux</option>
                @foreach($niveaux as $niveau)
                    <option value="{{ $niveau->id }}" {{ request('niveau') == $niveau->id ? 'selected' : '' }}>
                        {{ $niveau->libelle ?? $niveau->code ?? 'N/A' }}
                    </option>
                @endforeach
            </select>

            <select id="annee" name="annee" class="admin-filter-select">
                <option value="">Toutes les annees</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                        {{ $annee }}
                    </option>
                @endforeach
            </select>

            <select id="statut" name="statut" class="admin-filter-select">
                <option value="">Tous les statuts</option>
                <option value="diplome" {{ request('statut') === 'diplome' ? 'selected' : '' }}>Diplome</option>
                <option value="passage" {{ request('statut') === 'passage' ? 'selected' : '' }}>Passage</option>
                <option value="redoublement" {{ request('statut') === 'redoublement' ? 'selected' : '' }}>Redoublement</option>
                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
            </select>

            <div class="admin-toolbar-actions xl:col-span-5">
                <button type="submit" class="admin-filter-button">
                    Filtrer
                </button>
                <a href="{{ route('admin.progression.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Reinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap max-h-[calc(100vh-320px)] overflow-y-auto">
            <table class="admin-table min-w-[720px] text-sm">
                <thead class="sticky top-0 z-20">
                    <tr>
                        <th>Etudiant</th>
                        <th class="whitespace-nowrap">Niveau concerne</th>
                        <th class="text-center whitespace-nowrap">Annee Academique</th>
                        <th class="text-center whitespace-nowrap">Statut de Progression</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($progressions ?? [] as $progression)
                    <tr class="admin-row">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($progression->etudiant?->user?->nom ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900 truncate">
                                        {{ $progression->etudiant?->user?->nom ?? 'Inconnu' }} {{ $progression->etudiant?->user?->prenom ?? '' }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $progression->etudiant?->numero_etudiant ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                {{ $progression->niveau?->libelle ?? $progression->niveau?->code ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="text-center text-sm font-medium text-slate-600">
                            {{ $progression->annee_academique }}
                        </td>

                        <td class="text-center text-sm">
                            @if($progression->statut === 'diplome')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Diplome(e)
                            </span>
                            @elseif($progression->statut === 'passage')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Passage au niveau superieur
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
                            <p class="text-lg font-medium text-slate-900">Aucune donnee de progression</p>
                            <p class="text-sm mt-1">Generez d'abord le bilan annuel pour voir apparaitre les statuts ici.</p>
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
