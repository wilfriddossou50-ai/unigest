@extends('layouts.admin')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Étudiants</h1>
            <p class="text-slate-600 mt-1">Gérez tous les étudiants inscrits</p>
        </div>
        <a href="{{ route('admin.etudiants.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter étudiant
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <input type="text" placeholder="Rechercher un étudiant..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <select class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Tous les niveaux</option>
            </select>
            <select class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="suspendu">Suspendu</option>
                <option value="diplome">Diplômé</option>
            </select>
            <button class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Filtrer</button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matricule</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Filière</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Niveau</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($etudiants ?? [] as $etudiant)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ ($etudiant->user->nom ?? '') . ' ' . ($etudiant->user->prenom ?? '') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $etudiant->numero_etudiant ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $etudiant->user->email ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $etudiant->filiere?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $etudiant->niveau?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                            {{ ucfirst($etudiant->statut ?? 'actif') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.etudiants.edit', $etudiant) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.etudiants.destroy', $etudiant) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                        Aucun étudiant trouvé
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
