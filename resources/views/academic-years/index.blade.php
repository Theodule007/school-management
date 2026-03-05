@extends('layouts.app')

@section('title', 'Années Académiques')
@section('page_title', 'Gestion des Années Académiques')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Années académiques</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des années académiques</h3>
                    <div class="card-tools">
                        <a href="{{ route('academic-years.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle année
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Nom</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($academicYears as $year)
                            <tr>
                                {{-- <td>{{ $year->id }}</td> --}}
                                <td><strong>{{ $year->name }}</strong></td>
                                <td>{{ $year->start_date->format('d/m/Y') }}</td>
                                <td>{{ $year->end_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($year->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($year->status == 'draft')
                                        <span class="badge bg-warning">Brouillon</span>
                                    @else
                                        <span class="badge bg-secondary">Clôturée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('academic-years.show', $year) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('academic-years.edit', $year) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('academic-years.destroy', $year) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette année ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                    <p>Aucune année académique trouvée.</p>
                                    <a href="{{ route('academic-years.create') }}" class="btn btn-primary">
                                        Créer la première année
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $academicYears->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection