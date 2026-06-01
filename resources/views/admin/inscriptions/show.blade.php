@extends('layouts.admin')

@section('title', 'Détails de la demande')

@section('content')
<div class="p-8 space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Demande</p>
            <h1 class="text-3xl font-bold text-slate-900">Demande d'inscription</h1>
            <p class="mt-2 text-sm text-slate-500">Gérez cette inscription et mettez à jour son statut.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-4 py-2 text-sm text-slate-700">
            <span class="h-2.5 w-2.5 rounded-full {{ $inscription->statut === 'en_attente' ? 'bg-amber-500' : ($inscription->statut === 'approuvee' ? 'bg-emerald-500' : 'bg-rose-500') }}"></span>
            {{ ucwords(str_replace('_', ' ', $inscription->statut)) }}
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="space-y-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Informations sur le candidat</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Profil étudiant</h2>
                    <p class="mt-1 text-sm text-slate-500">Toutes les données saisies lors de la demande.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Nom</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->nom }} {{ $inscription->prenom }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->email }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Téléphone</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->telephone }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Filière</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->filiere?->libelle ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Année</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->annee_scolaire ?? 'N/A' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Date de création</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $inscription->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="space-y-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Actions</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Statut de la demande</h2>
                    <p class="mt-1 text-sm text-slate-500">Approuvez ou refusez cette inscription.</p>
                </div>

                @if($inscription->statut === 'en_attente')
                <div class="space-y-4">
                    <form action="{{ route('admin.inscriptions.approve', $inscription) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            Approuver la demande
                        </button>
                    </form>

                    <form action="{{ route('admin.inscriptions.refuse', $inscription) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Motif du refus *</label>
                            <textarea name="motif_refus" rows="4" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100" placeholder="Expliquez le motif du refus...">{{ old('motif_refus', $inscription->motif_refus) }}</textarea>
                            @error('motif_refus')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full rounded-3xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                            Refuser la demande
                        </button>
                    </form>
                </div>
                @else
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-sm font-semibold text-slate-900">Statut actuel</p>
                    <p class="mt-2 text-sm text-slate-600">Cette demande a été <strong>{{ ucwords(str_replace('_', ' ', $inscription->statut)) }}</strong>.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection