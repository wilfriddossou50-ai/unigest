@extends('layouts.admin')

@section('title', 'Programmation Hebdomadaire des Cours')

@section('content')
<div class="p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Programmation Hebdomadaire</h1>
            <p class="text-slate-600 mt-1">Visualisez et gérez l'affectation des cours dans la grille du planning</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.programme-cours.index') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-medium transition duration-150">
                <i data-lucide="list" class="w-4 h-4"></i>
                Vue Liste
            </a>
            <a href="{{ route('admin.programme-cours.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-150">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Ajouter un cours
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <form action="{{ route('admin.programme-cours.grille') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="w-full md:max-w-xs">
                <label for="date_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">Semaine du (Lundi)</label>
                <input type="date"
                    id="date_debut"
                    name="date_debut"
                    value="{{ $dateDebut->format('Y-m-d') }}"
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-200 bg-white text-slate-900 focus:outline-none focus:ring-4 transition duration-150">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition duration-150 w-full md:w-auto">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Afficher
                </button>

                <a href="{{ route('admin.programme-cours.grille', ['date_debut' => $dateDebut->copy()->subWeek()->format('Y-m-d')]) }}"
                    class="inline-flex items-center justify-center border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 p-2.5 rounded-xl transition duration-150"
                    title="Semaine précédente">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>

                <a href="{{ route('admin.programme-cours.grille', ['date_debut' => $dateDebut->copy()->addWeek()->format('Y-m-d')]) }}"
                    class="inline-flex items-center justify-center gap-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-medium transition duration-150 whitespace-nowrap w-full md:w-auto">
                    Semaine suivante
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-fixed min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500 w-32 border-r border-slate-200">Horaire</th>
                        @foreach($jours as $index => $jour)
                        <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500 border-r border-slate-200 last:border-r-0">
                            <div class="font-bold text-slate-900">{{ $jour }}</div>
                            <div class="text-[11px] text-slate-400 font-normal mt-0.5">
                                {{ $dateDebut->copy()->addDays($index)->format('d/m') }}
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($creneaux as $creneau)
                    <tr class="h-28">
                        <td class="p-3 text-center bg-slate-50 border-r border-slate-200 font-mono text-xs font-bold text-slate-700">
                            {{ $creneau->libelle ?? substr($creneau->heure_debut, 0, 5) . ' - ' . substr($creneau->heure_fin, 0, 5) }}
                        </td>

                        @foreach($jours as $index => $jour)
                        @php
                        $key = $jour . '-' . $creneau->id;
                        $cours = $programmes->get($key);
                        $cellDate = $dateDebut->copy()->addDays($index)->format('Y-m-d');
                        @endphp
                        <td class="p-2 border-r border-slate-200 last:border-r-0 align-top group relative bg-slate-50/30">
                            @if($cours)
                            <a href="{{ route('admin.programme-cours.edit', $cours) }}"
                                class="block h-full w-full p-2.5 rounded-xl bg-blue-50 border border-blue-200 text-left hover:bg-blue-100 hover:border-blue-300 transition duration-150 flex flex-col justify-between shadow-sm group/card">
                                <div>
                                    <div class="font-bold text-blue-900 text-xs line-clamp-1 group-hover/card:text-blue-950">
                                        {{ $cours->matiere->libelle }}
                                    </div>
                                    <div class="text-[11px] text-blue-700 font-medium mt-0.5 line-clamp-1">
                                        {{ $cours->professeur->nom_complet ?? $cours->professeur->nom }}
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-1 text-[10px] font-semibold bg-blue-200/50 text-blue-800 px-2 py-0.5 rounded-md w-max mt-2">
                                    📍 {{ $cours->salle->nom_salle ?? 'N/A' }}
                                </div>
                            </a>
                            @else
                            <a href="{{ route('admin.programme-cours.create', ['jour' => $jour, 'creneau' => $creneau->id, 'date' => $cellDate]) }}"
                                class="absolute inset-2 rounded-xl border border-dashed border-slate-200 group-hover:border-blue-400 group-hover:bg-white flex items-center justify-center opacity-0 group-hover:opacity-100 text-blue-600 shadow-sm transition-all duration-150">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </a>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($jours) + 1 }}" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="calendar-x" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucun créneau horaire configuré</p>
                            <p class="text-sm mt-1 mb-4">Vous devez ajouter des créneaux dans vos paramètres pour générer la grille.</p>
                            <a href="{{ route('admin.creneaux.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition">
                                <i data-lucide="plus" class="w-4 h-4"></i> Configurer les créneaux
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Initialisation des icônes Lucide
    lucide.createIcons();
</script>
@endsection