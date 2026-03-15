@extends('layouts.app')

@section('title', 'Étudiants')
@section('page_title', 'Gestion des Étudiants')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Étudiants</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title">Liste des étudiants</h3>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            {{-- Formulaire de filtres (pour DataTables) --}}
                            <div class="form-inline">
                                <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">
                                    {{-- Filtre par niveau --}}
                                    <select id="level-filter" class="form-control form-control-sm" style="width: 130px;">
                                        <option value="">Niveau</option>
                                        @foreach($levels ?? [] as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                    
                                    {{-- Filtre par statut --}}
                                    <select id="status-filter" class="form-control form-control-sm" style="width: 100px;">
                                        <option value="">Statut</option>
                                        <option value="active">Actifs</option>
                                        <option value="archived">Archivés</option>
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Bouton Nouvel étudiant --}}
                            <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nouvel étudiant
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table table-hover table-striped" id="students-table" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>NISU</th>
                                <th>Nom complet</th>
                                <th>Genre</th>
                                <th>Date naissance</th>
                                <th>Classe actuelle</th>
                                {{-- <th>Tuteur principal</th> --}}
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#students-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('students.index') }}",
            data: function(d) {
                d.level_id = $('#level-filter').val();
                d.status = $('#status-filter').val();
            }
        },
        columns: [
            { data: 'nisu_formatted', name: 'nisu' },
            { data: 'full_name', name: 'full_name' },
            { data: 'gender_badge', name: 'gender' },
            { data: 'birth_date_formatted', name: 'birth_date' },
            { data: 'current_class', name: 'current_class', orderable: false },
            // { data: 'primary_guardian_name', name: 'primary_guardian_name', orderable: false },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
        },
        dom: "<'row'<'col-sm-12'><'col-sm-12'B>>" +
            "<'row'<'col-sm-12'><'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                text: '<i class="fas fa-copy"></i> Copier',
                extend: 'copy',
                className: 'btn btn-sm btn-secondary'
            },
            {
                text: '<i class="fas fa-file-csv"></i> CSV',
                extend: 'csv',
                className: 'btn btn-sm btn-success'
            },
            {
                text: '<i class="fas fa-file-excel"></i> Excel',
                extend: 'excel',
                className: 'btn btn-sm btn-success'
            },
            {
                text: '<i class="fas fa-file-pdf"></i> PDF',
                extend: 'pdf',
                className: 'btn btn-sm btn-danger',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                text: '<i class="fas fa-print"></i> Imprimer',
                extend: 'print',
                className: 'btn btn-sm btn-info',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                text: '<i class="fas fa-eye"></i> Colonnes',
                extend: 'colvis',
                className: 'btn btn-sm btn-secondary'
            }
        ],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
        pageLength: 15,
        order: [[1, 'asc']]
    });

    // Appliquer les filtres
    $('#level-filter, #status-filter').on('change', function() {
        table.ajax.reload();
    });

    // Réinitialiser les filtres
    $('#reset-filters').on('click', function() {
        $('#level-filter').val('');
        $('#status-filter').val('');
        table.ajax.reload();
    });
});
</script>
@endpush