@extends('layouts.admin')

@section('title', 'Gestion des Filires')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Rfrentiel</p>
            <h1 class="text-2xl font-bold text-slate-900">Filires</h1>
            <p class="mt-1 text-sm text-slate-500">Grez les filires acadmiques.</p>
        </div>
        <a href="{{ route('admin.filieres.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter filire
        </a>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[720px] text-sm">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th class="hidden lg:table-cell">Description</th>
                        <th>Étudiants</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($filieres ?? [] as $filiere)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">{{ $filiere->libelle }}</td>
                        <td class="text-slate-600">{{ $filiere->code ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600 max-w-sm truncate">{{ $filiere->description ?? 'N/A' }}</td>
                        <td class="font-medium text-slate-900">{{ $filiere->etudiants->count() ?? 0 }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.filieres.edit', $filiere) }}" class="text-blue-600 hover:text-blue-700">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sr ?')">
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
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Aucune filire trouve
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
