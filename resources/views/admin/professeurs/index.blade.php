@extends('layouts.admin')

@section('title', 'Gestion des Professeurs')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Professeurs</h1>
            <p class="text-slate-600 mt-1">Gérez le corps enseignant</p>
        </div>
        <a href="{{ route('admin.professeurs.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter professeur
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Spécialité</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matières</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($professeurs ?? [] as $professeur)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $professeur->nom }} {{ $professeur->prenom }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $professeur->email }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $professeur->specialite ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $professeur->matieres->count() ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $professeur->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($professeur->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.professeurs.edit', $professeur) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.professeurs.destroy', $professeur) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                        Aucun professeur trouvé
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