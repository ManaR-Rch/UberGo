@extends('layouts.app')

@section('title', 'Inscription - GrandTaxiGo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Inscription</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Numéro de téléphone</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Je suis un</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Sélectionnez votre rôle</option>
                            <option value="passenger" {{ old('role') == 'passenger' ? 'selected' : '' }}>Passager</option>
                            <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Chauffeur</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="driver-fields" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label for="taxi_license" class="form-label">Numéro de licence de taxi</label>
                            <input id="taxi_license" type="text" class="form-control @error('taxi_license') is-invalid @enderror" name="taxi_license" value="{{ old('taxi_license') }}">
                            @error('taxi_license')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="vehicle_info" class="form-label">Information sur le véhicule</label>
                            <input id="vehicle_info" type="text" class="form-control @error('vehicle_info') is-invalid @enderror" name="vehicle_info" value="{{ old('vehicle_info') }}">
                            @error('vehicle_info')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Photo de profil (obligatoire)</label>
                        <input id="profile_picture" type="file" class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" required>
                        @error('profile_picture')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Vous avez déjà un compte? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const driverFields = document.getElementById('driver-fields');
        
        function toggleDriverFields() {
            if (roleSelect.value === 'driver') {
                driverFields.style.display = 'block';
            } else {
                driverFields.style.display = 'none';
            }
        }
        
        toggleDriverFields();
        roleSelect.addEventListener('change', toggleDriverFields);
    });
</script>
@endsection
            