@extends('layouts.admin')

@section('title', 'Gestion des Professeurs')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Ressources humaines</p>
            <h1 class="text-2xl font-bold text-slate-900">Professeurs</h1>
            <p class="mt-1 text-sm text-slate-500">Grez le corps enseignant.</p>
        </div>
        <a href="{{ route('admin.professeurs.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter professeur
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[720px] text-sm">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th class="hidden md:table-cell">Email</th>
                        <th class="hidden lg:table-cell">Spcialit</th>
                        <th>Matires</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($professeurs ?? [] as $professeur)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">{{ $professeur->nom }} {{ $professeur->prenom }}</td>
                        <td class="hidden md:table-cell text-slate-600 truncate">{{ $professeur->email }}</td>
                        <td class="hidden lg:table-cell text-slate-600 truncate">{{ $professeur->specialite ?? 'N/A' }}</td>
                        <td class="font-medium text-slate-900">{{ $professeur->matieres->count() ?? 0 }}</td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $professeur->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($professeur->statut) }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.professeurs.edit', $professeur) }}" class="text-blue-600 hover:text-blue-700">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.professeurs.destroy', $professeur) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            Aucun professeur trouv
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
