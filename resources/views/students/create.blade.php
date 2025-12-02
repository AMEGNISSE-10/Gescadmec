@extends('layouts.app')

@section('title', 'Inscription Étudiant')

@section('actions')
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Nouvelle Inscription
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('students.store') }}">
                    @csrf
                    
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-user me-2"></i>Informations de l'étudiant
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <textarea class="form-control" id="address" name="address" rows="1">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-book me-2"></i>Informations du cours
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="language_level_id" class="form-label">Niveau de langue *</label>
                            <select class="form-control" id="language_level_id" name="language_level_id" required>
                                <option value="">Choisir un niveau</option>
                                @foreach($languageLevels as $level)
                                    <option value="{{ $level->id }}" 
                                            data-price="{{ $level->price }}"
                                            data-duration="{{ $level->duration_days }}"
                                            {{ old('language_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }} - {{ number_format($level->price, 0) }} XOF 
                                        ({{ $level->duration_days }} jours)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Date de début *</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ old('start_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-money-bill me-2"></i>Informations de paiement
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount_paid" class="form-label">Montant versé *</label>
                            <input type="number" step="0.01" class="form-control" id="amount_paid" 
                                   name="amount_paid" value="{{ old('amount_paid', 0) }}" required>
                            <div class="form-text">
                                Montant initial payé à l'inscription
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Méthode de paiement *</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Prix du cours : </strong>
                                <span id="course_price">0 XOF</span> | 
                                <strong>Durée : </strong>
                                <span id="course_duration">0 jours</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Inscrire l'étudiant
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const levelSelect = document.getElementById('language_level_id');
        const coursePrice = document.getElementById('course_price');
        const courseDuration = document.getElementById('course_duration');
        
        function updateCourseInfo() {
            const selectedOption = levelSelect.options[levelSelect.selectedIndex];
            if (selectedOption.value) {
                const price = selectedOption.getAttribute('data-price');
                const duration = selectedOption.getAttribute('data-duration');
                coursePrice.textContent = parseFloat(price).toFixed(0) + ' XOF';
                courseDuration.textContent = duration + ' jours';
            } else {
                coursePrice.textContent = '0 XOF';
                courseDuration.textContent = '0 jours';
            }
        }
        
        levelSelect.addEventListener('change', updateCourseInfo);
        updateCourseInfo(); // Initial call
    });
</script>
@endsection