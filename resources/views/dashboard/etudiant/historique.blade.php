@extends('layouts.etudiant')

@section('title', 'Historique Académique')
@section('subtitle', 'Votre parcours complet')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Historique Académique</h1>
        <a href="{{ route('etudiant.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Résumé global -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Moyenne Générale</h5>
                    <h2 class="display-4 fw-bold">{{ $moyenneGenerale ?? '-' }}/20</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Modules Validés</h5>
                    <h2 class="display-4 fw-bold">{{ $modulesValides ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">En Attente</h5>
                    <h2 class="display-4 fw-bold">{{ $modulesEnAttente ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Crédits</h5>
                    <h2 class="display-4 fw-bold">{{ $credits ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique par semestre -->
    @forelse($semestres as $semestre)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">{{ $semestre->libelle }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Moyenne semestre :</strong> {{ $semestre->moyenne ?? '-' }}/20
                </div>
                <div class="col-md-6">
                    <strong>Décision :</strong>
                    <span class="badge {{ $semestre->decision === 'admis' ? 'bg-success' : ($semestre->decision === 'ajourne' || $semestre->decision === 'en_cours' ? 'bg-warning' : 'bg-danger') }}">
                        {{ ucfirst(str_replace('_', ' ', $semestre->decision ?? 'en cours')) }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th>Module</th>
                        <th>Matière</th>
                        <th>CC</th>
                        <th>Examen</th>
                        <th>Rattrapage</th>
                        <th>Reprise</th>
                        <th>Finale</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semestre->matieres ?? [] as $matiere)
                    <tr>
                        <td>{{ $matiere->module->libelle ?? $matiere->module->nom ?? '-' }}</td>
                        <td>{{ $matiere->libelle }}</td>
                        <td>{{ $matiere->cc ?? '-' }}</td>
                        <td>{{ $matiere->examen ?? '-' }}</td>
                        <td>{{ $matiere->rattrapage ?? '-' }}</td>
                        <td>{{ $matiere->reprise ?? '-' }}</td>
                        <td><strong>{{ $matiere->note_finale ?? '-' }}</strong></td>
                        <td>
                            <span class="badge {{ in_array($matiere->statut, ['validee', 'rattrapage_valide', 'reprise_valide']) ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($matiere->statut ?? '-') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucune matière</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
    </div>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center py-8">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <p>Aucun historique académique disponible</p>
        </div>
    </div>
    @endforelse
</div>
@endsection