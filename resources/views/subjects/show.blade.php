@extends('layouts.app')

@section('title', 'Détails de la matière')
@section('page_title', 'Détails de la matière')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Matières</a></li>
    <li class="breadcrumb-item active">{{ $subject->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'informations --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book-open mr-2"></i>
                        Informations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">ID</th>
                            <td>{{ $subject->id }}</td>
                        </tr>
                        <tr>
                            <th>Nom</th>
                            <td><strong>{{ $subject->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($subject->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Sections</th>
                            <td><span class="badge bg-info">{{ $subject->sections->count() }}</span></td>
                        </tr>
                        <tr>
                            <th>Notes saisies</th>
                            <td><span class="badge bg-warning">{{ $subject->grades->count() }}</span></td>
                        </tr>
                        <tr>
                            <th>Créée le</th>
                            <td>{{ $subject->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Liste des sections avec coefficients --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Sections enseignées ({{ $subject->sections->count() }})
                    </h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir : Assigner aux sections')">
                            <i class="fas fa-plus"></i> Assigner à des sections
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Section/Classe</th>
                                <th>Niveau</th>
                                <th class="text-center">Coefficient</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subject->sections as $section)
                            <tr>
                                <td>
                                    <a href="{{ route('sections.show', $section) }}">
                                        <strong>{{ $section->name }}</strong>
                                    </a>
                                </td>
                                <td>{{ $section->level->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $section->pivot->coefficient }}</span>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-xs btn-info" onclick="alert('Modifier coefficient pour {{ $section->name }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-xs btn-danger" onclick="alert('Retirer la matière de {{ $section->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-muted mb-2"></i>
                                    <p>Cette matière n'est pas encore assignée à des sections.</p>
                                    <button class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir')">
                                        Assigner aux sections
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Dernières notes saisies --}}
            @if($subject->grades->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        Dernières notes saisies
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Période</th>
                                <th class="text-center">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->grades->take(5) as $grade)
                            <tr>
                                <td>{{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}</td>
                                <td>{{ $grade->enrollment->section->name }}</td>
                                <td>{{ $grade->period->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                        {{ $grade->value }}/20
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection