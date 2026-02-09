<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends AgencyBaseController
{
    public function redirect()
    {
        return redirect()->route('agency.dashboard');
    }

    public function index()
    {
        $agency = $this->getActiveAgency();
        
        $stats = [
            'total_packages' => Package::where('agency_id', $agency->id)->count(),
            'total_bookings' => Booking::whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->count(),
            'pending_bookings' => Booking::where('status', 'pending')
                ->whereHas('package', function($query) use ($agency) {
                    $query->where('agency_id', $agency->id);
                })->count(),
            'total_revenue' => Booking::where('status', 'confirmed')
                ->whereHas('package', function($query) use ($agency) {
                    $query->where('agency_id', $agency->id);
                })->sum('total_amount'),
        ];

        $recentBookings = Booking::with(['package', 'user'])
            ->whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('agency.dashboard', compact('agency', 'stats', 'recentBookings'));
    }
}
