<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = auth()->user()->tripsAsDriver()->orderBy('departure_time', 'asc')->get();
        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
            'available_seats' => 'required|integer|min:1|max:6',
            'price' => 'required|numeric|min:0',
        ]);

        $trip = Trip::create([
            'driver_id' => auth()->id(),
            'departure_city' => $validated['departure_city'],
            'arrival_city' => $validated['arrival_city'],
            'departure_time' => $validated['departure_time'],
            'available_seats' => $validated['available_seats'],
            'price' => $validated['price'],
            'is_active' => true,
        ]);

        return redirect()->route('trips.index')
            ->with('success', 'Trajet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $this->authorize('view', $trip);
        $reservations = $trip->reservations;
        return view('trips.show', compact('trip', 'reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $this->authorize('update', $trip);
        return view('trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);
        
        $validated = $request->validate([
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
            'available_seats' => 'required|integer|min:1|max:6',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $trip->update($validated);

        return redirect()->route('trips.index')
            ->with('success', 'Trajet mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        $this->authorize('delete', $trip);
        
        // Vérifier s'il y a des réservations actives
        if ($trip->reservations()->whereIn('status', ['pending', 'accepted'])->exists()) {
            return back()->with('error', 'Impossible de supprimer ce trajet car il existe des réservations actives.');
        }

        $trip->delete();

        return redirect()->route('trips.index')
            ->with('success', 'Trajet supprimé avec succès.');
    }

    /**
     * Search for available trips
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        $startDate = Carbon::parse($validated['departure_date'])->startOfDay();
        $endDate = Carbon::parse($validated['departure_date'])->endOfDay();

        $trips = Trip::where('departure_city', $validated['departure_city'])
            ->where('arrival_city', $validated['arrival_city'])
            ->whereBetween('departure_time', [$startDate, $endDate])
            ->where('is_active', true)
            ->where('available_seats', '>', 0)
            ->with('driver')
            ->get();

        return view('trips.search', compact('trips', 'validated'));
    }
}