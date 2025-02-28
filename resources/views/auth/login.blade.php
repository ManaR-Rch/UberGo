@extends('layouts.app')

@section('title', 'Connexion - GrandTaxiGo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Connexion</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}">Mot de passe oublié?</a>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer text-center">
                Vous n'avez pas de compte? <a href="{{ route('register') }}">S'inscrire</a>
            </div>
        </div>
    </div>
</div>
@endsection