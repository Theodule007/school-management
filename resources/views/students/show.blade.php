@extends('layouts.app')

@section('title', 'Détails étudiant')
@section('page_title', 'Fiche étudiant')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Étudiants</a></li>
    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            {{-- Carte d'identité --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-id-card mr-2"></i>
                        Carte d'identité
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; font-size: 36px;">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">NISU</th>
                            <td><strong>{{ $student->nisu }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Genre</th>
                            <td>
                                @if($student->gender == 'M')
                                    <span class="badge bg-info">Masculin</span>
                                @else
                                    <span class="badge bg-pink">Féminin</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date naissance</th>
                            <td>
                                {{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'Non renseignée' }}
                                @if($student->birth_date)
                                    <br><small class="text-muted">{{ $student->birth_date->age }} ans</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($student->status == 'active')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Archivé</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date création</th>
                            <td>{{ $student->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir archiver cet étudiant ?')">
                            <i class="fas fa-archive"></i> Archiver
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tuteurs --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Tuteurs ({{ $student->guardians->count() }})
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir: Ajouter tuteur')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($student->guardians as $guardian)
                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $guardian->first_name }} {{ $guardian->last_name }}</strong>
                                @if($guardian->pivot->is_primary)
                                    <span class="badge bg-primary ml-2">Principal</span>
                                @endif
                                <br>
                                <small>
                                    <i class="fas fa-phone"></i> {{ $guardian->phone }}<br>
                                    <i class="fas fa-envelope"></i> {{ $guardian->email ?? 'Non renseigné' }}
                                </small>
                                <br>
                                <span class="badge bg-info">{{ $guardian->pivot->relationship }}</span>
                            </div>
                            <div>
                                <button class="btn btn-xs btn-warning" onclick="alert('Modifier lien')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                        <p>Aucun tuteur associé</p>
                        <button class="btn btn-sm btn-primary" onclick="alert('Ajouter tuteur')">
                            Ajouter un tuteur
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Historique des inscriptions --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Parcours scolaire ({{ $student->enrollments->count() }} inscriptions)
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-success" onclick="alert('Fonctionnalité à venir: Nouvelle inscription')">
                            <i class="fas fa-plus"></i> Inscrire
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Année scolaire</th>
                                <th>Classe</th>
                                <th>Statut</th>
                                <th>Date inscription</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->enrollments as $enrollment)
                            <tr>
                                <td>
                                    <strong>{{ $enrollment->academicYear->name }}</strong>
                                    @if($enrollment->academicYear->status == 'active')
                                        <span class="badge bg-success">En cours</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('levels.show', $enrollment->levelSection->level) }}">
                                        {{ $enrollment->levelSection->level->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($enrollment->status == 'enrolled')
                                        <span class="badge bg-success">Inscrit</span>
                                    @else
                                        <span class="badge bg-secondary">Transféré</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                <td class="text-right">
                                    <button class="btn btn-xs btn-info" onclick="alert('Voir détails inscription')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                    <p>Aucune inscription trouvée.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Dernières notes --}}
            @php
                $currentYear = App\Models\AcademicYear::where('status', 'active')->first();
                $grades = $currentYear ? $student->enrollments()
                            ->with('grades.subject')
                            ->where('academic_year_id', $currentYear->id)
                            ->first()?->grades ?? collect() : collect();
            @endphp

            @if($grades->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        Dernières notes (année en cours)
                    </h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-info" onclick="alert('Voir bulletin')">
                            <i class="fas fa-file-pdf"></i> Bulletin
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Matière</th>
                                <th>Période</th>
                                <th class="text-center">Note</th>
                                <th class="text-center">Appréciation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades->take(5) as $grade)
                            <tr>
                                <td>{{ $grade->subject->name }}</td>
                                <td>{{ $grade->period->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                        {{ $grade->value }}/20
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($grade->value >= 16)
                                        Excellent
                                    @elseif($grade->value >= 14)
                                        Très bien
                                    @elseif($grade->value >= 12)
                                        Bien
                                    @elseif($grade->value >= 10)
                                        Passable
                                    @else
                                        Insuffisant
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Historique des paiements --}}
            @php
                $payments = $student->enrollments()
                            ->with('payments.paymentType')
                            ->get()
                            ->pluck('payments')
                            ->flatten();
            @endphp

            @if($payments->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Historique des paiements
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Référence</th>
                                <th class="text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments->take(3) as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>{{ $payment->paymentType->name }}</td>
                                <td>{{ $payment->reference ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
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