@extends('layouts.app')

@section('title', 'Mes réservations - GrandTaxiGo')

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ request('filter', 'all') == 'all' ? 'active' : '' }}" href="{{ route('bookings.index', ['filter' => 'all']) }}">Toutes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'upcoming' ? 'active' : '' }}" href="{{ route('bookings.index', ['filter' => 'upcoming']) }}">À venir</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'pending' ? 'active' : '' }}" href="{{ route('bookings.index', ['filter' => 'pending']) }}">En attente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'completed' ? 'active' : '' }}" href="{{ route('bookings.index', ['filter' => 'completed']) }}">Terminées</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'cancelled' ? 'active' : '' }}" href="{{ route('bookings.index', ['filter' => 'cancelled']) }}">Annulées</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        @if(isset($bookings) && count($bookings) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date et heure</th>
                            <th>Trajet</th>
                            <th>Chauffeur</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->ride->departure_time->format('d/m/Y H:i') }}</td>
                                <td>{{ $booking->ride->departure_location }} → {{ $booking->ride->destination }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/'.$booking->ride->driver->profile_picture) }}" class="rounded-circle me-2" width="30" height="30">
                                        {{ $booking->ride->driver->name }}
                                    </div>
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="badge bg-success">Confirmé</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Terminé</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">Annulé</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-info">Détails</a>
                                    
                                    @if($booking->can_cancel)
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelBookingModal{{ $booking->id }}">
                                            Annuler
                                        </button>
                                        
                                        <!-- Modal de confirmation d'annulation -->
                                        <div class="modal fade" id="cancelBookingModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer l'annulation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir annuler cette réservation?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->status === 'completed' && !$booking->has_rated)
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#rateDriverModal{{ $booking->id }}">
                                            Évaluer
                                        </button>
                                        
                                        <!-- Modal d'évaluation du chauffeur -->
                                        <div class="modal fade" id="rateDriverModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('ratings.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                        <input type="hidden" name="driver_id" value="{{ $booking->ride->driver_id }}">
                                                        
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Évaluer le chauffeur</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Notez votre expérience</label>
                                                                <div class="rating">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="rating" id="rating1{{ $booking->id }}" value="1" required>
                                                                        <label class="form-check-label" for="rating1{{ $booking->id }}">1</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="rating" id="rating2{{ $booking->id }}" value="2">
                                                                        <label class="form-check-label" for="rating2{{ $booking->id }}">2</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="rating" id="rating3{{ $booking->id }}" value="3">
                                                                        <label class="form-check-label" for="rating3{{ $booking->id }}">3</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="rating" id="rating4{{ $booking->id }}" value="4">
                                                                        <label class="form-check-label" for="rating4{{ $booking->id }}">4</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="rating" id="rating5{{ $booking->id }}" value="5">
                                                                        <label class="form-check-label" for="rating5{{ $booking->id }}">5</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="comment{{ $booking->id }}" class="form-label">Commentaire (optionnel)</label>
                                                                <textarea class="form-control" id="comment{{ $booking->id }}" name="comment" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Soumettre</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune réservation trouvée.
                @if(request('filter', 'all') == 'all')
                    <a href="{{ route('rides.search') }}">Réservez un trajet maintenant</a>.
                @endif
            </div>
        @endif
    </div>
</div>
@endsection