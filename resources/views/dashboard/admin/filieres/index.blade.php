@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Filières</h1>
            <p class="text-sm text-slate-500">Gérez les filières proposées dans l’université.</p>
        </div>
        <a href="{{ route('admin.filieres.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
            Ajouter une filière
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-base font-semibold text-slate-900">Liste des filières</h2>
            <p class="text-sm text-slate-500">Voir, modifier ou supprimer les filières existantes.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nom</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($filieres as $filiere)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $filiere->libelle }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $filiere->description ?? 'Aucune description' }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.filieres.edit', $filiere) }}"
                                class="inline-flex rounded-xl bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                                Modifier
                            </a>
                            <form action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Confirmer la suppression de cette filière ?')"
                                    class="inline-flex rounded-xl bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-200">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">Aucune filière n'a encore été créée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 text-right text-sm text-slate-500">
            {{ $filieres->links() }}
        </div>
    </div>
</div>
@endsection