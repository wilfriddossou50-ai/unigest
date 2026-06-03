@extends('layouts.admin')

@section('title', 'Dettes de l\'étudiant')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <a href="{{ route('admin.dettes.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-4 inline-block">
            ← Retour aux dettes
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Dettes de {{ $etudiant->user?->nom ?? 'Étudiant' }} {{ $etudiant->user?->prenom ?? '' }}</h1>
        <p class="text-slate-600 mt-1">Matricule : {{ $etudiant->numero_etudiant ?? 'N/A' }}</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Module</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Matière</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Semestre</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($dettes ?? [] as $dette)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $dette->module?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $dette->matiere?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $dette->semestre?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $dette->statut === 'en_cours' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $dette->statut)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($dette->statut === 'en_cours')
                        <form action="{{ route('admin.dettes.lever', $dette->id) }}" method="POST" class="inline" onsubmit="return confirm('Valider cette dette ?')">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-700">
                                Lever
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-slate-400">Aucune action</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucune dette pour cet étudiant</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
