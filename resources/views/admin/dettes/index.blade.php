@extends('layouts.admin')

@section('title', 'Gestion des Dettes')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Suivi</p>
            <h1 class="text-2xl font-bold text-slate-900">Dettes acadmiques</h1>
            <p class="mt-1 text-sm text-slate-500">Suivi et leve manuelle des matires non encore valides.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-600 font-medium">Dettes en cours</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['en_cours'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-600 font-medium">Étudiants concerns</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['etudiants'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-600 font-medium">Dettes leves</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['levees'] }}</p>
        </div>
    </div>

    <div class="admin-toolbar mb-6">
        <form method="GET" action="{{ route('admin.dettes.index') }}" class="admin-toolbar-grid">
            <select name="statut" class="admin-filter-select">
                <option value="">Tous les statuts</option>
                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="levee" {{ request('statut') === 'levee' ? 'selected' : '' }}>Leve</option>
            </select>
            <button type="submit" class="admin-filter-button">Filtrer</button>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap max-h-96 overflow-y-auto">
            <table class="admin-table min-w-[760px] text-sm">
                <thead class="sticky top-0 z-20">
                    <tr>
                        <th>Étudiant</th>
                        <th>Matire</th>
                        <th class="hidden md:table-cell">Module</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dettes as $dette)
                    <tr class="admin-row">
                        <td class="font-medium text-slate-900">
                            {{ $dette->etudiant?->user?->nom }} {{ $dette->etudiant?->user?->prenom }}
                        </td>
                        <td class="text-slate-700">{{ $dette->matiere?->libelle ?? '—' }}</td>
                        <td class="hidden md:table-cell text-slate-700">{{ $dette->module?->libelle ?? '—' }}</td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $dette->statut === 'en_cours' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $dette->statut === 'en_cours' ? 'bg-red-600' : 'bg-emerald-600' }}"></span>
                                {{ ucfirst($dette->statut) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($dette->statut === 'en_cours')
                            <form action="{{ route('admin.dettes.lever', $dette) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition">Lever</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Aucune dette trouve
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
