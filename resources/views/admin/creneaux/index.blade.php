@extends('layouts.admin')

@section('title', 'Gestion des Creneaux Horaires')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Organisation</p>
            <h1 class="text-2xl font-bold text-slate-900">Gestion des Creneaux Horaires</h1>
            <p class="mt-1 text-sm text-slate-500">Definissez les tranches horaires utilisees pour les cours et les examens.</p>
        </div>
        <a href="{{ route('admin.creneaux.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Ajouter un creneau
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[680px] text-sm">
                <thead>
                    <tr>
                        <th>Heure de Debut</th>
                        <th>Heure de Fin</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($creneaux as $creneau)
                    <tr class="admin-row">
                        <td class="font-bold text-slate-900">
                            <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 font-mono">
                                {{ substr($creneau->heure_debut, 0, 5) }}
                            </span>
                        </td>

                        <td class="font-bold text-slate-900">
                            <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 font-mono">
                                {{ substr($creneau->heure_fin, 0, 5) }}
                            </span>
                        </td>

                        <td>
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

                        <td class="text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.creneaux.edit', ['creneaux' => $creneau->id]) }}" class="text-slate-400 hover:text-blue-600 transition" title="Modifier le creneau">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.creneaux.destroy', ['creneaux' => $creneau->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Supprimer le creneau" onclick="return confirm('Etes-vous sur de vouloir supprimer ce creneau horaire ?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="clock" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucun creneau horaire defini</p>
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
    lucide.createIcons();
</script>
@endsection
