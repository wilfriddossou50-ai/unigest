@extends('layouts.public')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center px-6">
    <div class="w-full max-w-2xl bg-white border rounded-3xl shadow-sm p-10">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-slate-900">Inscription en attente</h1>
            <p class="mt-4 text-slate-600">Votre dossier est bien reçu. L’administration va examiner votre demande et vous serez informé dès que votre compte sera validé.</p>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            <div class="rounded-3xl bg-slate-50 p-6">
                <h2 class="text-sm font-semibold text-slate-900 uppercase tracking-[0.2em] mb-4">Compte</h2>
                <p class="text-sm text-slate-700">{{ $user->nom }} {{ $user->prenom }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $user->email }}</p>
            </div>

            <div class="rounded-3xl bg-slate-50 p-6">
                <h2 class="text-sm font-semibold text-slate-900 uppercase tracking-[0.2em] mb-4">Statut</h2>
                <p class="text-sm font-semibold text-emerald-700">En attente de validation</p>
                <p class="mt-2 text-sm text-slate-500">Vous recevrez une notification lorsque votre inscription sera traitée.</p>
            </div>
        </div>

        <div class="mt-10 rounded-3xl bg-slate-100 p-6 text-sm text-slate-600">
            <h3 class="font-semibold text-slate-900 mb-3">Détails de la demande</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="font-semibold">Filière</dt>
                    <dd>{{ $inscription?->filiere?->libelle ?? 'Non précisée' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Sexe</dt>
                    <dd>{{ $inscription?->sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Date de naissance</dt>
                    <dd>{{ optional($inscription?->date_naissance)->format('d/m/Y') ?? 'Non précisée' }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</section>
@endsection