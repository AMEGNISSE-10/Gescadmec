@extends('layouts.app')

@section('title', 'Détails de ' . $student->name)

@section('actions')
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Carte informations étudiant -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Informations
                </h5>
            </div>
            <div class="card-body">
                <h6 class="card-title">{{ $student->name }}</h6>
                <p class="card-text">
                    <i class="fas fa-envelope me-2 text-muted"></i>{{ $student->email }}<br>
                    <i class="fas fa-phone me-2 text-muted"></i>{{ $student->phone }}<br>
                    @if($student->address)
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $student->address }}
                    @endif
                </p>
                <a href="{{ route('needs.create') }}?student_id={{ $student->id }}" 
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-comment me-1"></i>Ajouter un besoin
                </a>
            </div>
        </div>

        <!-- Carte besoins -->
        @if($student->needs->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-comments me-2"></i>Besoins ({{ $student->needs->count() }})
                </h6>
            </div>
            <div class="card-body">
                @foreach($student->needs as $need)
                    <div class="border-start border-{{ $need->status == 'pending' ? 'warning' : ($need->status == 'in_progress' ? 'info' : 'success') }} border-3 ps-3 mb-3">
                        <small class="text-muted">{{ $need->created_at->format('d/m/Y') }}</small>
                        <p class="mb-1">{{ Str::limit($need->description, 100) }}</p>
                        <span class="badge bg-{{ $need->status == 'pending' ? 'warning' : ($need->status == 'in_progress' ? 'info' : 'success') }}">
                            {{ $need->status }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <!-- Inscriptions -->
        @foreach($student->registrations as $registration)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center
                {{ $registration->status == 'active' ? 'bg-success' : 'bg-secondary' }} text-white">
                <h6 class="mb-0">
                    <i class="fas fa-book me-2"></i>
                    {{ $registration->languageLevel->name }} 
                    - {{ number_format($registration->languageLevel->price, 2) }}€
                </h6>
                <span class="badge bg-light text-dark">
                    {{ \Carbon\Carbon::parse($registration->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($registration->end_date)->format('d/m/Y') }}
                </span>
            </div>
            <div class="card-body">
                <!-- Barre de progression -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progression du paiement</small>
                        <small>{{ number_format($registration->payment_progress, 0) }}%</small>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar 
                            {{ $registration->is_fully_paid ? 'bg-success' : 'bg-warning' }}" 
                            style="width: {{ $registration->payment_progress }}%">
                        </div>
                    </div>
                </div>

                <!-- Informations financières -->
                <div class="row text-center mb-3">
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Total Payé</small>
                            <strong class="text-success">{{ number_format($registration->total_paid, 2) }} €</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Reste à Payer</small>
                            <strong class="{{ $registration->is_fully_paid ? 'text-success' : 'text-danger' }}">
                                {{ number_format($registration->remaining_amount, 2) }} €
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Jours Restants</small>
                            <strong class="text-info">{{ $registration->days_remaining }} jours</strong>
                        </div>
                    </div>
                </div>

                <!-- Historique des paiements -->
                <h6 class="text-primary mb-3">
                    <i class="fas fa-history me-2"></i>Historique des paiements
                </h6>
                
                @if($registration->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Reçu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registration->payments as $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
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
                @else
                    <p class="text-muted text-center">Aucun paiement enregistré</p>
                @endif

                <!-- Bouton ajouter paiement -->
                @if(!$registration->is_fully_paid && $registration->status == 'active')
                <div class="text-center mt-3">
                    <a href="{{ route('payments.create', $registration) }}" 
                       class="btn btn-success">
                        <i class="fas fa-money-bill me-2"></i>Ajouter un paiement
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection