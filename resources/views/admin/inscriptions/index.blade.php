@extends('layouts.admin')

@section('title', 'Demandes d’inscription')

@section('content')
<div class="p-8 space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Demandes</p>
            <h1 class="text-3xl font-bold text-slate-900">Demandes d’inscription</h1>
            <p class="mt-2 text-sm text-slate-500">Consultez et traitez toutes les demandes des étudiants.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
            {{ $inscriptions->total() }} demande(s)
        </span>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Vue d’ensemble</h2>
                <p class="text-sm text-slate-500">Accédez rapidement au statut de chaque demande.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                    <th class="px-6 py-4 font-semibold uppercase tracking-[0.08em] text-slate-500">Candidat</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-[0.08em] text-slate-500">Filière</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-[0.08em] text-slate-500">Statut</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-[0.08em] text-slate-500">Soumis le</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-[0.08em] text-slate-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($inscriptions as $inscription)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-slate-900">
                        <div class="font-semibold">{{ $inscription->nom }} {{ $inscription->prenom }}</div>
                        <div class="text-xs text-slate-500">{{ $inscription->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-700">{{ $inscription->filiere?->libelle ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $inscription->statut === 'en_attente' ? 'bg-amber-100 text-amber-700' : ($inscription->statut === 'approuvee' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                            {{ ucwords(str_replace('_', ' ', $inscription->statut)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $inscription->created_at->translatedFormat('d F Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.inscriptions.show', $inscription) }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">Aucune demande d'inscription trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $inscriptions->links() }}
    </div>
</div>
@endsection