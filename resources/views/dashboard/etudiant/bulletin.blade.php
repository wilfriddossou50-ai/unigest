@extends('layouts.etudiant')

@section('title', 'Résultats Officiels')
@section('subtitle', 'Consultez votre progression, vos moyennes et vos décisions académiques')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-8 pb-8">

    <!-- HEADER IMPRESSION -->
    <div class="hidden print:block mb-8 text-center border-b-2 border-slate-900 pb-6">
        <h1 class="text-3xl font-black uppercase tracking-wider">Relevé de Résultats Officiel</h1>
        <p class="text-xl mt-3 font-bold">{{ $user->nom }} {{ $user->prenom }}</p>
        <p class="text-md text-slate-700 mt-1">Numéro Étudiant : {{ $etudiant->numero_etudiant ?? 'N/A' }} | Filière : {{ $etudiant->filiere?->libelle ?? '' }}</p>
        <p class="text-sm text-slate-500 mt-1">Année Académique : {{ $resultatAnnuel->annee_academique ?? date('Y').'-'.(date('Y')+1) }} | Niveau : {{ $etudiant->niveau?->libelle ?? 'N/A' }}</p>
    </div>

    <!-- 1. PROGRESSION & DECISION ANNUELLE -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 ease-out">
        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="award" class="w-5 h-5 text-sky-500"></i>
            1. Bilan de l'Année
        </h2>
        <div class="grid gap-6 md:grid-cols-2">

            <!-- Moyenne Annuelle -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Moyenne Globale</p>
                <div class="mt-4 flex items-baseline gap-2">
                    @if($resultatAnnuel)
                    <p class="text-5xl font-black text-sky-600">{{ number_format((float) ($resultatAnnuel->moyenne_annuelle ?? 0), 2, ',', '.') }}</p>
                    <p class="text-sm font-bold text-slate-400">/ 20</p>
                    @else
                    <p class="text-2xl font-bold text-slate-400">En attente de calcul</p>
                    @endif
                </div>
                <p class="mt-4 text-sm text-slate-500">Cette moyenne regroupe l'ensemble des résultats de vos deux semestres.</p>
            </div>

            <!-- Décision de Progression -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200 flex flex-col justify-center">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Décision du Jury (Progression)</p>

                @if($progression)
                @if($progression->statut === 'diplome' || ($resultatAnnuel && $resultatAnnuel->decision === 'diplome'))
                <div class="rounded-2xl bg-emerald-50 p-6 border border-emerald-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900 uppercase tracking-wide">Diplômé(e) !</h3>
                    <p class="mt-1 text-sm text-emerald-700 font-medium">Félicitations pour l'obtention de votre diplôme.</p>
                </div>
                @elseif($progression->statut === 'passage' || ($resultatAnnuel && in_array($resultatAnnuel->decision, ['admis', 'ajourne'])))
                @if($resultatAnnuel && $resultatAnnuel->decision === 'ajourne')
                <div class="rounded-2xl bg-amber-50 p-6 border border-amber-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 mb-3">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 uppercase tracking-wide">Ajourné</h3>
                    <p class="mt-1 text-sm text-amber-700 font-medium">Votre moyenne annuelle est insuffisante, mais une reprise reste possible selon les règles du niveau.</p>
                </div>
                @else
                <div class="rounded-2xl bg-emerald-50 p-6 border border-emerald-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900 uppercase tracking-wide">Admis en année supérieure</h3>
                    <p class="mt-1 text-sm text-emerald-700 font-medium">Félicitations pour votre passage.</p>
                </div>
                @endif
                @elseif($progression->statut === 'redoublement' || ($resultatAnnuel && $resultatAnnuel->decision === 'redoublant'))
                <div class="rounded-2xl bg-red-50 p-6 border border-red-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 mb-3">
                        <i data-lucide="x-circle" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-900 uppercase tracking-wide">Redoublant</h3>
                    <p class="mt-1 text-sm text-red-700 font-medium">Vous n'avez pas validé les conditions requises pour le passage.</p>
                </div>
                @else
                <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 text-slate-500 mb-3">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Délibération en attente</h3>
                    <p class="mt-1 text-sm text-slate-500">Le jury ne s'est pas encore prononcé sur votre progression.</p>
                </div>
                @endif
                @else
                <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 text-slate-500 mb-3">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Délibération en attente</h3>
                    <p class="mt-1 text-sm text-slate-500">Le jury ne s'est pas encore prononcé sur votre année.</p>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- 2. RESULTATS SEMESTRIELS -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-100 ease-out">
        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-blue-500"></i>
            2. Résultats par Semestre
        </h2>

        <div class="grid gap-6 md:grid-cols-2">
            @forelse($resultatsSemestre as $rs)
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $rs->semestre?->libelle ?? 'Semestre Inconnu' }}</h3>
                        <p class="text-xs font-bold text-slate-500 mt-1">Année {{ $resultatAnnuel->annee_academique ?? date('Y') }}</p>
                    </div>
                    @if($rs->decision === 'admis')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-emerald-700 border border-emerald-200">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Validé
                    </span>
                    @elseif($rs->decision === 'en_cours')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-slate-700 border border-slate-200">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> En cours
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-red-700 border border-red-200">
                        <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Non Validé
                    </span>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                    <p class="text-sm font-medium text-slate-600">Moyenne du semestre</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format((float) ($rs->moyenne ?? 0), 2, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 rounded-3xl bg-white p-8 text-center border border-slate-200 shadow-sm">
                <i data-lucide="clock" class="mx-auto h-10 w-10 text-slate-300 mb-3"></i>
                <p class="text-base font-bold text-slate-900">Aucun résultat semestriel calculé</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- 3. DETAIL DES MATIERES (BULLETIN) -->
    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'" class="transition-all duration-700 delay-200 ease-out">
        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5 text-emerald-500"></i>
            3. Détail des Matières
        </h2>

        @if($bulletinReady && $notes->isNotEmpty())
        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Semestre / Module</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Matière</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Note Finale</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($notes as $note)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-blue-600 mb-1">{{ $note->matiere?->module?->semestre?->code ?? '—' }}</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $note->matiere?->module?->libelle ?? $note->matiere?->module?->nom ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-700">{{ $note->matiere?->libelle ?? '—' }}</p>
                                <p class="text-xs text-slate-400 mt-1">Coef. / Crédit : {{ $note->matiere?->module?->credit ?? '1' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!is_null($note->note_finale))
                                <span class="text-base font-black {{ $note->note_finale >= 10 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ number_format($note->note_finale, 2, ',', '.') }}
                                </span>
                                @else
                                <span class="text-sm text-slate-400 font-medium">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!is_null($note->note_finale))
                                @if($note->note_finale >= 10)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Validée
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Non Validée
                                </span>
                                @endif
                                @else
                                <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="rounded-3xl bg-white p-12 text-center border border-slate-200 shadow-sm">
            <i data-lucide="file-bar-chart" class="mx-auto h-12 w-12 text-slate-300"></i>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Aucune note disponible</h3>
            <p class="mt-1 text-sm text-slate-500">Vos notes détaillées s'afficheront ici après saisie par l'administration.</p>
        </div>
        @endif
    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
