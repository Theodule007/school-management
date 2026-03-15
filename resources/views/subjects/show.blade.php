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
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-book-open mr-2"></i>
                        Informations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        {{-- <tr>
                            <th style="width: 40%">ID</th>
                            <td>{{ $subject->id }}</td>
                        </tr> --}}
                        <tr>
                            <th>Nom</th>
                            <td><strong>{{ $subject->name }}</strong></td>
                        </tr>
                        {{-- <tr>
                            <th>Statut</th>
                            <td>
                                @if($subject->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr> --}}
                        <tr>
                            <th>Niveaux concernés</th>
                            <td>
                                <span class="badge bg-info">{{ $groupedByLevel->count() }}</span>
                                @if($groupedByLevel->count() > 0)
                                    <small>({{ $groupedByLevel->keys()->map(fn($id) => \App\Models\Level::find($id)?->name)->join(', ') }})</small>
                                @endif
                            </td>
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
                                    {{-- Statistiques rapides --}}
            @if($subject->levelSections->count() > 0)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $subject->levelSections->count() }}</h3>
                            <p>Sections assignées</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            @php
                                $totalStudents = \App\Models\Enrollment::whereIn('level_section_id', $subject->levelSections->pluck('id'))
                                    ->where('status', 'enrolled')
                                    ->count();
                            @endphp
                            <h3>{{ $totalStudents }}</h3>
                            <p>Élèves concernés</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div> --}}
            </div>
            @endif
        </div>

        <div class="col-md-8">

            {{-- Liste des assignations par niveau --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group mr-2"></i>
                        Niveaux et sections associés
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Gérer les assignations
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($groupedByLevel as $levelId => $levelSections)
                        @php $level = $levelSections->first()->level; @endphp
                        <div class="card mb-2">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <strong>{{ $level->name }}</strong>
                                    <span class="badge bg-primary float-right">
                                        {{ $levelSections->count() }} section(s)
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Section</th>
                                            <th class="text-center">Coefficient</th>
                                            {{-- <th class="text-center">Élèves</th> --}}
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($levelSections as $ls)
                                        <tr>
                                            <td>
                                                <a href="{{ route('sections.show', $ls->section) }}">
                                                    <strong>{{ $ls->section->name }}</strong>
                                                </a>
                                                {{-- @if($ls->section->is_default)
                                                    <span class="badge bg-info">Par défaut</span>
                                                @endif --}}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $ls->pivot->coefficient }}</span>
                                            </td>
                                            {{-- <td class="text-center">
                                                @php
                                                    $studentCount = \App\Models\Enrollment::where('level_section_id', $ls->id)
                                                        ->where('status', 'enrolled')
                                                        ->count();
                                                @endphp
                                                <span class="badge bg-success">{{ $studentCount }}</span>
                                            </td> --}}
                                            <td class="text-right">
                                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <p>Cette matière n'est pas encore assignée à des sections.</p>
                            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Assigner la matière
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Dernières notes saisies --}}
            @if($subject->grades->count() > 0)
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        Dernières notes saisies
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Niveau</th>
                                <th>Section</th>
                                <th>Période</th>
                                <th class="text-center">Note</th>
                                <th class="text-center">Appréciation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->grades->take(5) as $grade)
                            <tr>
                                <td>
                                    <a href="{{ route('students.show', $grade->enrollment->student) }}">
                                        {{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}
                                    </a>
                                </td>
                                <td>{{ $grade->enrollment->levelSection->level->name ?? 'N/A' }}</td>
                                <td>{{ $grade->enrollment->levelSection->section->name ?? 'N/A' }}</td>
                                <td>{{ $grade->period->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                        {{ $grade->value }}/20
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($grade->value >= 16)
                                        <span class="text-success">Excellent</span>
                                    @elseif($grade->value >= 14)
                                        <span class="text-info">Très bien</span>
                                    @elseif($grade->value >= 12)
                                        <span class="text-primary">Bien</span>
                                    @elseif($grade->value >= 10)
                                        <span class="text-warning">Passable</span>
                                    @else
                                        <span class="text-danger">Insuffisant</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($subject->grades->count() > 5)
                    <div class="card-footer text-center">
                        <a href="{{ route('grades.index', ['subject_id' => $subject->id]) }}">
                            Voir toutes les notes ({{ $subject->grades->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif


        </div>
    </div>
</div>
@endsection