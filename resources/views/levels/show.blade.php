{{-- resources/views/levels/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails du niveau')
@section('page_title', 'Détails du niveau')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('levels.index') }}">Niveaux</a></li>
    <li class="breadcrumb-item active">{{ $level->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'informations --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group mr-2"></i>
                        Informations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('levels.edit', $level) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        {{-- <tr>
                            <th style="width: 40%">ID</th>
                            <td>{{ $level->id }}</td>
                        </tr> --}}
                        <tr>
                            <th>Nom</th>
                            <td><strong>{{ $level->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Cycle</th>
                            <td>
                                @if($level->cycle == 'fondamentale')
                                    <span class="badge bg-info">Fondamentale</span>
                                @else
                                    <span class="badge bg-success">Secondaire</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Sections liées</th>
                            <td><span class="badge bg-primary">{{ $level->sections->count() }}</span></td>
                        </tr>
                        <tr>
                            <th>Étudiants</th>
                            <td><span class="badge bg-success">{{ $level->students->count() }}</span></td>
                        </tr>
                        <tr>
                            <th>Date création</th>
                            <td>{{ $level->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('levels.destroy', $level) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Sections du niveau --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Sections associées ({{ $level->sections->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>Par défaut</th>
                                <th>Inscriptions</th>
                                {{-- <th>Matières</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($levelSections as $levelSection)
                            <tr>
                                <td>
                                    <strong>{{ $levelSection->section->name }}</strong>
                                </td>
                                <td>
                                    @if($levelSection->section->is_default)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $count = $levelSection->enrollments()
                                            ->whereHas('academicYear', function($q) {
                                                $q->where('status', 'active');
                                            })->count();
                                    @endphp
                                    <span class="badge bg-info">{{ $count }}</span>
                                </td>
                                {{-- <td>
                                    @php
                                        $subjectsCount = $levelSection->subjects()->count();
                                    @endphp
                                    <span class="badge bg-warning">{{ $subjectsCount }}</span>
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-muted mb-2"></i>
                                    <p>Aucune section associée à ce niveau.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Derniers étudiants inscrits --}}
            @php
                $currentYear = App\Models\AcademicYear::where('status', 'active')->first();
                $recentStudents = $currentYear ? $level->studentsForYear($currentYear->id)->take(5) : collect();
            @endphp

            @if($recentStudents->count() > 0)
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Derniers inscrits (année en cours)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>NISU</th>
                                <th>Nom complet</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentStudents as $student)
                            <tr>
                                <td>{{ $student->nisu }}</td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </a>
                                </td>
                                <td>{{ $student->section->name ?? 'N/A' }}</td>
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