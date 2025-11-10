@extends('layouts.app')

@section('title', 'Statistiques des Paiements')

@section('actions')
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Statistiques par niveau -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistiques par Niveau
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Niveau</th>
                                <th>Étudiants</th>
                                <th>Total Attendu</th>
                                <th>Total Payé</th>
                                <th>Reste à Payer</th>
                                <th>Taux de Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $levelName => $stat)
                            @php
                                $paymentRate = $stat['total_price'] > 0 ? ($stat['total_paid'] / $stat['total_price']) * 100 : 0;
                            @endphp
                            <tr>
                                <td><strong>{{ $levelName }}</strong></td>
                                <td>{{ $stat['student_count'] }}</td>
                                <td>{{ number_format($stat['total_price'], 2) }} €</td>
                                <td class="text-success fw-bold">{{ number_format($stat['total_paid'], 2) }} €</td>
                                <td class="text-danger">{{ number_format($stat['total_remaining'], 2) }} €</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar 
                                            {{ $paymentRate >= 80 ? 'bg-success' : ($paymentRate >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                            style="width: {{ $paymentRate }}%">
                                            {{ number_format($paymentRate, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>TOTAL</th>
                                <th>{{ collect($stats)->sum('student_count') }}</th>
                                <th>{{ number_format(collect($stats)->sum('total_price'), 2) }} €</th>
                                <th>{{ number_format(collect($stats)->sum('total_paid'), 2) }} €</th>
                                <th>{{ number_format(collect($stats)->sum('total_remaining'), 2) }} €</th>
                                <th>
                                    @php
                                        $totalPrice = collect($stats)->sum('total_price');
                                        $totalPaid = collect($stats)->sum('total_paid');
                                        $totalRate = $totalPrice > 0 ? ($totalPaid / $totalPrice) * 100 : 0;
                                    @endphp
                                    {{ number_format($totalRate, 1) }}%
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Récapitulatif -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Récapitulatif
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalExpected = collect($stats)->sum('total_price');
                    $totalPaid = collect($stats)->sum('total_paid');
                    $totalRemaining = collect($stats)->sum('total_remaining');
                @endphp
                
                <div class="text-center mb-4">
                    <div class="display-4 text-primary fw-bold">
                        {{ number_format($totalPaid, 0) }}€
                    </div>
                    <small class="text-muted">Total perçu</small>
                </div>

                <div class="mb-3">
                    <strong>Total attendu :</strong>
                    <span class="float-end">{{ number_format($totalExpected, 2) }} €</span>
                </div>
                
                <div class="mb-3">
                    <strong>Reste à percevoir :</strong>
                    <span class="float-end text-danger">{{ number_format($totalRemaining, 2) }} €</span>
                </div>

                <div class="mb-3">
                    <strong>Taux de perception :</strong>
                    <span class="float-end text-success">
                        {{ $totalExpected > 0 ? number_format(($totalPaid / $totalExpected) * 100, 1) : 0 }}%
                    </span>
                </div>

                <hr>

                <h6 class="text-primary">Répartition par niveau :</h6>
                @foreach($stats as $levelName => $stat)
                <div class="mb-2">
                    <small>{{ $levelName }}</small>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ number_format($stat['total_paid'], 0) }}€</small>
                        <small class="text-muted">
                            {{ $totalPaid > 0 ? number_format(($stat['total_paid'] / $totalPaid) * 100, 1) : 0 }}%
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Historique des paiements récents -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Derniers Paiements
                </h5>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Étudiant</th>
                                    <th>Niveau</th>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Reçu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $payment->registration->student->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->registration->languageLevel->name }}</span>
                                    </td>
                                    <td class="text-success fw-bold">{{ number_format($payment->amount_paid, 2) }} €</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.receipt', $payment) }}" 
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $recentPayments->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun paiement enregistré</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection