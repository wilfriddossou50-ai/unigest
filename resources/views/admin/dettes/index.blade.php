@extends('layouts.admin')

@section('title', 'Gestion des Dettes')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dettes académiques</h1>
            <p class="text-slate-600 mt-1">Suivi et levée manuelle des matières non encore validées</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- CARTE STATISTIQUE DYNAMIQUE --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 font-medium">Dettes en cours</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['en_cours'] }}</p>
                </div>
                <i data-lucide="alert-circle" class="w-10 h-10 text-red-600 opacity-20"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 font-medium">Étudiants concernés</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['etudiants'] }}</p>
                </div>
                <i data-lucide="users" class="w-10 h-10 text-orange-600 opacity-20"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 font-medium">Dettes levées</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['levees'] }}</p>
                </div>
                <i data-lucide="check-circle" class="w-10 h-10 text-green-600 opacity-20"></i>
            </div>
        </div>
    </div>

    {{-- FILTRES & TABLE --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-slate-100">
        <form method="GET" action="{{ route('admin.dettes.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center">
            <select name="statut" class="px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="levee" {{ request('statut') === 'levee' ? 'selected' : '' }}>Levée</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white font-medium px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Filtrer</button>
        </form>
    </div>

    {{-- TABLE DES DETTES --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Étudiant</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Matière</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Module</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Statut</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dettes as $dette)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $dette->etudiant?->user?->nom }} {{ $dette->etudiant?->user?->prenom }}
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $dette->matiere?->libelle ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $dette->module?->libelle ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $dette->statut === 'en_cours' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $dette->statut === 'en_cours' ? 'bg-red-600' : 'bg-emerald-600' }}"></span>
                                {{ ucfirst($dette->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dette->statut === 'en_cours')
                            <form action="{{ route('admin.dettes.lever', $dette) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-3 py-1 bg-emerald-100 text-emerald-600 rounded hover:bg-emerald-200 transition font-medium">Lever</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.dettes.destroy', $dette) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition font-medium">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Aucune dette trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $dettes->links() }}
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection