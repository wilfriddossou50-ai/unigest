@extends('layouts.admin')

@section('title', 'Ajouter un Créneau Horaire')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.creneaux.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition mb-3">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour à la liste
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Ajouter un Créneau</h1>
        <p class="text-slate-600 mt-1">Définissez une nouvelle plage horaire globale pour la planification.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.creneaux.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="heure_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Heure de Début <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                            id="heure_debut"
                            name="heure_debut"
                            value="{{ old('heure_debut') }}"
                            class="w-full px-4 py-2.5 rounded-xl border @error('heure_debut') border-red-300 focus:border-red-500 focus:ring-red-200 @else border-slate-200 focus:border-blue-500 focus:ring-blue-200 @enderror bg-white text-slate-900 font-mono focus:outline-none focus:ring-4 transition duration-150"
                            required>

                        @error('heure_debut')
                        <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_fin" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Heure de Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                            id="heure_fin"
                            name="heure_fin"
                            value="{{ old('heure_fin') }}"
                            class="w-full px-4 py-2.5 rounded-xl border @error('heure_fin') border-red-300 focus:border-red-500 focus:ring-red-200 @else border-slate-200 focus:border-blue-500 focus:ring-blue-200 @enderror bg-white text-slate-900 font-mono focus:outline-none focus:ring-4 transition duration-150"
                            required>

                        @error('heure_fin')
                        <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="flex items-center h-5">
                        <input id="est_actif"
                            name="est_actif"
                            type="checkbox"
                            value="1"
                            {{ old('est_actif', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition duration-150 cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm cursor-pointer select-none">
                        <label for="est_actif" class="font-semibold text-slate-800 cursor-pointer">Créneau actif dès la création</label>
                        <p class="text-slate-500 mt-0.5">Le laisser activé permet de l'utiliser immédiatement pour la génération d'emplois du temps.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.creneaux.index') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-medium transition duration-150">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-150">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Créer le créneau
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