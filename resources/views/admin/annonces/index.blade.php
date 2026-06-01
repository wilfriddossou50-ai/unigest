@extends('layouts.admin')

@section('title', 'Gestion des Annonces')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Annonces</h1>
            <p class="text-slate-600 mt-1">Diffusez des informations ciblées aux étudiants</p>
        </div>
        <a href="{{ route('admin.annonces.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter annonce
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
        <form method="GET" class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Type</label>
                <select name="type" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm">
                    <option value="">Tous</option>
                    <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="important" {{ request('type') === 'important' ? 'selected' : '' }}>Important</option>
                    <option value="urgent" {{ request('type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Statut</label>
                <select name="actif" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm">
                    <option value="">Tous</option>
                    <option value="true" {{ request('actif') === 'true' ? 'selected' : '' }}>Actives</option>
                    <option value="false" {{ request('actif') === 'false' ? 'selected' : '' }}>Inactives</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button class="bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-800 transition">Filtrer</button>
                <a href="{{ route('admin.annonces.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Titre</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Type</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Cible</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Période</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($annonces ?? [] as $annonce)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $annonce->titre }}</div>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ \Illuminate\Support\Str::limit($annonce->contenu, 80) }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($annonce->type) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $annonce->filiere->libelle ?? 'Toutes filières' }}
                        <br>
                        <span class="text-xs text-slate-500">{{ $annonce->niveau->libelle ?? 'Tous niveaux' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ optional($annonce->date_publication)->format('d/m/Y') ?? '—' }}
                        @if($annonce->date_expiration)
                            <br>
                            <span class="text-xs text-slate-500">Jusqu’au {{ $annonce->date_expiration->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($annonce->actif)
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.annonces.edit', $annonce) }}" class="text-blue-600 hover:text-blue-700">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.annonces.destroy', $annonce) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        Aucune annonce trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $annonces->links() }}
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
