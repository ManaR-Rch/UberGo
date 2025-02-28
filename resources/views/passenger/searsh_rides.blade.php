@extends('layouts.app')

@section('title', 'Rechercher un trajet - GrandTaxiGo')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rechercher un trajet</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rides.search') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="departur e" class="form-label">Lieu de départ</label>
                            <input type="text" class="form-control" id="departure" name="departure" value="{{ request('departure') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" class="form-control" id="destination" name="destination" value="{{ request('destination') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="departure_time" class="form-label">Date et heure</label>
                            <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value="{{ request('departure_time') }}" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(isset($drivers) && count($drivers) > 0)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chauffeurs disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($drivers as $driver)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ asset('storage/'.$driver->profile_picture) }}" class="rounded-circle me-3" width="60" height="60">
                                            <div>
                                                <h5 class="card-title mb-0">{{ $driver->name }}</h5>
                                                <div class="text-muted small">
                                                    <i class="bi bi-star-fill text-warning"></i> {{ number_format($driver->rating, 1) }} ({{ $driver->ratings_count }} avis)
                                                </div>
                                            </div>
                                        </div>
                                        <p class="card-text">
                                            <strong>Véhicule:</strong> {{ $driver->vehicle_info }}<br>
                                            <strong>Trajets effectués:</strong> {{ $driver->completed_rides_count }}
                                        </p>
                                        <form action="{{ route('bookings.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                                            <input type="hidden" name="departure_location" value="{{ request('departure') }}">
                                            <input type="hidden" name="destination" value="{{ request('destination') }}">
                                            <input type="hidden" name="departure_time" value="{{ request('departure_time') }}">
                                            
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Réserver</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @elseif(request('departure'))
        <div class="col-md-12">
            <div class="alert alert-info">
                Aucun chauffeur disponible pour ce trajet. Essayez de modifier la date ou l'heure de départ.
            </div>
        </div>
    @endif
</div>
@endsection