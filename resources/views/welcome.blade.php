<!-- 1. Page d'accueil -->
<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrandTaxiGo - Réservation de grands taxis interurbains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">GrandTaxiGo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Tableau de bord</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 mb-4">Réservez votre grand taxi interurbain</h1>
                <p class="lead mb-5">Trouvez rapidement un chauffeur disponible pour vos trajets entre villes</p>
                
                @guest
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Créer un compte</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Se connecter</a>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Rechercher un trajet</h5>
                            <form action="{{ route('rides.search') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="departure" placeholder="Lieu de départ" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="destination" placeholder="Destination" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="datetime-local" class="form-control" name="departure_time" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Rechercher</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} GrandTaxiGo. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>