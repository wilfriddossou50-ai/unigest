@extends('layouts.admin')

@section('title', 'Gestion des Filières')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Filières</h1>
            <p class="text-slate-600 mt-1">Gérez les filières académiques</p>
        </div>
        <a href="{{ route('admin.filieres.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter filière
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Filières</p>
                <p class="text-base font-semibold text-slate-900">Catalogue des filières</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                {{ $filieres->count() }} filière(s)
            </span>
        </div>
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Code</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Description</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Étudiants</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($filieres ?? [] as $filiere)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $filiere->libelle }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $filiere->code ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 max-w-sm truncate">{{ $filiere->description ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $filiere->etudiants->count() ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.filieres.edit', $filiere) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                        Aucune filière trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection