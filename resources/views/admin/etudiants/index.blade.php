@extends('layouts.admin')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="admin-page">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
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
    <div class="admin-toolbar">
        <div class="admin-toolbar-grid">
            <input type="text" placeholder="Rechercher un étudiant..." class="admin-filter-input">
            <select class="admin-filter-select">
                <option value="">Tous les niveaux</option>
            </select>
            <select class="admin-filter-select">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="suspendu">Suspendu</option>
                <option value="diplome">Diplômé</option>
            </select>
            <button class="admin-filter-button">Filtrer</button>
        </div>
    </div>

    <!-- Table -->
    <div class="admin-shell">
        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th class="hidden md:table-cell">Email</th>
                    <th class="hidden lg:table-cell">Filière</th>
                    <th class="hidden lg:table-cell">Niveau</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($etudiants ?? [] as $etudiant)
                <tr class="admin-row">
                    <td class="font-medium text-slate-900">{{ ($etudiant->user?->nom ?? '') . ' ' . ($etudiant->user?->prenom ?? '') }}</td>
                    <td class="text-slate-600">{{ $etudiant->numero_etudiant ?? 'N/A' }}</td>
                    <td class="hidden md:table-cell text-slate-600 truncate">{{ $etudiant->user?->email ?? 'N/A' }}</td>
                    <td class="hidden lg:table-cell text-slate-600 truncate">{{ $etudiant->filiere?->libelle ?? 'N/A' }}</td>
                    <td class="hidden lg:table-cell text-slate-600 truncate">{{ $etudiant->niveau?->libelle ?? 'N/A' }}</td>
                    <td>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                            {{ ucfirst($etudiant->statut ?? 'actif') }}
                        </span>
                    </td>
                    <td class="text-right">
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
</div>

<script>
    lucide.createIcons();
</script>
@endsection
