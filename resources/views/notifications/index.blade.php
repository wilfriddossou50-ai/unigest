@extends('layouts.etudiant')

@section('title', 'Mes Notifications')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mes Notifications</h1>
        <form action="{{ route('notifications.marquer-toutes-lues') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
            </button>
        </form>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('notifications.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="cours_programme" {{ request('type') === 'cours_programme' ? 'selected' : '' }}>Cours programmé</option>
                        <option value="changement_salle" {{ request('type') === 'changement_salle' ? 'selected' : '' }}>Changement de salle</option>
                        <option value="annulation_cours" {{ request('type') === 'annulation_cours' ? 'selected' : '' }}>Annulation cours</option>
                        <option value="examen_programme" {{ request('type') === 'examen_programme' ? 'selected' : '' }}>Examen programmé</option>
                        <option value="resultats_publies" {{ request('type') === 'resultats_publies' ? 'selected' : '' }}>Résultats publiés</option>
                        <option value="rattrapage_propose" {{ request('type') === 'rattrapage_propose' ? 'selected' : '' }}>Rattrapage proposé</option>
                        <option value="decision_reprise" {{ request('type') === 'decision_reprise' ? 'selected' : '' }}>Décision reprise</option>
                        <option value="progression_niveau" {{ request('type') === 'progression_niveau' ? 'selected' : '' }}>Progression niveau</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="lu" class="form-select">
                        <option value="">Tous</option>
                        <option value="false" {{ request('lu') === 'false' ? 'selected' : '' }}>Non lues</option>
                        <option value="true" {{ request('lu') === 'true' ? 'selected' : '' }}>Lues</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="card shadow-sm">
        <div class="card-body">
            @forelse($notifications as $notification)
            <div class="border-bottom {{ $notification->lu ? 'bg-light' : 'bg-white' }} p-3 mb-2 rounded {{ !$notification->lu ? 'border-start border-4 border-primary' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-{{ $notification->couleur }} text-white">
                                <i class="fas {{ $notification->icone }} me-1"></i>
                                {{ ucfirst($notification->type) }}
                            </span>
                            @if(!$notification->lu)
                            <span class="badge bg-primary">Nouveau</span>
                            @endif
                            <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <h5 class="mb-2">{{ $notification->titre }}</h5>
                        <p class="text-muted mb-0" style="white-space: pre-line;">{{ $notification->message }}</p>
                    </div>
                    @if(!$notification->lu)
                    <form action="{{ route('notifications.marquer-lu', $notification) }}" method="POST" class="ms-3">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <p>Aucune notification trouvée</p>
            </div>
            @endforelse
        </div>
    </div>

    {{ $notifications->links() }}
</div>
@endsection
