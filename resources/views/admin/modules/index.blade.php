@extends('layouts.admin')

@section('title', 'Gestion des Modules')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Modules</h1>
            <p class="text-slate-600 mt-1">Gérez les modules de formation</p>
        </div>
        <a href="{{ route('admin.modules.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter module
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Code</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Filière</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Semestre</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matières</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($modules ?? [] as $module)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $module->libelle }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $module->code ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $module->filiere->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $module->semestre->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $module->matieres->count() ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.modules.resultats', $module) }}" class="text-sky-600 hover:text-sky-700" title="Voir les notes & moyennes">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>

                            <a href="{{ route('admin.modules.edit', $module) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                        Aucun module trouvé
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
