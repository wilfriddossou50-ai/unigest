@extends('layouts.admin')

@section('title', 'Gestion des Créneaux Horaires')

@section('content')
<div class="p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Gestion des Créneaux Horaires</h1>
            <p class="text-slate-600 mt-1">Définissez les tranches horaires utilisées pour les cours et les examens</p>
        </div>
        <div>
            <a href="{{ route('admin.creneaux.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all duration-150">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Ajouter un créneau
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-20">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Heure de Début</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Heure de Fin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($creneaux as $creneau)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-400">
                            #{{ $creneau->id }}
                        </td>

                        <td class="px-6 py-4 text-sm font-bold text-slate-900">
                            <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 font-mono">
                                {{ substr($creneau->heure_debut, 0, 5) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm font-bold text-slate-900">
                            <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 font-mono">
                                {{ substr($creneau->heure_fin, 0, 5) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            @if($creneau->est_actif)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Actif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactif
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.creneaux.edit', $creneau) }}" class="text-slate-400 hover:text-blue-600 transition" title="Modifier le créneau">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.creneaux.destroy', $creneau) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Supprimer le créneau" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce créneau horaire ?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="clock" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucun créneau horaire défini</p>
                            <p class="text-sm mt-1">Configurez les plages horaires pour planifier votre emploi du temps.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($creneaux && method_exists($creneaux, 'links'))
    <div class="mt-4">
        {{ $creneaux->links() }}
    </div>
    @endif
</div>

<script>
    // Initialisation des icônes Lucide
    lucide.createIcons();
</script>
@endsection