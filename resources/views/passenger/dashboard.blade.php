@extends('layouts.app')

@section('title', 'Tableau de bord passager - GrandTaxiGo')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bienvenue, {{ Auth::user()->name }}</h5>
                <a href="{{ route('rides.search') }}" class="btn btn-primary">Rechercher un trajet</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Réservations à venir</h5>
                                <p class="display-4">{{ $upcomingBookingsCount ?? 0 }}</p>
                                <a href="{{ route('bookings.index', ['filter' => 'upcoming']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Trajets terminés</h5>
                                <p class="display-4">{{ $completedRidesCount ?? 0 }}</p>
                                <a href="{{ route('bookings.index', ['filter' => 'completed']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Réservations annulées</h5>
                                <p class="display-4">{{ $cancelledBookingsCount ?? 0 }}</p>
                                <a href="{{ route('bookings.index', ['filter' => 'cancelled']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prochains trajets</h5>
            </div>
            <div class="card-body">
                @if(isset($upcomingBookings) && count($upcomingBookings) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date et heure</th>
                                    <th>Départ</th>
                                    <th>Destination</th>
                                    <th>Chauffeur</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingBookings as $booking)
                                <tr>
                                    <td>{{ $booking->ride->departure_time->format('d/m/Y H:i') }}</td>
                                    <td>{{ $booking->ride->departure_location }}</td>
                                    <td>{{ $booking->ride->destination }}</td>
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        Vous n'avez aucun trajet à venir. <a href="{{ route('rides.search') }}">Réservez un trajet maintenant</a>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection