<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('web')->check()) {
            // Get package ID from the route if it exists
            $package = $request->route('package');
            $packageId = null;

            if ($package instanceof \App\Models\Package) {
                $packageId = $package->id;
            } elseif (is_numeric($package)) {
                $packageId = $package;
            }

            if ($packageId) {
                session(['redirect_package' => $packageId]);
            }

            return redirect()->route('login')->with('info', 'Please login to view package details.');
        }

        return $next($request);
    }
}
