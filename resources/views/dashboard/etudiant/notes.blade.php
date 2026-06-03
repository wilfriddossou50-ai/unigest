@extends('layouts.etudiant')

@section('title', 'Mes notes')

@section('content')
<div x-data="{ ready: false }" x-init="setTimeout(() => ready = true, 50)" class="space-y-6">

    <div :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        class="rounded-3xl bg-white p-6 border border-slate-200 shadow-sm transition-all duration-700">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mes notes</h1>
                <p class="mt-1 text-sm text-slate-500">Consultez l'avancement de vos matières et leur statut.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <label class="text-sm font-medium text-slate-600">Semestre :</label>
                @php $url = route('etudiant.notes') . '?semestre_id='; @endphp
                <select onchange="window.location.href = '{{ $url }}' + this.value"
                    class="min-w-[180px] rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 cursor-pointer">
                    @foreach($semestresDisponibles as $s)
                    <option value="{{ $s->id }}" {{ $selectedSemestreId == $s->id ? 'selected' : '' }}>
                        {{ $s->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach([
        ['Semestre', $currentSemestre ?? '—', 'calendar', 'text-slate-600', 'bg-slate-50'],
        ['Moyenne', $average !== '—' ? $average . '/20' : '—', 'trending-up', 'text-emerald-600', 'bg-emerald-50'],
        ['Validées', $validatedCount, 'check-circle', 'text-sky-600', 'bg-sky-50']
        ] as $stat)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] font-semibold text-slate-400">{{ $stat[0] }}</p>
                    <p class="mt-2 text-xl font-bold text-slate-900">{{ $stat[1] }}</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl {{ $stat[4] }}">
                    <i data-lucide="{{ $stat[2] }}" class="w-5 h-5 {{ $stat[3] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="rounded-[28px] bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex flex-col gap-3 px-6 py-5 border-b border-slate-100 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Détail des matières</h2>
                <p class="mt-1 text-sm text-slate-500">Affichage clair des notes, statuts et validations.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">
                {{ $notes->count() }} matière(s)
            </span>
        </div>

        @if($notes->isNotEmpty())
        <div class="space-y-4 p-6">
            @foreach($notes as $note)
            @php
            $matiere = $note->matiere;
            $module = $matiere?->module;
            $isValide = in_array($note->statut, ['validee', 'rattrapage_valide', 'reprise_valide']);
            $statusLabel = match ($note->statut) {
            'validee', 'rattrapage_valide', 'reprise_valide' => 'Validée',
            'en_cours' => 'En cours',
            default => 'Non validée',
            };
            $statusColor = $isValide ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200';
            @endphp
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm transition hover:border-slate-300 hover:bg-white">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <h3 class="text-lg font-semibold text-slate-900 truncate">{{ $matiere?->libelle ?? 'Matière non définie' }}</h3>
                        <div class="mt-2 flex flex-wrap gap-2 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-slate-600 shadow-sm">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                                {{ $module?->libelle ?? $module?->nom ?? 'Module inconnu' }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-slate-600 shadow-sm">
                                <i data-lucide="hash" class="w-4 h-4"></i>
                                {{ $matiere?->code ?? 'Sans code' }}
                            </span>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.15em] {{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl bg-white p-4 border border-slate-200">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Note finale</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $note->note_finale !== null ? number_format($note->note_finale, 2, ',', '.') : '—' }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-4 border border-slate-200">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Validation</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">{{ $statusLabel }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-4 border border-slate-200">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">CC</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">{{ $note->cc ?? 'N/A' }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-4 border border-slate-200">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Examen</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">{{ $note->examen ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center text-slate-500">
            <i data-lucide="package" class="mx-auto h-12 w-12 text-slate-300"></i>
            <p class="mt-4 text-base">Aucune note enregistrée pour le semestre sélectionné.</p>
        </div>
        @endif
    </div>
</div>
@endsection