@extends('layouts.admin')

@section('title', 'Gestion des Salles')

@section('content')
<div class="p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Gestion des Salles</h1>
            <p class="text-slate-600 mt-1">Configurez et gérez les salles de classe ou de composition de l'établissement</p>
        </div>
        <div>
            <a href="{{ route('admin.salles.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all duration-150">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Ajouter une salle
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-20">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nom de la Salle</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Créée le</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($salles as $salle)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-400">
                            #{{ $salle->id }}
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                            {{ $salle->nom_salle }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            @if($salle->statut === 'Actif')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Actif
                            </span>
                            @elseif($salle->statut === 'Inactif')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700 border border-amber-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Maintenance
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $salle->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.salles.edit', $salle) }}" class="text-slate-400 hover:text-blue-600 transition" title="Modifier la salle">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.salles.destroy', $salle) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Supprimer la salle" onclick="return confirm('Êtes-vous sûr de vouloir désactiver cette salle ?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="door-closed" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucune salle disponible</p>
                            <p class="text-sm mt-1">Commencez par ajouter votre première salle de cours.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($salles && method_exists($salles, 'links'))
    <div class="mt-4">
        {{ $salles->links() }}
    </div>
    @endif
</div>

<script>
    // Initialisation des icônes Lucide
    lucide.createIcons();
</script>
@endsection