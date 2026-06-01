@extends('layouts.admin')

@section('title', 'Gestion des Notes')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Notes</h1>
            <p class="text-slate-600 mt-1">Gerez les notes des etudiants</p>
        </div>
        <a href="{{ route('admin.notes.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter note
        </a>
    </div>

    <form method="GET" action="{{ route('admin.notes.index') }}" class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="matiere_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes les matieres</option>
                @foreach($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                    {{ $matiere->libelle }}
                </option>
                @endforeach
            </select>
            <select name="etudiant_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les etudiants</option>
                @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->id }}" {{ request('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                    {{ $etudiant->user->nom ?? '' }} {{ $etudiant->user->prenom ?? '' }}
                </option>
                @endforeach
            </select>
            <select name="semestre_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les semestres</option>
                @foreach($semestres as $semestre)
                <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>
                    {{ $semestre->libelle }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-4 py-2 rounded-lg transition">Filtrer</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900 sticky left-0 bg-slate-50">Etudiant</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900 whitespace-nowrap">Matiere</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900 whitespace-nowrap">Filiere</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900">CC</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900">Examen</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900">Finale</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900">Statut</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($notes ?? [] as $note)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900 sticky left-0 bg-white">{{ $note->etudiant->user->nom ?? '' }} {{ $note->etudiant->user->prenom ?? '' }}</td>
                        <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ $note->matiere->libelle ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ $note->matiere->module->filiere->libelle ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $note->cc ?? '-' }}/20</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $note->examen ?? '-' }}/20</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $note->note_finale ?? '-' }}/20</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ in_array($note->statut, ['validee', 'rattrapage_valide', 'reprise_valide'], true) ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ ucfirst($note->statut ?? 'en attente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
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
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection