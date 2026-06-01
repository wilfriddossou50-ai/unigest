@extends('layouts.admin')

@section('title', 'Gestion de l\'Emploi du Temps')

@section('content')
<div class="p-8">
    @if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Emploi du Temps</h1>
            <p class="text-slate-600 mt-1">Gérez les séances par niveau, semestre et jour.</p>
        </div>
        <a href="{{ route('admin.emplois.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter une séance
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
        <form action="{{ route('admin.emplois.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Niveau</label>
                <select name="niveau_id" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les niveaux</option>
                    @foreach($niveaux as $niveau)
                    <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Semestre</label>
                <select name="semestre_id" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les semestres</option>
                    @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Jour</label>
                <select name="jour" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les jours</option>
                    @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'] as $jour)
                    <option value="{{ $jour }}" {{ request('jour') == $jour ? 'selected' : '' }}>{{ $jour }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">Appliquer</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Matière</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Professeur</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Jour</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Heure</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Salle</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Semestre</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900">Niveau</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-900 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($emplois as $emploi)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $emploi->matiere->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $emploi->professeur->nom ?? '' }} {{ $emploi->professeur->prenom ?? '' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($emploi->jour ?? 'N/A') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-900">{{ $emploi->heure_debut ?? 'N/A' }} - {{ $emploi->heure_fin ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $emploi->salle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $emploi->semestre->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $emploi->niveau->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-right">
                        <a href="{{ route('admin.emplois.edit', $emploi) }}" class="mr-3 text-blue-600 hover:text-blue-700">Modifier</a>
                        <form action="{{ route('admin.emplois.destroy', $emploi) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-500">Aucune séance enregistrée pour l'instant.</td>
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