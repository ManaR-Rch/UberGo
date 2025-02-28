<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = auth()->user()->reservations()->with('trip')->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirection vers recherche de trajets
        return redirect()->route('trips.search');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seats_reserved' => 'required|integer|min:1',
        ]);

        $trip = Trip::findOrFail($validated['trip_id']);

        // Vérifier si le passager n'est pas le conducteur
        if ($trip->driver_id == auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas réserver votre propre trajet.');
        }

        // Vérifier la disponibilité des places
        if ($trip->available_seats < $validated['seats_reserved']) {
            return back()->with('error', 'Il n\'y a pas assez de places disponibles.');
        }

        // Créer la réservation
        $reservation = Reservation::create([
            'passenger_id' => auth()->id(),
            'trip_id' => $validated['trip_id'],
            'seats_reserved' => $validated['seats_reserved'],
            'status' => 'pending',
        ]);

        // Mettre à jour le nombre de places disponibles
        $trip->update([
            'available_seats' => $trip->available_seats - $validated['seats_reserved']
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation effectuée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        // Vérifier si l'annulation est possible (avant 1h du départ)
        $departureTime = $reservation->trip->departure_time;
        if (Carbon::now()->addHour()->isAfter($departureTime)) {
            return back()->with('error', 'Vous ne pouvez plus annuler cette réservation (moins d\'une heure avant le départ).');
        }

        $reservation->update(['status' => 'cancelled']);

        // Restituer les places
        $trip = $reservation->trip;
        $trip->update([
            'available_seats' => $trip->available_seats + $reservation->seats_reserved
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès.');
    }

    /**
     * Accept a reservation (driver only)
     */
    public function accept(Reservation $reservation)
    {
        $this->authorize('manage', $reservation);

        $reservation->update(['status' => 'accepted']);

        return back()->with('success', 'Réservation acceptée.');
    }

    /**
     * Reject a reservation (driver only)
     */
    public function reject(Reservation $reservation)
    {
        $this->authorize('manage', $reservation);

        $reservation->update(['status' => 'rejected']);

        // Restituer les places
        $trip = $reservation->trip;
        $trip->update([
            'available_seats' => $trip->available_seats + $reservation->seats_reserved
        ]);

        return back()->with('success', 'Réservation rejetée.');
    }
}