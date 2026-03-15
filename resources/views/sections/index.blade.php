@extends('layouts.app')

@section('title', 'Sections / Classes')
@section('page_title', 'Gestion des Sections')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Sections</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des sections/classes</h3>
                    <div class="card-tools">
                        <a href="{{ route('sections.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle section
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Section/Classe</th>
                                <th>Effectif (année en cours)</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                            <tr>
                                <td>{{ $section->id }}</td>
                                <td>
                                    <strong>{{ $section->name }}</strong>
                                </td>
                                {{-- <td>
                                    <span class="badge bg-info">{{ $section->subjects->count() }}</span>
                                    @if($section->subjects->count() > 0)
                                        <button type="button" class="btn btn-xs btn-link" 
                                                onclick="alert('Matières: {{ $section->subjects->pluck('name')->implode(', ') }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </td> --}}
                                <td>
                                    <span class="badge bg-success">{{ $section->current_students_count }}</span>
                                </td>
                                <td>{{ $section->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('sections.show', $section) }}" class="btn btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sections.edit', $section) }}" class="btn btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sections.destroy', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" title="Supprimer" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section ? Toutes les inscriptions associées seront également supprimées.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                    <p>Aucune section trouvée.</p>
                                    <a href="{{ route('sections.create') }}" class="btn btn-primary">
                                        Créer la première section
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $sections->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection