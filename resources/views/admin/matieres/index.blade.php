@extends('layouts.admin')

@section('title', 'Gestion des Matières')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Matières</h1>
            <p class="text-slate-600 mt-1">Gérez les matières d'enseignement</p>
        </div>
        <a href="{{ route('admin.matieres.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter matière
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matière</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Code</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Filière</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Module</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Semestre</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Professeur</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($matieres ?? [] as $matiere)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $matiere->libelle }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $matiere->code ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $matiere->module->filiere->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $matiere->module->libelle ?? $matiere->module->nom ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $matiere->module->semestre->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        @if($matiere->professeurs->count() > 0)
                        {{ $matiere->professeurs->first()->nom }} {{ $matiere->professeurs->first()->prenom }}
                        @else
                        <span class="text-gray-400">À assigner</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.matieres.edit', $matiere) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.matieres.destroy', $matiere) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                        Aucune matière trouvée
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
