@extends('layouts.admin')

@section('title', 'Affectation Professeur-Matière')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Affectations</h1>
            <p class="text-slate-600 mt-1">Gérez quels professeurs enseignent quelles matières</p>
        </div>
        <a href="{{ route('admin.professeur-matiere.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nouvelle affectation
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Professeur</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matière</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($assignations ?? [] as $assignation)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">
                        {{ $assignation->professeur->nom_complet ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $assignation->matiere->libelle ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-right">
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
                        Aucune affectation trouvée
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
