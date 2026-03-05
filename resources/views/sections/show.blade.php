@extends('layouts.app')

@section('title', 'Détails de la section')
@section('page_title', 'Détails de la section/classe')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sections</a></li>
    <li class="breadcrumb-item active">{{ $section->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'informations --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Informations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('sections.edit', $section) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        {{-- <tr>
                            <th style="width: 40%">ID</th>
                            <td>{{ $section->id }}</td>
                        </tr>
                        <tr> --}}
                            <th>Nom</th>
                            <td><strong>{{ $section->name }}</strong></td>
                        </tr>
                        @if($section->description)
                        <tr>
                            <th>Description</th>
                            <td>{{ $section->description }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>classes associées</th>
                            <td>
                                @foreach($section->levels as $level)
                                    <a href="{{ route('levels.show', $level) }}" class="badge bg-info">
                                        {{ $level->name }}
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Classe-section</th>
                            <td>
                                <span class="badge bg-primary">{{ $levelSections->count() }}</span>
                                <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-target="#levelSectionsModal">
                                    <i class="fas fa-eye"></i> Voir détails
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>Matières enseignées</th>
                            <td>
                                @php
                                    $totalSubjects = 0;
                                    foreach($levelSections as $ls) {
                                        $totalSubjects += $ls->subjects->count();
                                    }
                                @endphp
                                <span class="badge bg-info">{{ $totalSubjects }}</span>
                                (réparties sur {{ $levelSections->count() }} niveaux)
                            </td>
                        </tr>
                        <tr>
                            <th>Effectif (année en cours)</th>
                            <td>
                                <span class="badge bg-success">{{ $section->current_students_count }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('sections.destroy', $section) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('sections.index') }}" class="btn btn-sm btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Liste des combinaisons niveau-section avec leurs matières --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group mr-2"></i>
                        classes et matières associées
                    </h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité à venir : Ajouter un niveau à cette section')">
                            <i class="fas fa-plus"></i> Associer une classe
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="levelAccordion">
                        @forelse($levelSections as $index => $levelSection)
                            <div class="card">
                                <div class="card-header" id="heading{{ $index }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" 
                                                data-toggle="collapse" data-target="#collapse{{ $index }}" 
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $index }}">
                                            <strong>{{ $levelSection->level->name }}</strong>
                                            <span class="badge bg-info float-right">
                                                {{ $levelSection->subjects->count() }} matière(s)
                                            </span>
                                        </button>
                                    </h2>
                                </div>
                                
                                <div id="collapse{{ $index }}" class="collapse {{ $index == 0 ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $index }}" data-parent="#levelAccordion">
                                    <div class="card-body">
                                        @if($levelSection->subjects->count() > 0)
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Matière</th>
                                                        <th class="text-center">Coefficient</th>
                                                        <th class="text-right">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($levelSection->subjects as $subject)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $subject->name }}</strong>
                                                            @if($subject->status == 'inactive')
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning">{{ $subject->pivot->coefficient }}</span>
                                                        </td>
                                                        <td class="text-right">
                                                            <button class="btn btn-xs btn-info" 
                                                                    onclick="alert('Modifier coefficient pour {{ $subject->name }} au niveau {{ $levelSection->level->name }}')">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-xs btn-danger" 
                                                                    onclick="alert('Retirer {{ $subject->name }} du niveau {{ $levelSection->level->name }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-muted text-center py-3">
                                                Aucune matière associée pour cette classe.
                                            </p>
                                        @endif
                                        
                                        <div class="text-right mt-2">
                                            <button class="btn btn-xs btn-primary" 
                                                    onclick="alert('Ajouter une matière à la classe {{ $levelSection->level->name }}')">
                                                <i class="fas fa-plus"></i> Ajouter une matière
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-layer-group fa-2x text-muted mb-2"></i>
                                <p>Aucune classe associée à cette section.</p>
                                <button class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir')">
                                    Associer ue classe
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Liste des étudiants --}}
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Étudiants inscrits (année en cours)
                    </h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité à venir : Inscrire un étudiant')">
                            <i class="fas fa-plus"></i> Inscrire
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $currentYear = App\Models\AcademicYear::where('status', 'active')->first();
                        $enrollments = $currentYear ? \App\Models\Enrollment::with(['student', 'levelSection.level'])
                                                    ->whereIn('level_section_id', $levelSections->pluck('id'))
                                                    ->where('academic_year_id', $currentYear->id)
                                                    ->where('status', 'enrolled')
                                                    ->get() : collect();
                    @endphp
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NISU</th>
                                <th>Nom complet</th>
                                <th>Niveau</th>
                                <th>Genre</th>
                                <th>Date inscription</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->nisu }}</td>
                                <td>
                                    <a href="{{ route('students.show', $enrollment->student) }}">
                                        <strong>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</strong>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $enrollment->levelSection->level->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($enrollment->student->gender == 'M')
                                        <span class="badge bg-info">M</span>
                                    @else
                                        <span class="badge bg-pink">F</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('students.show', $enrollment->student) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                    <p>Aucun étudiant inscrit dans cette section pour l'année en cours.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($enrollments->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('enrollments.index', ['section_id' => $section->id]) }}">
                        Gérer toutes les inscriptions ({{ $enrollments->count() }})
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal pour afficher les détails des combinaisons niveau-section --}}
<div class="modal fade" id="levelSectionsModal" tabindex="-1" role="dialog" aria-labelledby="levelSectionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="levelSectionsModalLabel">
                    <i class="fas fa-layer-group mr-2"></i>
                    Détails des combinaisons classe-section
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th class="text-center">Matières</th>
                            <th class="text-center">Inscriptions</th>
                            <th class="text-center">Date création</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($levelSections as $ls)
                        <tr>
                            <td>
                                <strong>{{ $ls->level->name }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $ls->subjects->count() }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $enrollmentsCount = \App\Models\Enrollment::where('level_section_id', $ls->id)
                                                                               ->where('status', 'enrolled')
                                                                               ->count();
                                @endphp
                                <span class="badge bg-success">{{ $enrollmentsCount }}</span>
                            </td>
                            <td class="text-center">
                                {{ $ls->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection