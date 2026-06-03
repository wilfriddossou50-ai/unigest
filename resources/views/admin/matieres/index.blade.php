@extends('layouts.admin')

@section('title', 'Gestion des Matires')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Rfrentiel</p>
            <h1 class="text-2xl font-bold text-slate-900">Matires</h1>
            <p class="mt-1 text-sm text-slate-500">Grez les matires d'enseignement.</p>
        </div>
        <a href="{{ route('admin.matieres.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter matire
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[760px] text-sm">
                <thead>
                    <tr>
                        <th>Matire</th>
                        <th>Code</th>
                        <th class="hidden md:table-cell">Filire</th>
                        <th class="hidden lg:table-cell">Module</th>
                        <th class="hidden lg:table-cell">Semestre</th>
                        <th class="hidden xl:table-cell">Professeur</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($matieres ?? [] as $matiere)
                    @php
                        $professeur = $matiere->professeurs->first();
                    @endphp
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">{{ $matiere->libelle }}</td>
                        <td class="text-slate-600">{{ $matiere->code ?? 'N/A' }}</td>
                        <td class="hidden md:table-cell text-slate-600 truncate">{{ $matiere->module?->filiere?->libelle ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600 truncate">{{ $matiere->module?->libelle ?? $matiere->module?->nom ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600 truncate">{{ $matiere->module?->semestre?->libelle ?? 'N/A' }}</td>
                        <td class="hidden xl:table-cell text-slate-600 truncate">
                            @if($professeur)
                                {{ $professeur->nom }} {{ $professeur->prenom }}
                            @else
                                <span class="text-slate-400">À assigner</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.matieres.edit', $matiere) }}" class="text-blue-600 hover:text-blue-700">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.matieres.destroy', $matiere) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sr ?')">
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
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            Aucune matire trouve
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
