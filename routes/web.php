<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les conducteurs
    Route::middleware(['driver'])->group(function () {
        Route::resource('trips', TripController::class);
        Route::resource('vehicles', VehicleController::class);
        Route::put('/reservations/{reservation}/accept', [ReservationController::class, 'accept'])->name('reservations.accept');
        Route::put('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    });
    
    // Routes pour les passagers
    Route::middleware(['passenger'])->group(function () {
        Route::get('/trips/search', [TripController::class, 'search'])->name('trips.search');
        Route::resource('reservations', ReservationController::class);
        Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    });

    Route::get('/driver/reservations', [ReservationController::class, 'driverReservations'])->name('reservations.driver');
    Route::patch('/driver/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');

    // Gestion des disponibilités
    Route::get('/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::get('/availabilities/create', [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

});

require __DIR__.'/auth.php';