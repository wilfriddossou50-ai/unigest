@extends('layouts.admin')

@section('title', 'Liste des Cours Programmés')

@section('content')
<div class="p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Cours Programmés</h1>
            <p class="text-slate-600 mt-1">Gérez, filtrez et modifiez l'ensemble des enseignements planifiés.</p>
        </div>
        <div>
            <a href="{{ route('admin.programme-cours.grille') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-150">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Vue Grille Hebdomadaire
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <form action="{{ route('admin.programme-cours.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="filiere_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Filière</label>
                <select id="filiere_id" name="filiere_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm focus:border-blue-500 focus:ring-blue-200 focus:outline-none focus:ring-4 transition duration-150">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                        {{ $filiere->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="niveau_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Niveau</label>
                <select id="niveau_id" name="niveau_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm focus:border-blue-500 focus:ring-blue-200 focus:outline-none focus:ring-4 transition duration-150">
                    <option value="">Tous les niveaux</option>
                    @foreach($niveaux as $niveau)
                    <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>
                        {{ $niveau->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="semestre_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Semestre</label>
                <select id="semestre_id" name="semestre_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm focus:border-blue-500 focus:ring-blue-200 focus:outline-none focus:ring-4 transition duration-150">
                    <option value="">Tous les semestres</option>
                    @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>
                        {{ $semestre->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-150 h-[38px]">
                    Filtrer
                </button>
                <a href="{{ route('admin.programme-cours.index') }}" class="w-full inline-flex items-center justify-center gap-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 px-4 py-2 rounded-xl text-sm font-medium transition duration-150 h-[38px]">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Date & Jour</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Créneau</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Matière</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Enseignant</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Salle</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Filière / Niveau</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Statut</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($programmes as $programme)
                    <tr class="hover:bg-slate-50/50 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900">{{ $programme->date_programme->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $programme->jour_semaine }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-600 font-medium">
                            {{ $programme->creneau->libelle ?? substr($programme->creneau->heure_debut, 0, 5) . ' - ' . substr($programme->creneau->heure_fin, 0, 5) }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900 line-clamp-1">{{ $programme->matiere->libelle }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-700 font-medium">{{ $programme->professeur->nom_complet ?? $programme->professeur->nom }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($programme->salle)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg">
                                📍 {{ $programme->salle->nom_salle }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400 italic">N/A</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-800 font-medium">{{ $programme->filiere->libelle ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $programme->niveau->libelle ?? 'N/A' }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($programme->statut === 'Programmé')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Programmé
                            </span>
                            @elseif($programme->statut === 'Modifié')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Modifié
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Annulé
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
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
                                        onclick="return confirm('Êtes-vous certain de vouloir annuler ce cours ?\nDes notifications de modification seront envoyées aux étudiants.')"
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
    // Initialisation des icônes Lucide
    lucide.createIcons();
</script>
@endsection