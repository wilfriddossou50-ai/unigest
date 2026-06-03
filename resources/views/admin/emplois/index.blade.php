@extends('layouts.admin')

@section('title', 'Gestion de l\'Emploi du Temps')

@section('content')
<div class="admin-page">
    @if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Planification</p>
            <h1 class="text-2xl font-bold text-slate-900">Emploi du Temps</h1>
            <p class="mt-1 text-sm text-slate-500">Gerez les seances par niveau, semestre et jour.</p>
        </div>
        <a href="{{ route('admin.emplois.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white font-semibold transition hover:bg-blue-700">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter une seance
        </a>
    </div>

    <div class="admin-toolbar mb-6">
        <form action="{{ route('admin.emplois.index') }}" method="GET" class="admin-toolbar-grid">
            <select name="niveau_id" class="admin-filter-select">
                <option value="">Tous les niveaux</option>
                @foreach($niveaux as $niveau)
                <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                @endforeach
            </select>
            <select name="semestre_id" class="admin-filter-select">
                <option value="">Tous les semestres</option>
                @foreach($semestres as $semestre)
                <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                @endforeach
            </select>
            <select name="jour" class="admin-filter-select">
                <option value="">Tous les jours</option>
                @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'] as $jour)
                <option value="{{ $jour }}" {{ request('jour') == $jour ? 'selected' : '' }}>{{ $jour }}</option>
                @endforeach
            </select>
            <button type="submit" class="admin-filter-button">Appliquer</button>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[960px] text-sm">
                <thead>
                    <tr>
                        <th>Matire</th>
                        <th>Professeur</th>
                        <th>Jour</th>
                        <th>Heure</th>
                        <th class="hidden lg:table-cell">Salle</th>
                        <th class="hidden lg:table-cell">Semestre</th>
                        <th class="hidden lg:table-cell">Niveau</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($emplois as $emploi)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">{{ $emploi->matiere?->libelle ?? 'N/A' }}</td>
                        <td class="text-slate-600">{{ $emploi->professeur?->nom ?? '' }} {{ $emploi->professeur?->prenom ?? '' }}</td>
                        <td class="text-slate-600">{{ ucfirst($emploi->jour ?? 'N/A') }}</td>
                        <td class="text-slate-900">{{ $emploi->heure_debut ?? 'N/A' }} - {{ $emploi->heure_fin ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600">{{ $emploi->salle ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600">{{ $emploi->semestre?->libelle ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600">{{ $emploi->niveau?->libelle ?? 'N/A' }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.emplois.edit', $emploi) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Modifier</a>
                                <form action="{{ route('admin.emplois.destroy', $emploi) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sr de vouloir supprimer cette sance ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-500">Aucune sance enregistre pour l'instant.</td>
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
