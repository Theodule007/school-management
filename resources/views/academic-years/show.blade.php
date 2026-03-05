@extends('layouts.app')

@section('title', 'Détails de l\'année académique')
@section('page_title', 'Détails de l\'année académique')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('academic-years.index') }}">Années académiques</a></li>
    <li class="breadcrumb-item active">{{ $academicYear->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'informations principales --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Informations générales
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('academic-years.edit', $academicYear) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Nom</th>
                            <td><strong>{{ $academicYear->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Date de début</th>
                            <td>{{ $academicYear->start_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Date de fin</th>
                            <td>{{ $academicYear->end_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($academicYear->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($academicYear->status == 'draft')
                                    <span class="badge bg-warning">Brouillon</span>
                                @else
                                    <span class="badge bg-secondary">Clôturée</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Créée le</th>
                            <td>{{ $academicYear->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière mise à jour</th>
                            <td>{{ $academicYear->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('academic-years.destroy', $academicYear) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette année académique ? Cette action est irréversible.')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('academic-years.index') }}" class="btn btn-sm btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>

            {{-- Statistiques rapides --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Inscriptions</span>
                            <span class="info-box-number">{{ $academicYear->enrollments->count() }}</span>
                        </div>
                    </div>
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Périodes</span>
                            <span class="info-box-number">{{ $academicYear->periods->count() }}</span>
                        </div>
                    </div>
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Frais configurés</span>
                            <span class="info-box-number">{{ $academicYear->fees->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Périodes de l'année --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Périodes de l'année
                    </h3>
                    @if($academicYear->status != 'closed')
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir : Ajouter une période')">
                            <i class="fas fa-plus"></i> Nouvelle période
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Période</th>
                                <th class="text-center">Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($academicYear->periods as $period)
                            <tr>
                                <td><strong>{{ $period->name }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-success">Active</span>
                                    {{-- À améliorer quand le modèle Period aura un champ status --}}
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-xs btn-info" onclick="alert('Détails de la période')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-xs btn-warning" onclick="alert('Modifier la période')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                                    <p>Aucune période définie pour cette année.</p>
                                    @if($academicYear->status != 'closed')
                                    <button class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir')">
                                        Ajouter la première période
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Dernières inscriptions --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Dernières inscriptions
                    </h3>
                    @if($academicYear->status == 'active')
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-success" onclick="alert('Fonctionnalité à venir : Nouvelle inscription')">
                            <i class="fas fa-plus"></i> Inscrire un étudiant
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Date d'inscription</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($academicYear->enrollments->take(5) as $enrollment)
                            <tr>
                                <td>
                                    <strong>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</strong>
                                </td>
                                <td>{{ $enrollment->section->name ?? 'Non assignée' }}</td>
                                <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($enrollment->status == 'enrolled')
                                        <span class="badge bg-success">Inscrit</span>
                                    @else
                                        <span class="badge bg-secondary">Transféré</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                    <p>Aucune inscription pour cette année.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($academicYear->enrollments->count() > 5)
                <div class="card-footer text-center">
                    <a href="#" onclick="alert('Voir toutes les inscriptions')">
                        Voir toutes les inscriptions ({{ $academicYear->enrollments->count() }})
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Vous pouvez ajouter ici des scripts spécifiques à la page si nécessaire
    console.log('Page détails année académique chargée');
</script>
@endsection