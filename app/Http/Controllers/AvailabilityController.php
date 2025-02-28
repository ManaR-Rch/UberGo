<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Availability::class);
        
        $availabilities = Availability::where('driver_id', Auth::user()->driver->id)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
            
        return view('availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        $this->authorize('create', Availability::class);
        return view('availabilities.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Availability::class);
        
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Availability::create([
            'driver_id' => Auth::user()->driver->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité ajoutée avec succès!');
    }

    public function destroy(Availability $availability)
    {
        $this->authorize('delete', $availability);
        
        $availability->delete();
        
        return redirect()->route('availabilities.index')->with('success', 'Disponibilité supprimée avec succès!');
    }
}