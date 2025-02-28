@extends('layouts.app')

@section('title', 'Tableau de bord chauffeur - GrandTaxiGo')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bienvenue, {{ Auth::user()->name }}</h5>
                <a href="{{ route('availability.edit') }}" class="btn btn-primary">Gérer mes disponibilités</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Demandes en attente</h5>
                                <p class="display-4">{{ $pendingRequestsCount ?? 0 }}</p>
                                <a href="{{ route('driver.requests', ['filter' => 'pending']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Trajets à venir</h5>
                                <p class="display-4">{{ $upcomingRidesCount ?? 0 }}</p>
                                <a href="{{ route('driver.rides', ['filter' => 'upcoming']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Trajets terminés</h5>
                                <p class="display-4">{{ $completedRidesCount ?? 0 }}</p>
                                <a href="{{ route('driver.rides', ['filter' => 'completed']) }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Revenus ce mois</h5>
                                <p class="display-4">{{ $currentMonthRevenue ?? 0 }} DH</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Demandes en attente</h5>
            </div>
            <div class="card-body">
                @if(isset($pendingRequests) && count($pendingRequests) > 0)
                    <div class="list-group">
                        @foreach($pendingRequests as $booking)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between mb-2">
                                    <h5 class="mb-1">{{ $booking->ride->departure_location }} → {{ $booking->ride->destination }}</h5>
                                    <small>{{ $booking->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    <strong>Date et heure:</strong> {{ $booking->ride->departure_time->format('d/m/Y H:i') }}<br>
                                    <strong>Passager:</strong> {{ $booking->passenger->name }}
                                </p>
                                <div class="d-flex justify-content-end mt-2">
                                    <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="me-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                                    </form>
                                    <form action="{{ route('bookings.accept', $booking) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Accepter</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Vous n'avez aucune demande en attente.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prochains trajets</h5>
            </div>
            <div class="card-body">
                @if(isset($upcomingRides) && count($upcomingRides) > 0)
                    <div class="list-group">
                        @foreach($upcomingRides as $ride)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between mb-2">
                                    <h5 class="mb-1">{{ $ride->departure_location }} → {{ $ride->destination }}</h5>
                                    <span class="badge bg-primary">{{ $ride->bookings_count }} passager(s)</span>
                                </div>
                                <p class="mb-1">
                                    <strong>Date et heure:</strong> {{ $ride->departure_time->format('d/m/Y H:i') }}
                                </p>
                                <div class="text-end">
                                    <a href="{{ route('driver.rides.show', $ride) }}" class="btn btn-sm btn-info">Détails</a>
                                    </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Vous n'avez aucun trajet à venir.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection