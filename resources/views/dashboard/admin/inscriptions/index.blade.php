@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Inscriptions</h2>
            <p class="text-sm text-slate-500">Toutes les demandes d’inscription des étudiants.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-700">
            {{ $inscriptions->total() }} demandes
        </span>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                    <th class="px-6 py-4 font-semibold text-slate-600">Candidat</th>
                    <th class="px-6 py-4 font-semibold text-slate-600">Filière</th>
                    <th class="px-6 py-4 font-semibold text-slate-600">Statut</th>
                    <th class="px-6 py-4 font-semibold text-slate-600">Soumis le</th>
                    <th class="px-6 py-4 font-semibold text-slate-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach($inscriptions as $inscription)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-900">
                        {{ $inscription->nom }} {{ $inscription->prenom }}
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
                        <a href="{{ route('admin.inscriptions.show', $inscription) }}"
                            class="text-sm font-semibold text-blue-700 hover:text-blue-900">Voir</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between text-sm text-slate-500">
        <div>
            {{ $inscriptions->links() }}
        </div>
    </div>
</div>
@endsection