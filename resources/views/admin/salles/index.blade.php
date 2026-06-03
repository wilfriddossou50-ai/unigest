@extends('layouts.admin')

@section('title', 'Gestion des Salles')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Infrastructure</p>
            <h1 class="text-2xl font-bold text-slate-900">Gestion des Salles</h1>
            <p class="mt-1 text-sm text-slate-500">Configurez et gerez les salles de classe ou de composition.</p>
        </div>
        <a href="{{ route('admin.salles.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Ajouter une salle
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[680px] text-sm">
                <thead>
                    <tr>
                        <th>Nom de la Salle</th>
                        <th>Statut</th>
                        <th class="hidden md:table-cell">Creee le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($salles as $salle)
                    <tr class="admin-row">
                        <td class="font-semibold text-slate-900">{{ $salle->nom_salle }}</td>
                        <td>
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
                        <td class="hidden md:table-cell text-slate-600">
                            {{ $salle->created_at->format('d/m/Y') }}
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.salles.edit', $salle) }}" class="text-slate-400 hover:text-blue-600 transition" title="Modifier la salle">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.salles.destroy', $salle) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Supprimer la salle" onclick="return confirm('Etes-vous sur de vouloir desactiver cette salle ?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="door-closed" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-lg font-medium text-slate-900">Aucune salle disponible</p>
                            <p class="text-sm mt-1">Commencez par ajouter votre premiere salle de cours.</p>
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
    lucide.createIcons();
</script>
@endsection
