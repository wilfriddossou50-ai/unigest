@extends('layouts.admin')

@section('title', 'Gestion des Annonces')

@section('content')
<div class="admin-page">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Communication</p>
            <h1 class="text-2xl font-bold text-slate-900">Annonces</h1>
            <p class="mt-1 text-sm text-slate-500">Diffusez des informations cibles aux tudiants.</p>
        </div>
        <a href="{{ route('admin.annonces.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter annonce
        </a>
    </div>

    <div class="admin-toolbar mb-6">
        <form method="GET" class="admin-toolbar-grid">
            <select name="type" class="admin-filter-select">
                <option value="">Tous les types</option>
                <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                <option value="important" {{ request('type') === 'important' ? 'selected' : '' }}>Important</option>
                <option value="urgent" {{ request('type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
            <select name="actif" class="admin-filter-select">
                <option value="">Tous les statuts</option>
                <option value="true" {{ request('actif') === 'true' ? 'selected' : '' }}>Actives</option>
                <option value="false" {{ request('actif') === 'false' ? 'selected' : '' }}>Inactives</option>
            </select>
            <div class="admin-toolbar-actions">
                <button class="admin-filter-button">Filtrer</button>
                <a href="{{ route('admin.annonces.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Rinitialiser</a>
            </div>
        </form>
    </div>

    <div class="admin-shell">
        <div class="admin-table-wrap">
            <table class="admin-table min-w-[780px] text-sm">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Cible</th>
                        <th class="hidden lg:table-cell">Priode</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($annonces ?? [] as $annonce)
                    <tr class="admin-row">
                        <td>
                            <div class="font-semibold text-slate-900">{{ $annonce->titre }}</div>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ \Illuminate\Support\Str::limit($annonce->contenu, 80) }}</p>
                        </td>
                        <td class="text-slate-600">{{ ucfirst($annonce->type) }}</td>
                        <td class="text-slate-600">
                            {{ $annonce->filiere?->libelle ?? 'Toutes filires' }}
                            <br>
                            <span class="text-xs text-slate-500">{{ $annonce->niveau?->libelle ?? 'Tous niveaux' }}</span>
                        </td>
                        <td class="hidden lg:table-cell text-slate-600">
                            {{ optional($annonce->date_publication)->format('d/m/Y') ?? '—' }}
                            @if($annonce->date_expiration)
                                <br>
                                <span class="text-xs text-slate-500">Jusqu’au {{ $annonce->date_expiration->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($annonce->actif)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.annonces.edit', $annonce) }}" class="text-blue-600 hover:text-blue-700">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.annonces.destroy', $annonce) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sr ?')">
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
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">Aucune annonce trouve</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $annonces->links() }}
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
