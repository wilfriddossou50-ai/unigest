@extends('layouts.admin')

@section('title', 'Demandes d’inscription')

@section('content')
<div class="admin-page space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Demandes</p>
            <h1 class="text-2xl font-bold text-slate-900">Demandes d’inscription</h1>
            <p class="mt-1 text-sm text-slate-500">Consultez et traitez toutes les demandes des tudiants.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
            {{ $inscriptions->total() }} demande(s)
        </span>
    </div>

    <div class="admin-shell">
        <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Vue d’ensemble</h2>
                <p class="text-sm text-slate-500">Accdez rapidement au statut de chaque demande.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table min-w-[760px] text-sm">
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Filire</th>
                        <th>Statut</th>
                        <th class="hidden md:table-cell">Soumis le</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($inscriptions as $inscription)
                    <tr class="admin-row">
                        <td class="text-slate-900">
                            <div class="font-semibold">{{ $inscription->nom }} {{ $inscription->prenom }}</div>
                            <div class="text-xs text-slate-500">{{ $inscription->user?->email ?? 'N/A' }}</div>
                        </td>
                        <td class="text-slate-700">{{ $inscription->filiere?->libelle ?? 'N/A' }}</td>
                        <td>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $inscription->statut === 'en_attente' ? 'bg-amber-100 text-amber-700' : ($inscription->statut === 'approuvee' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ ucwords(str_replace('_', ' ', $inscription->statut)) }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell text-slate-500">{{ $inscription->created_at->translatedFormat('d F Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.inscriptions.show', $inscription) }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Aucune demande d'inscription trouve.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end">
        {{ $inscriptions->links() }}
    </div>
</div>
@endsection
