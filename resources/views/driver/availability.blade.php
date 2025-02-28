@extends('layouts.app')

@section('title', 'Gérer mes disponibilités - GrandTaxiGo')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Gérer mes disponibilités</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('availability.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" {{ $driver->is_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_available">Je suis actuellement disponible pour des trajets</label>
                    </div>
                </div>
            </div>
            
            <h6 class="mb-3">Zones de service</h6>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="service_locations" class="form-label">Villes où vous proposez vos services</label>
                        <select class="form-select" id="service_locations" name="service_locations[]" multiple>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ in_array($city, $driver->service_locations ?? []) ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Sélectionnez plusieurs villes en maintenant la touche Ctrl (ou Cmd sur Mac) enfoncée.</div>
                    </div>
                </div>
            </div>
            
            <h6 class="mb-3">Horaires réguliers</h6>
            <div class="row">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input day-toggle" type="checkbox" id="{{ $day }}_available" name="schedule[{{ $day }}][available]" {{ isset($driver->schedule[$day]['available']) && $driver->schedule[$day]['available'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $day }}_available">
                                        {{ ucfirst(trans("days.$day")) }}
                                    </label>
                                </div>
                                
                                <div class="time-slots" id="{{ $day }}_slots" {{ isset($driver->schedule[$day]['available']) && $driver->schedule[$day]['available'] ? '' : 'style=display:none' }}>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label for="{{ $day }}_start" class="form-label small">Début</label>
                                            <input type="time" class="form-control" id="{{ $day }}_start" name="schedule[{{ $day }}][start]" value="{{ $driver->schedule[$day]['start'] ?? '08:00' }}">
                                        </div>
                                        <div class="col-6">
                                            <label for="{{ $day }}_end" class="form-label small">Fin</label>
                                            <input type="time" class="form-control" id="{{ $day }}_end" name="schedule[{{ $day }}][end]" value="{{ $driver->schedule[$day]['end'] ?? '18:00' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer mes disponibilités</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dayToggles = document.querySelectorAll('.day-toggle');
        
        dayToggles.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const day = this.id.replace('_available', '');
                const slots = document.getElementById(day + '_slots');
                
                if (this.checked) {
                    slots.style.display = 'block';
                } else {
                    slots.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection