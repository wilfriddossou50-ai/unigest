@extends('layouts.admin')

@section('title', 'Affectation Professeur-Matire')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Affectations</p>
            <h1 class="text-2xl font-bold text-slate-900">Affectation Professeur-Matire</h1>
            <p class="mt-1 text-sm text-slate-500">Grez quels professeurs enseignent quelles matires.</p>
        </div>
        <a href="{{ route('admin.professeur-matiere.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nouvelle affectation
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[640px] text-sm">
                <thead>
                    <tr>
                        <th>Professeur</th>
                        <th>Matire</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($assignations ?? [] as $assignation)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">
                            {{ $assignation->professeur?->nom_complet ?? 'N/A' }}
                        </td>
                        <td class="text-slate-600">
                            {{ $assignation->matiere?->libelle ?? 'N/A' }}
                        </td>
                        <td class="text-right">
                            <form action="{{ route('admin.professeur-matiere.destroy', $assignation->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette affectation ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                            Aucune affectation trouve
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
