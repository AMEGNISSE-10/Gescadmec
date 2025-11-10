@extends('layouts.app')

@section('title', 'Nouveau Besoin')

@section('actions')
    <a href="{{ route('needs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-comment me-2"></i>Nouveau Besoin
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('needs.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Étudiant *</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Choisir un étudiant</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                    {{ (request('student_id') == $student->id || old('student_id') == $student->id) ? 'selected' : '' }}>
                                    {{ $student->name }} - {{ $student->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description du besoin *</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="5" placeholder="Décrivez le besoin exprimé par l'étudiant..." 
                                  required>{{ old('description') }}</textarea>
                        <div class="form-text">
                            Exemples : besoin de cours supplémentaires, difficultés particulières, 
                            demande d'aménagement, etc.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Ce besoin sera enregistré et pourra être 
                        suivi jusqu'à sa résolution.
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-save me-2"></i>Enregistrer le besoin
                        </button>
                        <a href="{{ route('needs.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection