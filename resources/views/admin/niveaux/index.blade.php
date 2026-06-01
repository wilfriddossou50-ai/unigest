@extends('layouts.admin')

@section('title', 'Gestion des Niveaux')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Niveaux</h1>
            <p class="text-slate-600 mt-1">Gérez les niveaux d'études</p>
        </div>
        <a href="{{ route('admin.niveaux.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Ajouter niveau
        </a>
    </div>

    <!-- Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($niveaux ?? [] as $niveau)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $niveau->libelle }}</h3>
                    <p class="text-sm text-slate-500 mt-1">Code: {{ $niveau->code ?? 'N/A' }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Niveau
                </span>
            </div>

            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Étudiants:</span>
                    <span class="font-semibold text-slate-900">{{ $niveau->etudiants->count() ?? 0 }}</span>
                </div>
            </div>

            <div class="flex gap-2 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.niveaux.edit', $niveau) }}" class="flex-1 text-center text-blue-600 hover:text-blue-700 py-2 text-sm font-medium">
                    Éditer
                </a>
                <form action="{{ route('admin.niveaux.destroy', $niveau) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-red-600 hover:text-red-700 py-2 text-sm font-medium">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-slate-500">Aucun niveau trouvé</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection