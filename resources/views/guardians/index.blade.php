@extends('layouts.app')

@section('title', 'Tuteurs')
@section('page_title', 'Gestion des Tuteurs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Tuteurs</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des tuteurs / responsables</h3>
                        {{-- Formulaire de recherche --}}
                    <div class="card-tools">
                        <form action="{{ route('guardians.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control float-right" 
                                    placeholder="Nom, prénom, téléphone..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Filtre par statut --}}
                            <select name="status" class="form-control form-control-sm ml-2" style="width: 120px;" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                            </select>
                            
                            {{-- Bouton reset --}}
                            @if(request('search') || request('status'))
                                <a href="{{ route('guardians.index') }}" class="btn btn-sm btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Nom complet</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Étudiants liés</th>
                                <th>Statut</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guardians as $guardian)
                            <tr>
                                {{-- <td>{{ $guardian->id }}</td> --}}
                                <td>
                                    <strong>{{ $guardian->full_name }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-phone mr-1"></i>{{ $guardian->phone }}
                                </td>
                                <td>
                                    @if($guardian->email)
                                        <i class="fas fa-envelope mr-1"></i>{{ $guardian->email }}
                                    @else
                                        <span class="text-muted">Non renseigné</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $guardian->students_count }}</span>
                                    @if($guardian->students_count > 0)
                                        <button type="button" class="btn btn-xs btn-link" 
                                                onclick="alert('Étudiants: {{ $guardian->students->pluck('full_name')->implode(', ') }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if($guardian->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $guardian->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('guardians.show', $guardian) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('guardians.edit', $guardian) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('guardians.destroy', $guardian) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p>Aucun tuteur trouvé.</p>
                                    <a href="{{ route('guardians.create') }}" class="btn btn-primary">
                                        Ajouter le premier tuteur
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $guardians->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection