<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicle = auth()->user()->vehicle;
        return view('vehicles.index', compact('vehicle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier si l'utilisateur a déjà un véhicule
        if (auth()->user()->vehicle) {
            return redirect()->route('vehicles.index')
                ->with('info', 'Vous avez déjà enregistré un véhicule.');
        }

        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:255|unique:vehicles',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:6',
            'color' => 'required|string|max:255',
        ]);

        Vehicle::create([
            'user_id' => auth()->id(),
            'registration_number' => $validated['registration_number'],
            'model' => $validated['model'],
            'capacity' => $validated['capacity'],
            'color' => $validated['color'],
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Véhicule enregistré avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'registration_number' => 'required|string|max:255|unique:vehicles,registration_number,' . $vehicle->id,
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:6',
            'color' => 'required|string|max:255',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Véhicule mis à jour avec succès.');
    }
}