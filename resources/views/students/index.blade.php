@extends('layouts.app')

@section('title', 'Liste des Étudiants')

@section('actions')
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i>Nouvelle Inscription
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Niveau</th>
                            <th>Total Payé</th>
                            <th>Reste à Payer</th>
                            <th>Jours Restants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @foreach($student->registrations as $registration)
                            <tr>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                    @if($registration->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($registration->status == 'completed')
                                        <span class="badge bg-secondary">Terminé</span>
                                    @else
                                        <span class="badge bg-danger">Annulé</span>
                                    @endif
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $registration->languageLevel->name }}</span>
                                    <br>
                                    <small class="text-muted">{{ number_format($registration->languageLevel->price, 0) }} XOF</small>
                                </td>
                                <td class="text-success fw-bold">
                                    {{ number_format($registration->total_paid, 0) }} XOF
                                </td>
                                <td>
                                    @if($registration->is_fully_paid)
                                        <span class="badge bg-success">Soldé</span>
                                    @else
                                        <span class="text-danger fw-bold">
                                            {{ number_format($registration->remaining_amount, 0) }} XOF
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($registration->status == 'active')
                                        <span class="badge {{ $registration->days_remaining < 30 ? 'bg-warning' : 'bg-primary' }}">
                                            {{ $registration->days_remaining }} jours
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Terminé</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('students.show', $student) }}" 
                                           class="btn btn-sm btn-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('payments.create', $registration) }}" 
                                           class="btn btn-sm btn-success" title="Ajouter paiement">
                                            <i class="fas fa-money-bill"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun étudiant inscrit</h4>
                <p class="text-muted">Commencez par inscrire votre premier étudiant.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Inscrire un étudiant
                </a>
            </div>
        @endif
    </div>
</div>
@endsection