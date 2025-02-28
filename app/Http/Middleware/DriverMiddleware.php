<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DriverMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isDriver()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section.');
        }

        return $next($request);
    }
}