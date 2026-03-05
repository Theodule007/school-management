{{-- resources/views/levels/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Niveaux')
@section('page_title', 'Gestion des Niveaux')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Niveaux</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title mb-2 mb-sm-0">Liste des niveaux</h3>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('levels.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nouveau niveau
                            </a>
                            
                            <form action="{{ route('levels.index') }}" method="GET" class="form-inline">
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="input-group input-group-sm" style="min-width: 180px;">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Rechercher..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <select name="cycle" class="form-control form-control-sm" 
                                            style="width: 130px;" onchange="this.form.submit()">
                                        <option value="">Tous cycles</option>
                                        <option value="fondamentale" {{ request('cycle') == 'fondamentale' ? 'selected' : '' }}>
                                            Fondamentale
                                        </option>
                                        <option value="secondaire" {{ request('cycle') == 'secondaire' ? 'selected' : '' }}>
                                            Secondaire
                                        </option>
                                    </select>
                                    
                                    @if(request()->anyFilled(['search', 'cycle']))
                                        <a href="{{ route('levels.index') }}" class="btn btn-sm btn-secondary">
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
                                <th>Nom</th>
                                <th>Cycle</th>
                                <th>Sections liées</th>
                                <th>Étudiants</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($levels as $level)
                            <tr>
                                {{-- <td>{{ $level->id }}</td> --}}
                                <td><strong>{{ $level->name }}</strong></td>
                                <td>
                                    @if($level->cycle == 'fondamentale')
                                        <span class="badge bg-info">Fondamentale</span>
                                    @else
                                        <span class="badge bg-success">Secondaire</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $level->sections->count() }}</span>
                                    @if($level->sections->count() > 0)
                                        <button type="button" class="btn btn-xs btn-link" 
                                                onclick="alert('Sections: {{ $level->sections->pluck('name')->implode(', ') }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $level->students->count() }}</span>
                                </td>
                                <td>{{ $level->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('levels.show', $level) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('levels.edit', $level) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('levels.destroy', $level) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                    <p>Aucun niveau trouvé.</p>
                                    <a href="{{ route('levels.create') }}" class="btn btn-primary">
                                        Créer le premier niveau
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer clearfix">
                    {{ $levels->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection