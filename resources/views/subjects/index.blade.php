{{-- resources/views/subjects/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Matières')
@section('page_title', 'Gestion des Matières')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Matières</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title">Liste des matières enseignées</h3>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('subjects.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nouvelle matière
                            </a>
                            
                            <form action="{{ route('subjects.index') }}" method="GET" class="form-inline">
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Rechercher..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <select name="status" class="form-control form-control-sm" 
                                            style="width: 120px;" onchange="this.form.submit()">
                                        <option value="">Tous</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                                    </select>
                                    
                                    @if(request()->anyFilled(['search', 'status']))
                                        <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Matière</th>
                                {{-- <th>Statut</th> --}}
                                <th>Assignations (Classe → Section)</th>
                                <th>Coefficients</th>
                                <th>Notes saisies</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                            <tr>
                                {{-- <td>{{ $subject->id }}</td> --}}
                                <td>
                                    <strong>{{ $subject->name }}</strong>
                                </td>
                                {{-- <td>
                                    @if($subject->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td> --}}
                                <td>
                                    @if($subject->levelSections->count() > 0)
                                        @foreach($subject->levelSections->groupBy('level_id') as $levelId => $levelSections)
                                            @php $level = $levelSections->first()->level; @endphp
                                            <div class="mb-1">
                                                <strong>{{ $level->name }}:</strong>
                                                @foreach($levelSections as $ls)
                                                    <span class="badge bg-info">{{ $ls->section->name }}</span>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Non assignée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->levelSections->count() > 0)
                                        @foreach($subject->levelSections as $ls)
                                            <div>
                                                {{ $ls->section->name }}: 
                                                <span class="badge bg-warning">{{ $ls->pivot->coefficient }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $subject->grades->count() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Supprimer cette matière ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <p>Aucune matière trouvée.</p>
                                    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                                        Créer la première matière
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer clearfix">
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection