@extends('layouts.app')

@section('title', 'Détails du tuteur')
@section('page_title', 'Fiche tuteur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('guardians.index') }}">Tuteurs</a></li>
    <li class="breadcrumb-item active">{{ $guardian->full_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'informations --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-id-card mr-2"></i>
                        Informations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guardians.edit', $guardian) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        {{-- <tr>
                            <th style="width: 35%">ID</th>
                            <td>{{ $guardian->id }}</td>
                        </tr> --}}
                        <tr>
                            <th>Nom complet</th>
                            <td><strong>{{ $guardian->full_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td>
                                <i class="fas fa-phone mr-1"></i> {{ $guardian->phone }}
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                @if($guardian->email)
                                    <i class="fas fa-envelope mr-1"></i> {{ $guardian->email }}
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Adresse</th>
                            <td>
                                @if($guardian->address)
                                    {{ $guardian->address }}
                                @else
                                    <span class="text-muted">Non renseignée</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($guardian->status == 'active')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date création</th>
                            <td>{{ $guardian->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('guardians.destroy', $guardian) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('guardians.index') }}" class="btn btn-sm btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Liste des étudiants associés --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Étudiants associés ({{ $guardian->students->count() }})
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-light" onclick="alert('Fonctionnalité à venir: Associer un étudiant')">
                            <i class="fas fa-plus"></i> Associer
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Lien de parenté</th>
                                <th>Principal</th>
                                <th>Classe actuelle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guardian->students as $student)
                            <tr>
                                <td>
                                    <a href="{{ route('students.show', $student) }}">
                                        <strong>{{ $student->full_name }}</strong>
                                    </a>
                                </td>
                                <td>
                                    @switch($student->pivot->relationship)
                                        @case('pere')
                                            <span class="badge bg-primary">Père</span>
                                            @break
                                        @case('mere')
                                            <span class="badge bg-pink">Mère</span>
                                            @break
                                        @case('tuteur')
                                            <span class="badge bg-warning">Tuteur</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $student->pivot->relationship }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($student->pivot->is_primary)
                                        <span class="badge bg-success">Principal</span>
                                    @else
                                        <span class="badge bg-secondary">Secondaire</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->current_level)
                                        {{ $student->current_level->name }}
                                    @else
                                        <span class="text-muted">Non inscrit</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info" onclick="alert('Voir détails relation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                    <p>Aucun étudiant associé à ce tuteur.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Statistiques rapides --}}
            @if($guardian->students->count() > 0)
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $guardian->students->count() }}</h3>
                            <p>Étudiants sous sa responsabilité</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            @php
                                $primaryCount = $guardian->students->filter(function($s) {
                                    return $s->pivot->is_primary;
                                })->count();
                            @endphp
                            <h3>{{ $primaryCount }}</h3>
                            <p>Dont responsable principal</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection