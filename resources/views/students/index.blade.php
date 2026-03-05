@extends('layouts.app')

@section('title', 'Étudiants')
@section('page_title', 'Gestion des Étudiants')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Étudiants</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des étudiants</h3>
                    
                    <div class="card-tools">
                        <div class="d-flex flex-wrap align-items-center gap-2" style="gap: 10px;">                            
                            {{-- Formulaire de recherche --}}
                            <form action="{{ route('students.index') }}" method="GET" class="form-inline">
                                <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">
                                    {{-- Champ de recherche --}}
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" name="search" class="form-control" 
                                            placeholder="Rechercher..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    {{-- Filtre par niveau --}}
                                    <select name="level_id" class="form-control form-control-sm" 
                                            style="width: 130px;" onchange="this.form.submit()">
                                        <option value="">Niveau</option>
                                        @foreach($levels ?? [] as $level)
                                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    {{-- Filtre par statut --}}
                                    <select name="status" class="form-control form-control-sm" 
                                            style="width: 100px;" onchange="this.form.submit()">
                                        <option value="">Statut</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivés</option>
                                    </select>
                                    
                                    {{-- Bouton reset --}}
                                    @if(request('search') || request('level') || request('status'))
                                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                            {{-- Bouton Nouvel étudiant --}}
                            <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nouvel étudiant
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>NISU</th>
                                <th>Nom & Prénom</th>
                                <th>Genre</th>
                                <th>Date naissance</th>
                                <th>Classe actuelle</th>
                                <th>Tuteur principal</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">STU{{ $student->nisu }}</span>
                                </td>
                                <td>
                                    <strong>{{ $student->full_name }}</strong><br>
                                    <small class="text-muted">{{ $student->first_name }} {{ $student->last_name }}</small>
                                </td>
                                <td>
                                    @if($student->gender == 'M')
                                        <span class="badge bg-info">Masculin</span>
                                    @else
                                        <span class="badge bg-pink">Féminin</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'Non renseignée' }}
                                </td>
                                <td>
                                    @if($student->current_level)
                                        <a href="{{ route('levels.show', $student->current_level) }}">
                                            {{ $student->current_level->name }}
                                        </a>
                                        <small class="text-muted">
                                            ({{ $student->current_section->name ?? '' }})
                                        </small>
                                    @else
                                        <span class="text-muted">Non inscrit</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->primary_guardian)
                                        {{ $student->primary_guardian->first_name }} {{ $student->primary_guardian->last_name }}
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Archivé</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Archiver" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir archiver cet étudiant ?')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                    <p>Aucun étudiant trouvé.</p>
                                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                                        Ajouter le premier étudiant
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection