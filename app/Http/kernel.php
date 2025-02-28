<?php 

protected $routeMiddleware = [
    'driver' => \App\Http\Middleware\DriverMiddleware::class,
    'passenger' => \App\Http\Middleware\PassengerMiddleware::class,
];