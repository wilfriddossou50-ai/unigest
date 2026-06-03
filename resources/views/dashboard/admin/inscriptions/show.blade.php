@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Détails de l'inscription</h1>
            <p class="mt-1 text-sm text-slate-500">Examinez et traitez cette demande d'inscription.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.inscriptions.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                ← Retour
            </a>
            <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold
                {{ $inscription->statut === 'en_attente' ? 'bg-amber-100 text-amber-700' : ($inscription->statut === 'approuvee' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                {{ ucwords(str_replace('_', ' ', $inscription->statut)) }}
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Informations Personnelles -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="mb-6 text-lg font-semibold text-slate-900">Informations personnelles</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-slate-600">Nom complet</dt>
                    <dd class="mt-1 text-base font-semibold text-slate-900">{{ $inscription->nom }} {{ $inscription->prenom }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-600">Email</dt>
                    <dd class="mt-1 text-base text-slate-700">{{ $inscription->user?->email ?? 'N/A' }}</dd>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-600">Sexe</dt>
                        <dd class="mt-1 text-base text-slate-700">{{ $inscription->sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-600">Date de naissance</dt>
                        <dd class="mt-1 text-base text-slate-700">{{ optional($inscription->date_naissance)->format('d/m/Y') ?? 'N/A' }}</dd>
                    </div>
                </div>
            </dl>
        </div>

        <!-- Détails Académiques -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-6 text-lg font-semibold text-slate-900">Détails académiques</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-slate-600">Filière</dt>
                    <dd class="mt-1 text-base font-semibold text-slate-900">{{ $inscription->filiere?->libelle ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-600">Date de soumission</dt>
                    <dd class="mt-1 text-base text-slate-700">{{ $inscription->created_at->translatedFormat('d F Y \\à H:i') }}</dd>
                </div>
                @if($inscription->motif_refus)
                <div class="rounded-2xl bg-rose-50 p-4">
                    <dt class="text-sm font-medium text-rose-900">Motif de refus</dt>
                    <dd class="mt-2 text-sm text-rose-800">{{ $inscription->motif_refus }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Actions -->
    @if($inscription->statut === 'en_attente')
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Approuver -->
        <form method="POST" action="{{ route('admin.inscriptions.approve', $inscription) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100">
                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900">Approuver cette inscription</h3>
                    <p class="mt-1 text-sm text-slate-600">L'étudiant pourra accéder à son tableau de bord et commencer ses cours.</p>
                </div>
            </div>
            <button type="submit" class="mt-6 w-full rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                ✓ Approuver l'inscription
            </button>
        </form>

        <!-- Refuser -->
        <form method="POST" action="{{ route('admin.inscriptions.refuse', $inscription) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-rose-100">
                    <svg class="w-6 h-6 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900">Refuser cette inscription</h3>
                    <p class="mt-1 text-sm text-slate-600">Expliquez le motif du refus qui sera notifié au candidat.</p>
                </div>
            </div>
            <textarea name="motif_refus" rows="3" placeholder="Motif du refus..." class="mt-4 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100" required>{{ old('motif_refus', $inscription->motif_refus) }}</textarea>
            @error('motif_refus')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
            <button type="submit" class="mt-6 w-full rounded-2xl bg-rose-600 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700 transition">
                ✗ Refuser l'inscription
            </button>
        </form>
    </div>
    @else
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
        <p class="text-center text-slate-600">Cette inscription a déjà été traitée et ne peut pas être modifiée.</p>
    </div>
    @endif
</div>
@endsection
