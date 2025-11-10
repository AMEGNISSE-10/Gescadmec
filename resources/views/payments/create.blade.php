@extends('layouts.app')

@section('title', 'Nouveau Paiement')

@section('actions')
    <a href="{{ route('students.show', $registration->student_id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill me-2"></i>Nouveau Paiement
                </h5>
            </div>
            <div class="card-body">
                <!-- Informations de l'étudiant -->
                <div class="alert alert-info">
                    <h6 class="alert-heading">{{ $registration->student->name }}</h6>
                    <p class="mb-1">
                        <strong>Niveau :</strong> {{ $registration->languageLevel->name }}<br>
                        <strong>Prix du cours :</strong> {{ number_format($registration->languageLevel->price, 2) }} €<br>
                        <strong>Déjà payé :</strong> {{ number_format($registration->total_paid, 2) }} €<br>
                        <strong>Reste à payer :</strong> 
                        <span class="{{ $registration->remaining_amount > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ number_format($registration->remaining_amount, 2) }} €
                        </span>
                    </p>
                </div>

                <form method="POST" action="{{ route('payments.store', $registration) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Montant du paiement *</label>
                        <input type="number" step="0.01" class="form-control" id="amount_paid" 
                               name="amount_paid" value="{{ old('amount_paid') }}" 
                               max="{{ $registration->remaining_amount }}" required>
                        <div class="form-text">
                            Maximum : {{ number_format($registration->remaining_amount, 2) }} €
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Méthode de paiement *</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                        </select>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action ne peut pas être annulée.
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check me-2"></i>Enregistrer le paiement
                        </button>
                        <a href="{{ route('students.show', $registration->student_id) }}" 
                           class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection