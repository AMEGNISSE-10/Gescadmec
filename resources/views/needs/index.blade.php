@extends('layouts.app')

@section('title', 'Gestion des Besoins')

@section('actions')
    <a href="{{ route('needs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouveau Besoin
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if($needs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Réponse</th>
                            <th>Secrétaire</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($needs as $need)
                        <tr>
                            <td>{{ $need->created_at->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $need->student->name }}</strong><br>
                                <small class="text-muted">{{ $need->student->email }}</small>
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    {{ $need->description }}
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    {{ $need->status == 'pending' ? 'bg-warning' : 
                                       ($need->status == 'in_progress' ? 'bg-info' : 'bg-success') }}">
                                    {{ $need->status }}
                                </span>
                            </td>
                            <td>
                                @if($need->response)
                                    <small>{{ Str::limit($need->response, 50) }}</small>
                                @else
                                    <span class="text-muted">Aucune réponse</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $need->secretary->name }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#needModal{{ $need->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal pour mise à jour -->
                        <div class="modal fade" id="needModal{{ $need->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mettre à jour le besoin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('needs.updateStatus', $need) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Étudiant</label>
                                                <input type="text" class="form-control" 
                                                       value="{{ $need->student->name }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" rows="3" readonly>{{ $need->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Statut</label>
                                                <select class="form-control" name="status">
                                                    <option value="pending" {{ $need->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                                    <option value="in_progress" {{ $need->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                                    <option value="resolved" {{ $need->status == 'resolved' ? 'selected' : '' }}>Résolu</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Réponse</label>
                                                <textarea class="form-control" name="response" rows="3" 
                                                          placeholder="Réponse ou action prise...">{{ $need->response }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun besoin enregistré</h4>
                <p class="text-muted">Les besoins exprimés par les étudiants apparaîtront ici.</p>
                <a href="{{ route('needs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Ajouter un besoin
                </a>
            </div>
        @endif
    </div>
</div>
@endsection