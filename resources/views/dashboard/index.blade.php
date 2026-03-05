{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tableau de bord</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat cards) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    {{-- Nous utiliserons des variables réelles plus tard --}}
                    <h3>150</h3>
                    <p>Étudiants Actifs</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
                    <p>Taux de Paiement</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <a href="{{ route('payments.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>12</h3>
                    <p>Nouvelles Inscriptions (30j)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <a href="{{ route('enrollments.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>8</h3>
                    <p>Bulletins à Valider</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <a href="{{ route('report-cards.index') }}" class="small-box-footer">
                    Plus d'info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Main row: Links & Recent Activity -->
    <div class="row">
        <!-- Quick Links Card -->
        <section class="col-lg-7 connectedSortable">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Accès Rapide
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 col-sm-3">
                            <a href="{{ route('students.create') }}" class="btn btn-app bg-info">
                                <i class="fas fa-user-plus fa-2x"></i>
                                <strong>Nouvel<br>Étudiant</strong>
                            </a>
                        </div>
                        <div class="col-4 col-sm-3">
                            <a href="{{ route('enrollments.create') }}" class="btn btn-app bg-success">
                                <i class="fas fa-clipboard-check fa-2x"></i>
                                <strong>Nouvelle<br>Inscription</strong>
                            </a>
                        </div>
                        <div class="col-4 col-sm-3">
                            <a href="{{ route('payments.create') }}" class="btn btn-app bg-warning">
                                <i class="fas fa-cash-register fa-2x"></i>
                                <strong>Enregistrer<br>Paiement</strong>
                            </a>
                        </div>
                        <div class="col-4 col-sm-3">
                            <a href="{{ route('grades.create') }}" class="btn btn-app bg-purple">
                                <i class="fas fa-edit fa-2x"></i>
                                <strong>Saisir<br>Notes</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Activity -->
        <section class="col-lg-5 connectedSortable">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Activité Récente
                    </h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav flex-column">
                        <!-- Example Activity Items - Replace with real data -->
                        <li class="nav-item border-bottom">
                            <a href="#" class="nav-link">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-user-circle text-success mr-2"></i>
                                        <strong>Moussa Diop</strong> a été inscrit en 3ème A
                                    </div>
                                    <span class="text-muted text-sm">Il y a 2h</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item border-bottom">
                            <a href="#" class="nav-link">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-money-bill-wave text-info mr-2"></i>
                                        Paiement reçu de <strong>Amina Ndiaye</strong>
                                    </div>
                                    <span class="text-muted text-sm">Il y a 5h</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item border-bottom">
                            <a href="#" class="nav-link">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-file-signature text-warning mr-2"></i>
                                        Bulletin validé pour <strong>Cheikh Tidiane Fall</strong>
                                    </div>
                                    <span class="text-muted text-sm">Hier, 14:30</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="#" class="uppercase">Voir toute l'activité</a>
                </div>
            </div>
        </section>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

{{-- Vous pouvez ajouter des scripts spécifiques à la page ici --}}
@section('scripts')
<script>
    console.log('Dashboard chargé.');
    // Ici, vous pourrez plus tard ajouter des graphiques (Chart.js) ou des appels AJAX pour les stats en temps réel.
</script>
@endsection