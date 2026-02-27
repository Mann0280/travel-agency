<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $today = date('Y-m-d');

        // Check if this IP has already visited today
        $exists = \App\Models\Visitor::where('ip_address', $ip)
            ->where('visit_date', $today)
            ->exists();

        if (!$exists) {
            try {
                \App\Models\Visitor::create([
                    'ip_address' => $ip,
                    'visit_date' => $today
                ]);
            } catch (\Exception $e) {
                // Ignore unique constraint violations if they occur between check and create
            }
        }

        return $next($request);
    }
}
