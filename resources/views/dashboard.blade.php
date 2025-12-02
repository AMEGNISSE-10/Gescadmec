@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Étudiants Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Inscriptions Actives</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_registrations'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Revenus Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_income'], 0) }} XOF</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Besoins en Attente</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_needs'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Inscriptions récentes -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Inscriptions Récentes</h6>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Niveau</th>
                                <th>Date Début</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRegistrations as $registration)
                            <tr>
                                <td>{{ $registration->student->name }}</td>
                                <td><span class="badge bg-info">{{ $registration->languageLevel->name }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($registration->start_date)->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                            @if($recentRegistrations->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucune inscription récente</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Paiements récents -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">Paiements Récents</h6>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-success">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Montant</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->registration->student->name }}</td>
                                <td class="text-success fw-bold">{{ number_format($payment->amount_paid, 0) }} XOF</td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                            @if($recentPayments->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun paiement récent</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection