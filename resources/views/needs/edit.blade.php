@extends('layouts.app')

@section('title', 'Modifier Besoin')

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
                    <i class="fas fa-comment-edit me-2"></i>Modifier le Besoin
                </h5>
            </div>
            <div class="card-body">
                <!-- Messages d'erreur -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Erreurs :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('needs.update', $need) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Étudiant *</label>
                        <select class="form-control @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" required>
                            <option value="">Choisir un étudiant</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                    {{ old('student_id', $need->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} - {{ $student->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description du besoin *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" 
                                  placeholder="Décrivez le besoin exprimé par l'étudiant..." 
                                  required>{{ old('description', $need->description) }}</textarea>
                        <div class="form-text">
                            Exemples : besoin de cours supplémentaires, difficultés particulières, 
                            demande d'aménagement, etc.
                        </div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="pending" {{ old('status', $need->status) == 'pending' ? 'selected' : '' }}>
                                    <i class="fas fa-clock"></i> En attente
                                </option>
                                <option value="in_progress" {{ old('status', $need->status) == 'in_progress' ? 'selected' : '' }}>
                                    <i class="fas fa-spinner"></i> En cours
                                </option>
                                <option value="resolved" {{ old('status', $need->status) == 'resolved' ? 'selected' : '' }}>
                                    <i class="fas fa-check"></i> Résolu
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="response" class="form-label">Réponse</label>
                        <textarea class="form-control @error('response') is-invalid @enderror" 
                                  id="response" name="response" rows="3" 
                                  placeholder="Réponse ou action prise...">{{ old('response', $need->response) }}</textarea>
                        @error('response')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Les modifications seront enregistrées et le suivi du besoin sera mis à jour.
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
