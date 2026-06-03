@extends('layouts.admin')

@section('title', 'Grille Hebdomadaire')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Planning</p>
            <h1 class="text-2xl font-bold text-slate-900">Grille hebdomadaire</h1>
            <p class="mt-1 text-sm text-slate-500">Affichage des cours programmés pour la semaine sélectionnée.</p>
        </div>
        <div class="inline-flex items-center gap-2">
            <a href="{{ route('admin.programme-cours.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                Liste des cours
            </a>

            <a href="{{ route('admin.programme-cours.create', ['date' => $dateDebut->format('Y-m-d')]) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Ajouter un cours
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.programme-cours.grille') }}" class="admin-toolbar">
        <div class="admin-toolbar-grid">
            <div>
                <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-1">Semaine du</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut', $dateDebut->format('Y-m-d')) }}" class="admin-filter-input" />
            </div>
            <div>
                <label for="filiere_id" class="block text-sm font-medium text-slate-700 mb-1">Filière</label>
                <select name="filiere_id" id="filiere_id" class="admin-filter-select">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="niveau_id" class="block text-sm font-medium text-slate-700 mb-1">Niveau</label>
                <select name="niveau_id" id="niveau_id" class="admin-filter-select">
                    <option value="">Tous les niveaux</option>
                    @foreach($niveaux as $niveau)
                    <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="semestre_id" class="block text-sm font-medium text-slate-700 mb-1">Semestre</label>
                <select name="semestre_id" id="semestre_id" class="admin-filter-select">
                    <option value="">Tous les semestres</option>
                    @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-toolbar-actions">
                <button type="submit" class="admin-filter-button">Filtrer</button>
            </div>
        </div>
    </form>

    <div class="admin-shell">
        <div class="admin-table-wrap overflow-x-auto">
            <table class="admin-table min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50">Créneau</th>
                        @foreach($jours as $jour)
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50">{{ $jour }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($creneaux as $creneau)
                    <tr class="admin-row">
                        <td class="px-4 py-4 align-top text-slate-900 font-semibold whitespace-nowrap">
                            {{ is_string($creneau->heure_debut) ? substr($creneau->heure_debut, 0, 5) : $creneau->heure_debut->format('H:i') }} - {{ is_string($creneau->heure_fin) ? substr($creneau->heure_fin, 0, 5) : $creneau->heure_fin->format('H:i') }}
                        </td>
                        @foreach($jours as $jour)
                        @php
                        $key = $jour . '-' . $creneau->id;
                        $programme = $programmes[$key] ?? null;
                        @endphp
                        <td class="px-4 py-4 align-top">
                            @if($programme)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <div class="text-sm font-semibold text-slate-900">{{ $programme->matiere?->libelle ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $programme->professeur?->nom ?? 'Non assigné' }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $programme->salle?->nom_salle ?? 'Salle non définie' }}</div>
                                <div class="mt-2 text-xs text-slate-500">{{ $programme->filiere?->libelle ?? '' }} {{ $programme->niveau?->libelle ?? '' }}</div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('admin.programme-cours.edit', ['programme' => $programme->id]) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-700 hover:bg-slate-100 transition">Modifier</a>
                                </div>
                            </div>
                            @else
                            <div class="text-slate-400 text-xs italic">Vide</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($jours) + 1 }}" class="px-4 py-12 text-center text-slate-500">
                            Aucune séance programmée pour cette semaine.
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