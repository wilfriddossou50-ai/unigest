@extends('layouts.admin')

@section('title', 'Modifier la Salle')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.salles.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition mb-3">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour à la liste
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Modifier la Salle</h1>
        <p class="text-slate-600 mt-1">Mettez à jour les informations de la salle : <span class="font-semibold text-slate-800">{{ $salle->nom_salle }}</span></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.salles.update', $salle) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nom_salle" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nom de la salle <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="nom_salle"
                        name="nom_salle"
                        value="{{ old('nom_salle', $salle->nom_salle) }}"
                        class="w-full px-4 py-2.5 rounded-xl border @error('nom_salle') border-red-300 focus:border-red-500 focus:ring-red-200 @else border-slate-200 focus:border-blue-500 focus:ring-blue-200 @enderror bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 transition duration-150"
                        placeholder="Ex: Salle 101, Amphi A, Labo Informatique"
                        required>

                    @error('nom_salle')
                    <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="statut"
                            name="statut"
                            class="w-full px-4 py-2.5 rounded-xl border @error('statut') border-red-300 focus:border-red-500 focus:ring-red-200 @else border-slate-200 focus:border-blue-500 focus:ring-blue-200 @enderror bg-white text-slate-900 appearance-none focus:outline-none focus:ring-4 transition duration-150"
                            required>
                            <option value="Actif" {{ old('statut', $salle->statut) === 'Actif' ? 'selected' : '' }}>Actif</option>
                            <option value="Inactif" {{ old('statut', $salle->statut) === 'Inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="Maintenance" {{ old('statut', $salle->statut) === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    @error('statut')
                    <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.salles.index') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-medium transition duration-150">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-150">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialisation des icônes Lucide
    lucide.createIcons();
</script>
@endsection