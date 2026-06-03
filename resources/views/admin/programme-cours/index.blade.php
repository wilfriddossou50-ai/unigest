@extends('layouts.admin')

@section('title', 'Liste des cours programmés')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Planning</p>
            <h1 class="text-2xl font-bold text-slate-900">Cours programmés</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez, filtrez et modifiez l'ensemble des enseignements planifiés.</p>
        </div>
        <a href="{{ route('admin.programme-cours.grille') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            Vue Grille Hebdomadaire
        </a>
    </div>

    <div class="admin-toolbar mb-6">
        <form action="{{ route('admin.programme-cours.index') }}" method="GET" class="admin-toolbar-grid">
            <select id="filiere_id" name="filiere_id" class="admin-filter-select">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $filiere)
                <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                    {{ $filiere->libelle }}
                </option>
                @endforeach
            </select>

            <select id="niveau_id" name="niveau_id" class="admin-filter-select">
                <option value="">Tous les niveaux</option>
                @foreach($niveaux as $niveau)
                <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>
                    {{ $niveau->libelle }}
                </option>
                @endforeach
            </select>

            <select id="semestre_id" name="semestre_id" class="admin-filter-select">
                <option value="">Tous les semestres</option>
                @foreach($semestres as $semestre)
                <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>
                    {{ $semestre->libelle }}
                </option>
                @endforeach
            </select>

            <div class="admin-toolbar-actions">
                <button type="submit" class="admin-filter-button">Filtrer</button>
                <a href="{{ route('admin.programme-cours.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[980px] text-sm">
                <thead>
                    <tr>
                        <th>Date &amp; jour</th>
                        <th>Créneau</th>
                        <th>Matière</th>
                        <th>Enseignant</th>
                        <th class="hidden lg:table-cell">Salle</th>
                        <th class="hidden xl:table-cell">Filière / niveau</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($programmes as $programme)
                    <tr class="admin-row">
                        <td class="whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900">{{ $programme->date_programme->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $programme->jour_semaine }}</div>
                        </td>
                        <td class="whitespace-nowrap font-mono text-xs text-slate-600 font-medium">
                            {{ $programme->creneau?->libelle ?? ($programme->creneau?->heure_debut ? substr($programme->creneau->heure_debut, 0, 5) . ' - ' . substr($programme->creneau->heure_fin, 0, 5) : 'N/A') }}
                        </td>
                        <td>
                            <div class="text-sm font-bold text-slate-900 line-clamp-1">{{ $programme->matiere?->libelle ?? 'N/A' }}</div>
                        </td>
                        <td class="whitespace-nowrap">
                            <div class="text-sm text-slate-700 font-medium">{{ $programme->professeur?->nom_complet ?? $programme->professeur?->nom ?? 'N/A' }}</div>
                        </td>
                        <td class="hidden lg:table-cell whitespace-nowrap">
                            @if($programme->salle)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg">
                                {{ $programme->salle->nom_salle }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400 italic">N/A</span>
                            @endif
                        </td>
                        <td class="hidden xl:table-cell whitespace-nowrap">
                            <div class="text-sm text-slate-800 font-medium">{{ $programme->filiere?->libelle ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $programme->niveau?->libelle ?? 'N/A' }}</div>
                        </td>
                        <td class="whitespace-nowrap">
                            @if($programme->statut === 'Programmé')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Programmé
                            </span>
                            @elseif(in_array($programme->statut, ['Modifié', 'Modifie']))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Modifié
                            </span>
                            @elseif($programme->statut === 'Annulé')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Annulé
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                {{ $programme->statut ?? '—' }}
                            </span>
                            @endif
                        </td>
                        <td class="text-end whitespace-nowrap text-sm font-medium">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.programme-cours.edit', $programme) }}"
                                    class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 rounded-lg transition duration-150"
                                    title="Modifier le cours">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>

                                @if($programme->statut !== 'Annulé')
                                <form action="{{ route('admin.programme-cours.annuler', $programme) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-rose-600 bg-slate-50 hover:bg-rose-50 rounded-lg transition duration-150"
                                        onclick="return confirm('Êtes-vous certain de vouloir annuler ce cours ?\nDes notifications de modification seront envoyes aux tudiants.')"
                                        title="Annuler le cours">
                                        <i data-lucide="calendar-off" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="layers-3" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-base font-medium text-slate-900">Aucun cours planifié</p>
                            <p class="text-sm text-slate-400 mt-0.5">Ajustez vos filtres de recherche ou passez par la vue grille pour ajouter un cours.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($programmes->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $programmes->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection