@extends('layouts.admin')

@section('title', 'Résultats Semestriels')

@section('content')
<div class="p-8">
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Résultats Semestriels</h1>
            <p class="text-sm text-slate-500">Vue synthétique par étudiant et par semestre</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <form method="GET" action="{{ route('admin.resultats.semestre.index') }}" class="flex items-stretch gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Rechercher un étudiant"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                <button type="submit" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-semibold hover:bg-slate-700 transition">Filtrer</button>
            </form>

            <form action="{{ route('admin.resultats.semestre.tout-calculer') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Tout recalculer
                </button>
            </form>
        </div>
    </div>

    @if($etudiants->isNotEmpty())
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-320px)] overflow-y-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500 sticky left-0 bg-slate-50">Étudiant</th>
                        @foreach($semestres as $semestre)
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase text-slate-500 whitespace-nowrap">{{ $semestre->libelle }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($etudiants as $etudiant)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900 sticky left-0 bg-white border-r border-slate-200">
                            {{ $etudiant->user->nom ?? 'Inconnu' }} {{ $etudiant->user->prenom ?? '' }}
                        </td>
                        @foreach($semestres as $semestre)
                        @php
                        $res = data_get($resultatsMap, "{$etudiant->id}.{$semestre->id}");
                        @endphp
                        <td class="px-4 py-4 text-center">
                            @if($res && $res->moyenne !== null)
                            <div class="text-base font-semibold text-slate-900">{{ number_format($res->moyenne, 2, ',', '.') }}</div>
                            <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase {{ in_array($res->decision, ['admis', 'diplome']) ? 'bg-emerald-100 text-emerald-700' : ($res->decision === 'ajourne' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ $res->decision ? ucfirst(str_replace('_', ' ', $res->decision)) : 'En attente' }}
                            </span>
                            @else
                            <button type="button" data-etudiant="{{ $etudiant->id }}" data-semestre="{{ $semestre->id }}" onclick="calculerResultat(this)" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Calculer
                            </button>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $etudiants->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-slate-200">
        <i data-lucide="inbox" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
        <p class="text-slate-600">Aucun résultat semestriel disponible</p>
    </div>
    @endif

    <script>
        function calculerResultat(button) {
            const etudiantId = button.dataset.etudiant;
            const semestreId = button.dataset.semestre;

            fetch(`/admin/resultats/semestre/calculer/${etudiantId}/${semestreId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Échec de la requête');
                    }
                    location.reload();
                })
                .catch(err => alert('Erreur: ' + err.message));
        }
    </script>
</div>

<script>
    lucide.createIcons();
</script>
@endsection