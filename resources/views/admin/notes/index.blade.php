@extends('layouts.admin')

@section('title', 'Gestion des Notes')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Administration</p>
            <h1 class="text-2xl font-bold text-slate-900">Notes</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez les notes des étudiants.</p>
        </div>
        <a href="{{ route('admin.notes.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter note
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="admin-toolbar mb-6">
        <form method="GET" action="{{ route('admin.notes.index') }}" class="admin-toolbar-grid">
            <select name="matiere_id" class="admin-filter-select">
                <option value="">Toutes les matières</option>
                @foreach($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                    {{ $matiere->libelle }}
                </option>
                @endforeach
            </select>
            <select name="etudiant_id" class="admin-filter-select">
                <option value="">Tous les étudiants</option>
                @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->id }}" {{ request('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                    {{ $etudiant->user?->nom ?? '' }} {{ $etudiant->user?->prenom ?? '' }}
                </option>
                @endforeach
            </select>
            <select name="semestre_id" class="admin-filter-select">
                <option value="">Tous les semestres</option>
                @foreach($semestres as $semestre)
                <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>
                    {{ $semestre->libelle }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="admin-filter-button">Filtrer</button>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap max-h-96 overflow-y-auto">
            <table class="admin-table text-sm">
                <thead class="sticky top-0 z-10">
                    <tr>
                        <th class="sticky left-0 z-10 bg-slate-50">Etudiant</th>
                        <th>Matiere</th>
                        <th class="hidden lg:table-cell">Filiere</th>
                        <th>CC</th>
                        <th>Examen</th>
                        <th>Finale</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($notes ?? [] as $note)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900 sticky left-0 bg-white">{{ $note->etudiant?->user?->nom ?? '' }} {{ $note->etudiant?->user?->prenom ?? '' }}</td>
                        <td class="text-slate-600 truncate">{{ $note->matiere?->libelle ?? 'N/A' }}</td>
                        <td class="hidden lg:table-cell text-slate-600 truncate">{{ $note->matiere?->module?->filiere?->libelle ?? 'N/A' }}</td>
                        <td class="font-medium text-slate-900">{{ $note->cc ?? '-' }}/20</td>
                        <td class="font-medium text-slate-900">{{ $note->examen ?? '-' }}/20</td>
                        <td class="font-semibold text-slate-900">{{ $note->note_finale ?? '-' }}/20</td>
                        <td>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ in_array($note->statut, ['validee', 'rattrapage_valide', 'reprise_valide'], true) ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ ucfirst($note->statut ?? 'en attente') }}
                            </span>
                        </td>
                        <td>
                            @if(!in_array($note->statut, ['validee', 'rattrapage_valide', 'reprise_valide'], true))
                            <a href="{{ route('admin.notes.create', [
                                'etudiant_id' => $note->etudiant_id, 
                                'matiere_id' => $note->matiere_id, 
                                'type' => ($note->statut === 'reprise' ? 'reprise' : 'rattrapage')
                                ]) }}"
                                class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                Régulariser
                            </a>
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                            Aucune note trouvee
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $notes->links() }}
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection